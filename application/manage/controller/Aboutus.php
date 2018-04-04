<?php
/**
 * User: Chris He
 * Date: 2017/6/15
 * Time: 14:10
 * Desc: 关于我们控制器
 */
namespace app\manage\controller;


use think\Controller;
use think\Validate;
use app\common\model\Aboutus as aboutusModel;


class Aboutus extends Base{
    /**
     * 构造方法
     */
    public function _initialize(){

        $this->aboutusModel = new aboutusModel();
    }

    //关于我们
    public function index(){
        $data=aboutusModel::get('1');
        if(empty($data))$this->_error('数据不完整，刷新重试!');
        $this->assign('data',$data);
        return $this->fetch('aboutus/index',['data'=>$data]);
    }


    //保存新闻数据
    public function save(){
        $postData = input('post.');
//        pr($postData);die;
        $rule = [
            ['title','require','标题!'],
            ['content1','require','About Us'],
            ['content2','require','Our Story'],
        ];
        $validate = new Validate($rule);
        if(!$validate->check($postData)){
            //验证不通过
            $this->_error($validate->getError());
        }

        $Data=array(
            'title'=>$postData['title'],
            'content1' =>$postData['content1'],
            'content2' =>$postData['content2'],
        );

       if($this->aboutusModel->update($Data,['id'=>$postData['id']])){
           $this->_success('保存成功！');
       }else{
           $this->_error('保存失败！');
       }

    }

}