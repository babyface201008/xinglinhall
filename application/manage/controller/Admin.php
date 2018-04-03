<?php
/**
 * User: Chris He
 * Date: 2017/6/15
 * Time: 15:22
 * Desc: 系统管理员控制器
 */
namespace app\manage\controller;
use think\Controller;
use app\common\model\Admin as adminModel;
use think\Validate;
use think\Db;

class Admin extends Base{

    /**
     * 构造方法
     */
    public function _initialize(){

        $this->adminModel = new adminModel();
    }


    /**
     * 管理员列表页
     */
    public function index(){
        return $this->fetch('admin/index');
    }

    /**
     * 添加页
     */
    public function add(){
        return $this->fetch('admin/add');
    }

    /**
     * 编辑
     */
    public function edit(){
        $adminid = input('get.adminid');
        if(empty($adminid)) $this->_error('请选择编辑行!');
        $adminData = $this->adminModel->get($adminid);
        $this->assign('adminData',$adminData);
        return $this->fetch('admin/edit');
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

            //启动事务
            Db::startTrans();

            try{

                $salt = randstr();//随机数
                $data = array(
                    'adminname'=>$postData['adminname'],
                    'password'=>encryptPassword($postData['password'],$salt),
                    'realname'=>$postData['realname'],
                    'salt'=>$salt,
                    'email'=>$postData['email'],
                    'phone'=>$postData['phone'],
                    'islocked'=>$postData['islocked'],
                    'createtime'=>time(),
                );
                //添加后台用户信息
                $this->adminModel->data($data)->save();
                $adminDataId=$this->adminModel->getLastInsID();

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
                'adminname'=>$postData['adminname'],
                'password'=>$postData['password'],
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
    public function changePwd(){

        return $this->fetch('admin/changePwd',array('data'=>session('admin')));
    }
    /**
     * 处理当前登录用户密码修改
     */
    public function handleEditPasswd(){

        //接受数据
        $oldpassword = input('post.oldpassword');
        $password = input('post.password');
        $ackpassword = input('post.ackpassword');

        if(empty($password)||empty($ackpassword)) $this->_error('密码和确认密码不能为空!');
        if(trim($password)!=trim($ackpassword)){
             $this->_error('两次输入密码不一致，请重新输入!');
        }

        $admin = session('admin');
        $adminData = adminModel::get($admin['adminid']);

        //判断旧密码是否输入正确
        $oldpassword = encryptPassword($oldpassword,$admin['salt']);
        if($oldpassword!=$adminData->password){
            $this->_error('旧密码输入错误!');
        }
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
        $page = input('post.curr');

        //搜索栏
        $islocked = input('post.islocked');//账号状态
        $keyword = input('post.keyword');//关键字
        $datetype = input('post.datetype');//时间类型
        $dateStart = input('post.dateStart');//开始时间
        $timeStart = strtotime($dateStart);
        $dateEnd = input('post.dateEnd');//结束时间
        $timeEnd = strtotime($dateEnd)+24*3600;

        //组装搜索条件
        $map['adminid'] = ['<>','1'];
        if($keyword!=''){
            $map['adminname|email|realname'] = ['like','%'.$keyword.'%'];
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
        //分页
        $options=[
            'page'=>$page,
        ];
        //每页显示条数
        $per_page = config('paginate.list_rows');
        //获取列表
        $list = $this->adminModel->where($map)->order('createtime','desc')->paginate($per_page,false,$options)->toArray();

        //遍历获取其它字段
        if($list['data']){
            foreach($list['data'] as $key=>$val){

                $val['last_login_time']  = !empty($val['last_login_time'])?date('Y-m-d H:i:s',$val['last_login_time']):'';
                $val['createtime']  = date('Y-m-d H:i:s',$val['createtime']);
                $val['islocked'] = $val['islocked']==1?'禁用':'启用';
                $list['data'][$key] = $val;
            }
        }
        $this->_data($list);
    }

    /*
      * 删除信息
      */
    public function del(){
        $adminid = input('post.adminid');
        if(empty($adminid)) $this->_error('请选择编辑行!');

        if($this->adminModel->where("adminid in ({$adminid})")->delete()) {
            $this->_success('删除成功');
        }else{
            $this->_error('删除失败');
        }
    }



}