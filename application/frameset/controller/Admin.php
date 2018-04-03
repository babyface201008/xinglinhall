<?php
/**
 * User: Chris He
 * Date: 2017/6/15
 * Time: 15:22
 * Desc: 系统管理员控制器
 */
namespace app\frameset\controller;
use think\Controller;
use app\common\model\Admin as adminModel;
use app\common\model\AdminGroup as admingroupModel;
use app\common\model\AdminUsergroup as adminusergroupModel;
use think\Validate;
use think\Db;

class Admin extends Base{

    /**
     * 构造方法
     */
    public function _initialize(){

        $this->adminModel = new adminModel();
        $this->admingroupModel = new admingroupModel();
        $this->adminusergroupModel = new adminusergroupModel();
    }


    /**
     * 管理员列表页
     */
    public function index(){


        $groupData=$this->admingroupModel->getList('status = 1');
        $this->assign('groupData',$groupData);
        return $this->fetch('admin/index');
    }

    /**
     * 管理员列表页
     */
    public function add(){
        $groupData=$this->admingroupModel->getList('status = 1');
        $this->assign('groupData',$groupData);
        return $this->fetch('admin/add');
    }

    /**
     * 保存用户信息
    */
    public  function save(){

        //接受数据
        $postData = input('post.');

        //验证规则
        if($postData['op'] == 'add'){//添加
            $rule = [
                ['adminname','require|max:25','用户名必须!|用户名最多不能超过25个字符!'],
                ['password','require','密码必须!'],
                ['groupid','require','权限必须!'],
            ];
        }
        if($postData['op'] == 'edit'){//编辑
            $rule = [
                ['adminid','require','id!'],
            ];
        }
        $validate = new Validate($rule);
        if(!$validate->check($postData)){
            //验证不通过
            $this->_error($validate->getError());
        }

        if($postData['op'] == 'add'){//添加

            //账号名是否存在
            $checkAdminName = adminModel::getByAdminname($postData['adminname']);
            //判断账号名不要重复
            if($checkAdminName){
                $this->_error('账号名已存在,请重新输入');
            }
//print_r($postData['groupid']);exit;
            //启动事务
            Db::startTrans();

            try{

                $salt = randstr();//随机数
                $data = array(
                    'adminname'=>$postData['adminname'],
                    'password'=>encryptPassword($postData['password'],$salt),
                    'salt'=>$salt,
                    'createtime'=>time(),
                );
                //添加后台用户信息
                $this->adminModel->data($data)->save();
                $adminDataId=$this->adminModel->getLastInsID();


               if($postData['groupid']){
                    foreach ($postData['groupid'] as $v){
                        $group_data[]= ['adminid' => $adminDataId, 'groupid' => $v];
                    }
                    $this->adminusergroupModel->insertAll($group_data);
                }


                //提交事务
                Db::commit();

                $this->_success('添加成功','admin');
            } catch (\Exception $e) {

                //回滚事务
                Db::rollback();

                $this->_error('添加失败');
            }

        }elseif($postData['op'] == 'edit'){//修改

            //查询用户信息
            $adminData = adminModel::get($postData['adminid']);

            $data = array(
                'password'=>$postData['editpassword'],
                'email'=>$postData['email'],
                'phone'=>$postData['phone'],
                'realname'=>$postData['realname'],
                'islocked'=>$postData['islocked'],
            );

            //如果密码不为空，则修改密码
            if($data['password']){
                $data['password']= encryptPassword($data['password'],$adminData['salt']);
            }else{
                unset($data['password']);
            }

            //启动事务
            Db::startTrans();

            try{
                //更新账户信息
                $this->adminModel->save($data,['adminid'=>$postData['adminid']]);
                $this->adminusergroupModel->where("adminid='{$postData['adminid']}'")->delete();
                if($postData['groupid']){
                    foreach ($postData['groupid'] as $v){
                        $group_data[]= ['adminid' => $postData['adminid'], 'groupid' => $v];
                    }
                    $this->adminusergroupModel->insertAll($group_data);
                }

                //提交事务
                Db::commit();
                $this->_success('更新成功','admin');
            } catch (\Exception $e) {

                //回滚事务
                Db::rollback();
                $this->_error('更新失败');
            }

        }
    }


    /**
     * 改变账号状态
     */
    public function switchState(){

        $adminid = input('post.adminid');
        if(empty($adminid)) $this->_error('id不为空');

        //获取账户信息
        $adminData = adminModel::get($adminid);
        if(!$adminData){
            $this->_error('用户不存在');
        }

        //改变选中id账号状态
        $islocked = $adminData['islocked'];
        if($adminData['islocked']==1){
            $islocked = 0;
            $islockstr='停用';
        }elseif($adminData['islocked']==0){
            $islocked = 1;
            $islockstr='启用';

        }
        $data = array('islocked'=>$islocked);
        $where = array('adminid'=>$adminid);
        $result = $this->adminModel->save($data,$where);
        if($result) {
            $this->_success($islocked,'admin');
        }else{
            $this->_error('操作失败');
        }
    }

