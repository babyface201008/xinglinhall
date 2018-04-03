<?php
/**
 * User: Chris He
 * Date: 2017/6/15
 * Time: 14:10
 * Desc: 首页控制器
 */
namespace app\manage\controller;
use app\common\model\News;
use app\common\model\Setting;
use think\Controller;

class Index extends Base{

    public $webSetting;
    /**
     * 构造方法
     */
    public function _initialize(){

        //获取网站配置信息
        $settingModel = new Setting();
        //新闻模型
        $this->newsModel = new News();
        $this->webSetting = $settingModel->getListInfo("code='basic'");
    }

    //后台首页
    public function index(){

        return $this->fetch('public/index',array('webSetting'=>$this->webSetting));
    }

    //欢迎页面
    public function main(){

        //获取最新新闻
        $newList = $this->newsModel->where(['isshow'=>1])->order('createtime','desc')->limit(1,6)->select();
        if($newList){
            foreach ($newList as $key=> $val){
                $newList[$key]['isshow']=$val['isshow']==1?'显示':'隐藏';
                $newList[$key]['createtime']=date('Y-m-d H:i:s',$val['createtime']);
            }
        }
        return $this->fetch('public/main',array('webSetting'=>$this->webSetting,'newList'=>$newList));
    }
}