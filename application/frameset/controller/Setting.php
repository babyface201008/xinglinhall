<?php
/**
 * User: jeson
 * Date: 2017/6/16
 * Time: 15:35
 * Desc: 系统设置控制器
 */
namespace app\frameset\controller;
use think\Controller;
use think\Validate;
use app\common\model\Setting as settingModel;
class Setting extends Base{

    /**
     * 构造方法
     */
    public function _initialize(){
        $this->settingModel = new settingModel();
    }

    /**
     * 系统设置
     */
    public function index(){
        $data = $this->settingModel->getListInfo("1=1");
        return $this->fetch('setting/system',$data);
    }

    /**
     * 保存系统设置
     */
    public function saveSystem(){
        $is_express = input('post.is_express');
        $express_val = input('post.express_val');
        $order_tel = input('post.order_tel');
        $consult_tel = input('post.consult_tel');
        $colophon = input('post.colophon');
        if($is_express=='on' && empty($express_val)){
            $this->_error('请填写运费!');
        }
        if(empty($order_tel)){
            $this->_error('请输入订购热线!');
        }
        if(empty($consult_tel)){
            $this->_error('请输入咨询热线!');
        }
        if(empty($colophon)){
            $this->_error('请输入版权信息!');
        }
        $data = array(
            'is_express'=>input('post.is_express')=='on'?'1':'0',
            'order_tel'=>input('post.order_tel'),
            'consult_tel'=>input('post.consult_tel'),
            'colophon'=>input('post.colophon'),
            'orderPrompt' => input('post.orderPrompt')=='on'?'1':'0',
        );
        if(input('post.express_val')){
            $data['express_val']= input('post.express_val');
        }
        if($this->settingModel->updateSetting($data)) {
            $this->_success('更新成功');
        }else{
            $this->_error('更新失败');
        }
    }
    /**
     * 保存邮件设置
     */
    public function saveEmail(){
        $data = array(
            'SMTP_SERVER'=>input('post.SMTP_SERVER'),
            'SMTP_PORT'=>input('post.SMTP_PORT'),
            'SMTP_USER_EMAIL'=>input('post.SMTP_USER_EMAIL'),
            'SMTP_USER'=>input('post.SMTP_USER'),
            'SMTP_PWD'=>input('post.SMTP_PWD'),
            'SMTP_MAIL_TYPE'=>input('post.SMTP_MAIL_TYPE'),
            'SMTP_TIME_OUT'=>input('post.SMTP_TIME_OUT'),
            'SMTP_AUTH' => input('post.SMTP_AUTH')=='on'?1:0
        );
        foreach ($data as $val){
            if($val===""){
                $this->_error('邮件配置不能有空！');
            }
        }

        if($this->settingModel->updateSetting($data)) {
            $this->_success('更新成功');
        }else{
            $this->_error('更新失败');
        }
    }
    /**
     * 保存短信设置
     */
    public function saveSMS(){
        $data = array(
            'enterpriseID'=>input('post.enterpriseID'),
            'loginName'=>input('post.loginName'),
            'password'=>input('post.password'),
            'url'=>input('post.url'),
            'subPort'=>input('post.subPort')
        );
        foreach ($data as $val){
            if(empty($val)){
                $this->_error('短信配置不能有空！');
            }
        }
        if($this->settingModel->updateSetting($data)) {
            $this->_success('更新成功');
        }else{
            $this->_error('更新失败');
        }
    }
    /**
     * 保存seo设置
     */
    public function saveSeo(){
        $data = array(
            'title' => input('post.title'),
            'keywords'=>input('post.keywords'),
            'description'=>input('post.description'),
            'product_keywords'=>input('post.product_keywords'),
            'product_description'=>input('post.product_description'),
            'product_title' => input('post.product_title'),
            'news_keywords'=>input('post.news_keywords'),
            'news_description'=>input('post.news_description'),
            'news_title' => input('post.news_title'),
            'medication_keywords'=>input('post.medication_keywords'),
            'medication_description'=>input('post.medication_description'),
            'medication_title' => input('post.medication_title'),
            'question_keywords'=>input('post.question_keywords'),
            'question_description'=>input('post.question_description'),
            'question_title' => input('post.question_title'),
        );
        foreach ($data as $val){
            if(empty($val)){
                $this->_error('seo不能有空！');
            }
        }
        if($this->settingModel->updateSetting($data)) {
            $this->_success('更新成功');
        }else{
            $this->_error('更新失败');
        }
    }
//    保存积分设置
    public function saveScore(){
        $data = array(
            'register' => input('post.register'),
            'phone'=>input('post.phone'),
            'email'=>input('post.email'),
            'face'=>input('post.face'),
            'nickname'=>input('post.nickname'),
            'qq' => input('post.qq'),
            'sex'=>input('post.sex'),
            'real_name'=>input('post.real_name'),
            'common_medicine' => input('post.common_medicine'),
            'birthday'=>input('post.birthday'),
            'height'=>input('post.height'),
            'weight' => input('post.weight'),
            'maritalStatus'=>input('post.maritalStatus'),
            'monthcome'=>input('post.monthcome'),
            'occupation'=>input('post.occupation'),
            'consume' => input('post.consume'),
        );
        foreach ($data as $val){
            if(empty($val)){
                $this->_error('积分设置不能有空！');
            }
        }
        if($this->settingModel->updateSetting($data)) {
            $this->_success('更新成功');
        }else{
            $this->_error('更新失败');
        }
    }

}