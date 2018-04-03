<?php
/**
 * User: Chris He
 * Date: 2017/6/14
 * Time: 15:27
 * Desc: 登录控制器
 */
namespace app\admin\controller;
use app\common\model\Admin;
use app\common\model\AdminUsergroup;
use app\common\model\AdminMenu;
use app\common\model\AdminMenurole;
use think\Controller;
use think\Validate;

class Login extends Controller{

    //登录页
    public function index(){

        return $this->fetch('public/login');
    }

    //处理登陆
    public function handleLogin(){

        //接受数据
        $postData = input('post.');
        //验证规则
        $rule = [
            ['adminname','require','用户名必须!'],
            ['password','require','密码必须!'],
            ['code','require|captcha','验证码必须!|验证码不正确!'],
        ];
        $validate = new Validate($rule);
        if(!$validate->check($postData)){
            //验证不通过
            show_msg($validate->getError());
        }

        //查询用户是否存在
        $admin = Admin::get(['adminname' =>  $postData['adminname']]);
        if($admin){
            //验证密码是否正确
            if($admin->password!=encryptPassword($postData['password'],$admin->salt)){
                show_msg('密码输入错误!');
            }
            //用户状态
            if($admin['islocked']==1){
                show_msg('该用户被锁定!');
            }

            //更新最后登录时间和ip和错误次数为0
            $admin->last_login_time = time();
            $admin->last_login_ip   = get_client_ip();
            $admin->save();

            //成功,保存Session信息
            unset($admin->password);

            session('admin', $admin);


            $this->getMenuInfo();//登录用户的菜单写入session

            //跳转
            $this->success('登录成功', 'Index/index');
        }else{
            show_msg('用户不存在!');
        }

    }

    //登录用户的菜单写入session
    public function getMenuInfo(){
        //登录用户ID
        $adminid = input('session.admin.adminid');
        if(!$adminid){
            redirect('login/login');
        }

        $adminGroupName='';
        $strUsergroup='';
        if($adminid == 1){
            $adminGroupName="超级管理员";
        }else{
            $adminUsergroup = AdminUsergroup::getGroupByAdminid($adminid);
            if($adminUsergroup){
                foreach ($adminUsergroup as $val){
                    $adminGroupName.=$val['groupname'].',';
                    $strUsergroup .= $val['groupid'].',';
                }
                $adminGroupName = substr($adminGroupName,0,-1);
                $strUsergroup = substr($strUsergroup,0,-1);
            }
        }

        //成功,保存Session信息
        session('groupname',$adminGroupName);


        $menus = array();//存菜单数组
        $menuurl = array();//存菜单URL

        //超级管理员admin,拥有所有权限
        if($adminid==1){
            //获取菜单权限
            $arrMenuData = AdminMenu::getAdminMenuList();
        }else{
            //获取角色对应的菜单权限
            $arrMenuData = AdminMenurole::getMenuGroupList($strUsergroup);

        }
//print_r($arrMenuData);exit;

        //遍历获取菜单URL数组
        if($arrMenuData){
            foreach($arrMenuData as $val){
                $menuurl[] = $val['url'];
            }
        }

        //整理成树状格式
        $arrMenuData = treeToArray($arrMenuData,'menuid','parentid');
        //菜单按sort排序
        $arrMenuData = array2sort($arrMenuData,'sort');

        if($arrMenuData){
            foreach($arrMenuData as $val){
                if(isset($val['details'])){
                    $val['details'] = array2sort($val['details'],'sort');
                }
                $menus[] = $val;
            }
        }


        //登录用户的菜单信息存入session
        session('menus',serialize($menus));
        session('menuurl',serialize($menuurl));
        //重写数组格式
        $menuListData=array();

        $strMenuData=input('session.menus');

        if($strMenuData)$arrMenuData = unserialize($strMenuData);//反序列化
        foreach ($arrMenuData as $key=>$val){
            $menuListData[$key]['text']=$val['menuname'];
            $menuListData[$key]['state']='open';
            $menuListData[$key]['iconCls']=$val['icon'];
            $menuListData[$key]['children']=array();
            if( isset($val['details']) ){
                foreach ( $val['details'] as $k=>$v){
                    $menuListData[$key]['children'][$k]['text']=$v['menuname'];
                    $menuListData[$key]['children'][$k]['url']=url($v['url']);
                    $menuListData[$key]['children'][$k]['iconCls']=$v['icon'];
                }
            }

        }

        session('menuList',json_encode($menuListData));

    }

    /**
     * 退出
   * */
    public function logout(){
        session('admin',null);
        session('menuList',null);
        session('menus',null);
        session('menuurl',null);
        session('groupname',null);
        $this->success('退出成功', 'Login/index');
    }
}
