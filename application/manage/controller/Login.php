<?php
/**
 * User: Chris He
 * Date: 2017/6/14
 * Time: 15:27
 * Desc: 登录控制器
 */
namespace app\manage\controller;
use app\common\model\Admin;
use app\common\model\Setting;
use think\Controller;
use think\Validate;
use think\Cookie;

class Login extends Controller{

    //登录页
    public function index(){

        //获取网站配置信息
        $settingModel = new Setting();
        $webSetting = $settingModel->getListInfo("code='basic'");
        return $this->fetch('public/login',array('webSetting'=>$webSetting));
    }

    //处理登陆
    public function handleLogin(){

        //接受数据
        $postData = input('post.');
        //验证规则
        $rule = [
            ['adminname','require','用户名必须!'],
            ['password','require','密码必须!'],
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
                die(json_encode(['code'=>2,'msg'=>'密码输入错误!']));
            }
            //用户状态
            if($admin['islocked']==1){
                die(json_encode(['code'=>2,'msg'=>'该用户被锁定!']));
            }

            //更新最后登录时间和ip和错误次数为0
            $admin->last_login_time = time();
            $admin->last_login_ip   = get_client_ip();
            $admin->save();

            //成功,保存Session信息
            session('admin', $admin);

            if(input('post.status')=="on"){
//            过期时间为七天
                cookie('loginid',base64_encode($postData['adminname']),time()+604800);
                cookie('loginpwd',base64_encode($postData['password']),time()+604800);
                cookie('loginstatus',1,time()+604800);
            }else{
////            即时过期
                Cookie::delete('loginid');
                Cookie::delete('loginpwd');
                Cookie::delete('loginstatus');
            }
            //跳转
            $this->success('登录成功', 'Index/index');
        }else{
            die(json_encode(['code'=>2,'msg'=>'用户不存在!']));
        }

    }

    /**
     * 退出
   * */
    public function logout(){
        session('admin',null);
        $this->success('退出成功', 'Login/index');
    }
}
