<?php
/**
 * User: Chris He
 * Date: 2017/6/15
 * Time: 15:22
 * Desc: 系统管理员角色控制器
 */
namespace app\frameset\controller;
use think\Controller;
use app\common\model\AdminGroup as admingroupModel;
use app\common\model\AdminMenu as adminmenuModel;
use app\common\model\AdminMenurole as adminmenuroleModel;
use think\Validate;
use think\Db;

class AdminGroup extends Base{

    /**
     * 构造方法
     */
    public function _initialize(){

        $this->admingroupModel = new admingroupModel();
        $this->adminmenuModel = new adminmenuModel();
        $this->adminmenuroleModel = new adminmenuroleModel();
    }


    /**
     * 管理员列表页
     */
    public function index(){

        return $this->fetch('admin_group/index');
    }

    /**
     * 管理员列表页
     */
    public function add(){
        $order=' parentid asc,sort asc';
        //获取菜单
        $menuData = $this->adminmenuModel->getList('isshow=1','*',$order);
        //树状结构
        $menuData = tree_array2($menuData,'menuid','parentid');
        $data['menu'] = $menuData;
        $this->assign($data);
        return $this->fetch('admin_group/add');
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
                ['groupname','require|max:25','角色名称必须!|角色名称最多不能超过25个字符!'],
                ['menuids','require','请选择角色权限！'],
            ];
        }

        if($postData['op'] == 'edit'){//编辑
            $rule = [
                ['groupid','require','id错误!'],
            ];
        }
        $validate = new Validate($rule);
        if(!$validate->check($postData)){
            //验证不通过
            $this->_error($validate->getError());
        }
        $menuids = input('post.menuids/a');
        if($postData['op'] == 'add'){//添加

            //账号名是否存在
            $checkAdminName = admingroupModel::getByGroupname($postData['groupname']);
            //判断账号名不要重复
            if($checkAdminName){
                $this->_error('角色名称已存在,请重新输入');
            }

            //启动事务
            Db::startTrans();

            try{
                $data = array(
                    'groupname'=>$postData['groupname'],
                    'desc'=>$postData['desc'],
                    'status'=>input('post.status')?1:0,
                    'createtime'=>time(),
                );
                //添加后台用户信息
                $this->admingroupModel->data($data)->save();
                $groupid= $this->admingroupModel->groupid;
                if ($menuids) {
                    foreach ($menuids as $key => $val) {
                        $uesrgroup['groupid'] = $groupid;
                        $uesrgroup['menuid'] = $val;
                        $arr[] = $uesrgroup;
                    }
                    $this->adminmenuroleModel->insertAll($arr);
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
            $groupData= $this->admingroupModel->where("groupname='{$postData['groupname']}'and groupid !={$postData['groupid']}")->find();
            if($groupData){
                //判断账号名不要重复
                $this->_error('角色名称已存在,请重新输入');
            }

            $data = array(
                'groupname'=>$postData['groupname'],
                'desc'=>$postData['desc'],
                'status'=>input('post.status')?1:0,
            );

            //启动事务
            Db::startTrans();

            try{
                //更新账户信息
                $this->admingroupModel->save($data,['groupid'=>$postData['groupid']]);
                $this->adminmenuroleModel->where("groupid='{$postData['groupid']}'")->delete();

                if ($menuids) {
                    foreach ($menuids as $key => $val) {
                        $uesrgroup['groupid'] = $postData['groupid'];
                        $uesrgroup['menuid'] = $val;
                        $arr[] = $uesrgroup;
                    }
                    $this->adminmenuroleModel->insertAll($arr);
                }
                //提交事务
                Db::commit();
                $this->_success('更新成功','admin_group');
            } catch (\Exception $e) {

                //回滚事务
                Db::rollback();
                $this->_error('更新失败');
            }

        }
    }

    /*
    * 删除信息
    */
    public function delete(){
        $groupid = input('post.ids');
        if(empty($groupid)) $this->_error('id不为空');

        if($this->admingroupModel->where("groupid='{$groupid}'")->delete()) {
            $this->adminmenuroleModel->where("groupid='{$groupid}'")->delete();
            $this->_success('删除成功','admin_group');
        }else{
            $this->_error('删除失败');
        }
    }

    /**
     * 改变账号状态
     */
    public function groupswitchstate(){

        $groupid = input('post.groupid');
        if(empty($groupid)) $this->_error('id不为空');

        //获取账户信息
        $groupData = admingroupModel::get($groupid);
        if(!$groupData){
            $this->_error('用户不存在');
        }

        //改变选中id账号状态
        $islocked = $groupData['status'];
        if($groupData['status']==1){
            $islocked = 0;
            $islockstr='停用';
        }elseif($groupData['status']==0){
            $islocked = 1;
            $islockstr='启用';

        }
        $data = array('status'=>$islocked);
        $where = array('groupid'=>$groupid);
        $result = $this->admingroupModel->save($data,$where);
        if($result) {
            $this->_success($islocked,'admingroup');
        }else{
            $this->_error('操作失败');
        }
    }

    //===============返回JSON格式==================

    /**
     * 获取管理员列表数据
     */
    public function getAdminGroupListJSON(){

        //接受参数
        $page = input('post.page');
        $rows = input('post.rows');
        $orderDirection = input('post.order');
        $orderField = input('post.sort');

        //组装搜索条件
        $map = [];

        //排序
        $order = array('orderField'=>empty($orderField)?'createtime':$orderField,'orderDirection'=>empty($orderDirection)?'desc':$orderDirection);
        //获取列表
        $list = $this->admingroupModel->getPageList($map,"$page,$rows",$order);
        // pr($list);

        //总条数
        $total_rows = $this->admingroupModel->totalCount($map);

        //遍历获取其它字段
        $arrData = array();
        if($list){
            foreach($list as $val){
                $val['createtime']  = date('Y-m-d H:i:s',$val['createtime']);
                $val['status'] = $val['status']==1?'启用':'禁用';
                $arrData[] = $val;
            }
        }
        $aaData['aaData'] = $arrData;
        echo json_encode($aaData);
    }

    /*
    * 获取编辑管理员数据
    */
    public  function getAdminGroupEditJSON(){

        $groupid = input('post.groupid');
        if(empty($groupid)) $this->_error('id不为空');
        //获取账户信息
        $groupData = admingroupModel::get($groupid);
        if(!$groupData){
            $this->_error('用户不存在');
        }
        $order=' parentid asc,sort asc';
        //获取菜单
        $menuData = $this->adminmenuModel->getList('isshow=1','*',$order);
        //树状结构
        $menuData = tree_array2($menuData,'menuid','parentid');
        //获取角色对应菜单
        $menuroleData = $this->adminmenuroleModel->getList("groupid={$groupid}");
        $menurole = array();
        if(!empty($menuroleData)){
            foreach ($menuroleData as $k => $v) {
                $menurole[] = $v['menuid'];
            }
        }
        $data['menu'] = $menuData;
        $data['group'] =$groupData ;
        $data['menurole'] = $menurole;
        $this->assign($data);
        $this->fetch('admin_group/edit');

    }

    /*
    * 获取菜单数据
    */
    public  function getMenuListJSON()
    {
        $groupid=input('post.groupid');
        if(empty($groupid))$this->_error('id不为空');

        //获取信息
        $groupData = admingroupModel::get($groupid);

        if(!$groupData){
            $this->_error('用户不存在');
        }
        $order=' parentid asc,sort asc';
        //获取菜单
        $menuData = $this->adminmenuModel->getList('isshow=1','*',$order);
        //树状结构
        $menuData = tree_array2($menuData,'menuid','parentid');
        //获取角色对应菜单
        $menuroleData = $this->adminmenuroleModel->getList("groupid={$groupid}");
        $menurole = array();
        if(!empty($menuroleData)){
            foreach ($menuroleData as $k => $v) {
                $menurole[] = $v['menuid'];
            }
        }
        $data['menu'] = $menuData;
        $data['group'] =$groupData ;
        $data['menurole'] = $menurole;

        $this->assign($data);
        $this->fetch('admin_group/menu_list');
    }

    /*
    * 上传菜单数据
    */
    public  function saveMenuListData()
    {
//        print_r(input('post.'));exit;

        $groupid = input('post.groupid');
        if (!$groupid) {
            //验证不通过
            $this->_error('id错误');
        }

        $menuids = input('post.menuids/a');


        //启动事务
        Db::startTrans();
        try {
            //删除初始数据
            $this->adminmenuroleModel->where("groupid='{$groupid}'")->delete();

            if ($menuids) {
                foreach ($menuids as $key => $val) {
                    $data['groupid'] = $groupid;
                    $data['menuid'] = $val;
                    $arr[] = $data;
                }
                $this->adminmenuroleModel->insertAll($arr);
            }

            //提交事务
            Db::commit();

            $this->_success('设置成功', 'admin_group');

        } catch (\Exception $e) {
            //回滚事务
            Db::rollback();
            $this->_error('设置失败');
        }
    }
}