<?php
/**
 * User: Chris He
 * Date: 2017/6/16
 * Time: 10:10
 * Desc: 基类控制器
 */
namespace app\frameset\controller;
use think\Controller;

class Base extends Controller{


    /**
     * 构造方法
     * @param Request $request Request对象
     * @access public
     */
    public function __construct(){
        parent::__construct();
        //判断是否登录
        if(!session('?admin')){
            $this->page_redirect('身份认证已过期请，请先登录', 'Login/index');
        }
    }

    /**
     * 成功提示信息
     * @param String $message 提示信息
     * @param String $url 跳转的页面
     * @param String $did 指定要刷新的节点ID，注意查看前面页面上的选项卡ID
     * @return void
     */
    public function _success($message,$did='',$callbackType='datagrid'){
        $data['statuscode'] = "200";
        $data['message'] = $message;
        $data['did'] = $did;
        $data['callbackType'] = $callbackType;

        die(json_encode($data));
    }

    /**
     * 错误提示信息
     * @param String $message 提示信息
     * @param String $url 跳转的页面
     * @return void
     */
    public function _error($message,$url=''){
        $data['statuscode'] = "300";
        $data['message'] = $message;
        die(json_encode($data,JSON_HEX_TAG));
    }

    /**
     * 数据信息
     * @param String  $data 数据
     * @return void
     */
    public function _data($data){
        $data['code'] = 200;
        die(json_encode($data));
    }

    /**
     * @param $url 要跳转的地址
     */
    function page_redirect($msg,$url){
        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
        echo '<script>alert("'.$msg.'");window.location.href="'.url($url).'";</script>';
        exit;
    }
}