    /**
     * 当前登录用户修改密码方法
     */
    public function editPasswd(){

        return $this->fetch('admin/password',array('data'=>session('admin')));
    }
    /**
     * 处理当前登录用户密码修改
     */
    public function handleEditPasswd(){

        //接受数据
        $password = input('post.password');
        $ackpassword = input('post.ackpassword');

        if(empty($password)||empty($ackpassword)) $this->_error('密码和确认密码不能为空!');
        if(trim($password)!=trim($ackpassword)){
             $this->_error('新密码和确认密码不为空!');
        }
        $admin = session('admin');
        //密码
        $password = encryptPassword($password,$admin['salt']);
        if($this->adminModel->update(['password'=>$password],['adminid'=>$admin['adminid']])){
            $this->_success('密码修改成功','admin');
        }else{
            $this->_error('密码修改失败');
        }

    }

    //===============返回JSON格式==================

    /**
     * 获取管理员列表数据
     */
    public function getAdminListJSON(){

        //接受参数
        $page = input('post.page');
        $rows = input('post.rows');
        $orderDirection = input('post.order');
        $orderField = input('post.sort');

        //搜索栏
        $islocked = input('post.islocked');//账号状态
        $keyword = input('post.keyword');//关键字
        $datetype = input('post.datetype');//时间类型
        $dateStart = input('post.dateStart');//开始时间
        $timeStart = strtotime($dateStart);
        $dateEnd = input('post.dateEnd');//结束时间
        $timeEnd = strtotime($dateEnd)+24*3600;

        //组装搜索条件
        $map = [];
        if($keyword!=''){
            $map['adminname|email'] = ['like','%'.$keyword.'%'];
        }
        if($datetype=='createtime'){
            if($dateStart!='' && $dateEnd==''){
                $map['createtime'] = ['>=',$timeStart];
            }elseif($dateStart=='' && $dateEnd!=''){
                $map['createtime'] = ['<=',$timeEnd];
            }elseif($dateStart!='' && $dateEnd!=''){
                $map['createtime'] = ['between',[$timeStart,$timeEnd]];
            }
        }elseif($datetype=='last_login_time'){
            if($dateStart!='' && $dateEnd==''){
                $map['last_login_time'] = ['>=',$timeStart];
            }elseif($dateStart=='' && $dateEnd!=''){
                $map['last_login_time'] = ['<=',$timeEnd];
            }elseif($dateStart!='' && $dateEnd!=''){
                $map['last_login_time'] = ['between',[$timeStart,$timeEnd]];
            }
        }

        if($islocked!=''){
            $map['islocked'] = $islocked;
        }

        //排序
        $order = array('orderField'=>empty($orderField)?'createtime':$orderField,'orderDirection'=>empty($orderDirection)?'desc':$orderDirection);
        //获取列表
        $list = $this->adminModel->getPageList($map,"$page,$rows",$order);

        //总条数
        $total_rows = $this->adminModel->totalCount($map);

        //遍历获取其它字段
        $arrData = array();
        if($list){
            foreach($list as $val){

                $val['last_login_time']  = !empty($val['last_login_time'])?date('Y-m-d H:i:s',$val['last_login_time']):'';
                $val['createtime']  = date('Y-m-d H:i:s',$val['createtime']);
                $val['islocked'] = $val['islocked']==1?'禁用':'启用';
                $arrData[] = $val;
            }
        }
////        $aaData['total'] = $total_rows;
        $aaData['aaData'] = $arrData;
        echo json_encode($aaData);
//        $info=array('aaData'=> array(0=>array('id'=>'yyyy' ,'name'=>'nnnn'),1=>array('id'=>'yyyy' ,'name'=>'nnnn')));

//        echo(json_encode($info));
    }

    /*
* 删除信息
*/
    public function delete(){
        $adminid = input('post.ids');
        if(empty($adminid)) $this->_error('id不为空');

        //启动事务
        Db::startTrans();

        try{
            //删除用户数据
            $this->adminModel->where("adminid='{$adminid}'")->delete();

            $this->adminusergroupModel->where("adminid='{$adminid}'")->delete();
            //提交事务
            Db::commit();
            $this->_success('删除成功','admin');
        } catch (\Exception $e) {

            //回滚事务
            Db::rollback();
            $this->_error('删除失败');
        }

    }
    /*
    * 获取编辑管理员数据
    */
    public  function getAdminEditJSON(){
            $adminid = input('get.adminid');
            if(empty($adminid)) $this->_error('id不为空');
            //获取账户信息
            $adminData = adminModel::get($adminid);
            if(!$adminData){
                $this->_error('用户不存在');
            }
            //获取用户所属角色
            $adminData['groupstext']='';
            $groupArr=array();
            $userGroup=$this->adminusergroupModel->getList("adminid='{$adminid}'");
            foreach ($userGroup as $v){
                $groupArr[]=$v['groupid'];
            }

            $groupData=$this->admingroupModel->getList('status = 1');
            foreach ($groupData as $val){
                if(in_array($val['groupid'],$groupArr)){
                    $check="checked=true";
                }else{
                    $check="";
                }
                $adminData['groupstext'].="<input id='groupid".$val['groupid']."' type='checkbox' ".$check." name='groupid[]' value='".$val['groupid']."'>
                                <label style='cursor:pointer' for='groupid".$val['groupid']."'>".$val['groupname']."</label>";
            }
        $this->assign('data',$adminData);
        return $this->fetch('admin/edit');
    }

}