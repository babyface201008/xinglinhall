<?php
/**
 * User: Chris He
 * Date: 2017/6/15
 * Time: 14:10
 * Desc: 留言管理控制器
 */
namespace app\manage\controller;


use think\Controller;
use think\Validate;
use app\common\model\Message as messageModel;


class Message extends Base{
    /**
     * 构造方法
     */
    public function _initialize(){

        $this->messageModel = new messageModel();
    }


    public function index(){

        return $this->fetch('message/list');
    }

    /**
     *获取列表数据
     **/
    public function getList(){

        //排序
        $List = $this->messageModel->select()->toArray();
        foreach ($List as $v){
            $v['time']=date('Y-m-d h:i:s',$v['uploadtime']);
            $v['status']=$v['message_status'] == 1?'是':'否';
            $data[]=$v;
        }
        $arr['code']=200;
        $arr['data']=$data;

        return $arr;
    }

    //保存查看数据
    public function save(){
        $postData = input('post.');

        $rule []= ['messageid','require','数据不完整，刷新重试!'];
        $validate = new Validate($rule);
        if(!$validate->check($postData)){
            //验证不通过
            $this->_error($validate->getError());
        }


        $Data=array(
            'message_status'=>$postData['status'],
            'createtime' =>time(),
        );

        if($this->messageModel->save($Data,['messageid'=>$postData['messageid']])){
            $this->_success('修改成功！');
        }else{
            $this->_error('修改成功！');
        }

    }


    //查看页面
    public function messageEdit(){
        $id=input("get.id");

        if(empty($id)) $this->_error('数据不完整，刷新重试!');

        $data=messageModel::get($id);

        $data['time']=date('Y-m-d h:i:s',$data['uploadtime']);
        $data['status'] = $data['message_status']=="1" ? "checked" : "";

        $this->assign('data',$data);
        return $this->fetch('message/messageEdit');
    }
}