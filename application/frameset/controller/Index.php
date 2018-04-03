<?php
/**
 * User: Chris He
 * Date: 2017/6/15
 * Time: 14:10
 * Desc: 首页控制器
 */
namespace app\frameset\controller;
use think\Controller;

class Index extends Base{

    //后台首页
    public function index(){
        return $this->fetch('public/index');
    }
}