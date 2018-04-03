<?php
/**
 * User: jeson
 * Date: 2017/6/16
 * Time: 10:35
 * Desc: 菜单管理控制器
 */
namespace app\manage\controller;
use think\Controller;
use app\common\model\Setting as SettingModel;
use think\Validate;
use think\Paginator;
use think\Db;
class Setup extends Base{

    /**
     * 构造方法
     */
    public function _initialize(){

        $this->SettingModel = new SettingModel();
    }
    /**
     * 显示合作伙伴列表页
     */
    public function index(){
        $setData=$this->SettingModel->order('code','desc')->select()->toArray();
        $setup=array();
        foreach ($setData as $val){
           switch ($val['code']){
               case 'system';
                   $setup['system'][]=$val;
                   break;
               case 'sms';
                   $setup['sms'][]=$val;
                   break;
               case 'email';
                   $setup['email'][]=$val;
                   break;
//               case 'score';
//                   $setup['score'][]=$val;
//                   break;
               case 'seo';
                   $setup['seo'][]=$val;
                   break;
           }
        }
        $this->assign('setup',$setup);
        return $this->fetch('setup/index');
    }

    /*
     * 保存数据
     */
    public function save(){
        $postData = input('post.');
        $rule=[];
        if(input('post.express_val')){
            $rule = [
                    ['express_val','require','运费不能为空!'],
                    ['order_tel','require','订购热线不能为空!'],
                    ['consult_tel','require','咨询热线不能为空!'],
                    ['follow_order_tel','require','跟单热线不能为空!'],
                    ['colophon','require','版权设置不能为空!'],
                ];
        }elseif (input('post.loginName')){
            $rule = [
                ['loginName','require','短信登陆名不能为空!'],
                ['enterpriseID','require','短信企业ID不能为空!'],
                ['password','require','短信登录密码不能为空!'],
                ['url','require','短信http接口不能为空!'],
                ['subPort','require','短信扩展不能为空!'],
            ];
        }elseif (input('post.keywords')){
            $rule = [
                ['keywords','require','网站关键字不能为空!'],
                ['description','require','网站描述不能为空!'],
                ['title','require','网页标题不能为空!'],
            ];
        }elseif (input('post.SMTP_USER')) {
            $rule = [
                ['SMTP_USER', 'require', 'SMTP服务器账户名不能为空!'],
                ['SMTP_SERVER', 'require', '邮件服务器不能为空!'],
                ['SMTP_PORT', 'require', '邮件服务器端口不能为空!'],
                ['SMTP_USER_EMAIL', 'require', 'SMTP服务器的用户邮箱不能为空!'],
                ['SMTP_PWD', 'require', 'SMTP服务器账户密码不能为空!'],
                ['SMTP_MAIL_TYPE', 'require', '发送邮件类型不能为空!'],
                ['SMTP_TIME_OUT', 'require', '超时时间不能为空!']

            ];
        }
//
        $validate = new Validate($rule);
        if(!$validate->check($postData)){
            //验证不通过
            $this->_error($validate->getError());
        }

            //添加后台用户信息
        if($this->SettingModel->updateSetting($postData)){
            $this->_success('修改成功！');
        }else{
            $this->_error('修改失败！');
        }

    }

    /**
     * 上传官方二维码
     */
    public function uploadimg(){
        $file = request()->file('QR_code');
        if(empty($file)){
            $this->error('请选择上传文件');
        }
        $config=config('upload');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move($config['path'].'/manage');
        if($info){
            // 上传成功返回文件名信息
            $this->_data($info->getSaveName());
        }else{
            // 上传失败获取错误信息
            $this->_error($file->getError());
        }
    }





}