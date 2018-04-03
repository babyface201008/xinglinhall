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
class Basic extends Base{

    /**
     * 构造方法
     */
    public function _initialize(){

        $this->SettingModel = new SettingModel();
    }
    /**
     * 基础信息
     */
    public function index(){
        $setData=$this->SettingModel->where('code', 'basic')->select()->toArray();
        $codesrc='';
        foreach ($setData as $val){
            if($val['variable']=='QR_code'){
                $codesrc=$val['value'];
            }
            if($val['variable']=='logo'){
                $logosrc=$val['value'];
            }
        }
        $this->assign('logosrc',$logosrc);
        $this->assign('codesrc',$codesrc);
        $this->assign('setData',$setData);
        return $this->fetch('basic/index');
    }

    /*
     * 保存数据
     */
    public function save(){
        $postData = input('post.');

        $rule = [
            ['webname','require','公司名称不能为空!'],
            ['copyright','require','版权信息不能为空!'],
            ['service_phone','require','客服电话不能为空!'],
            ['company_address','require','公司地址不能为空!'],
            ['company_url','require','公司网址不能为空!'],
            ['dawk','require','邮政编码不能为空!'],
            ['company_call','require','公司电话不能为空!'],
            ['company_email','require','公司邮箱不能为空!'],
            ['logo','require','官网logo不能为空!'],
        ];

        $validate = new Validate($rule);
        if(!$validate->check($postData)){
            //验证不通过
            $this->_error($validate->getError());
        }
//        编辑数据
        $data = array(
            'webname'=>$postData['webname'],
            'copyright'=>$postData['copyright'],
            'service_phone'=>$postData['service_phone'],
            'company_address'=>$postData['company_address'],
            'company_url'=>$postData['company_url'],
            'dawk'=>$postData['dawk'],
            'company_call'=>$postData['company_call'],
            'company_email'=>$postData['company_email'],
            'QR_code'=>$postData['QR_code'],
            'logo'=>$postData['logo'],
        );

            //添加后台用户信息
        if($this->SettingModel->updateSetting($data)){
            $this->_success('修改成功！');
        }else{
            $this->_error('修改失败！');
        }

    }

    /**
     * 上传官方二维码
     */
    public function uploadimg(){
        $uploadConfig=config('upload');
        $file = request()->file('logo');
        $images = request()->file('QR_code');
        // 移动到框架应用根目录/public/upload/ 目录下
        if(!empty($file)){
            $info = $file->move($uploadConfig['path'].'manage/');
        }else if(!empty($images)){
            $imginfo = $images->move($uploadConfig['path'].'manage/');
        }else{
            $this->error('请选择上传文件');
        }
        // 上传成功返回文件名信息
        if(isset($info)){
            $this->_success($info->getSaveName(),'logo');
        }else if(isset($imginfo)) {
            $this->_success($imginfo->getSaveName(),'QR_code');
        }else{
            // 上传失败获取错误信息
            $this->_error($file->getError());
        }
    }





}