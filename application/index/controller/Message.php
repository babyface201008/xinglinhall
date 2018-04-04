<?php
/**
 * User: Chris He
 * Date: 2018/4/3
 * Time: 17:03
 * Desc:
 */
namespace app\index\controller;
use app\common\model\Message as messageModel;
use think\Validate;

class Message extends Base{

    /**
     * 构造方法
     */
    public function _initialize(){

        $this->messageModel = new messageModel();
    }

    public function save(){

        $postData = input('post.');

        $rule[]= ['message_name','require','姓名不能为空!'];
        $rule[]= ['message_phone','require','手机号不能为空'];
        $rule[]= ['message','require','留言内容不能为空'];
        $validate = new Validate($rule);
        if(!$validate->check($postData)){
            //验证不通过
            $this->_error($validate->getError());
        }

        $data=array(
            'message_name'=>$postData['message_name'],
            'message_phone'=>$postData['message_phone'],
            'message'=>$postData['message'],
            'createtime' =>time(),
        );

        if($this->messageModel->save($data)){
            $this->page_redirect('提交成功！','index/index');
        }else{
            $this->page_redirect('提交失败！','index/index');
        }

    }
}