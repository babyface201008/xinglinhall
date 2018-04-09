<?php
/**
 * User: Chris He
 * Date: 2017/6/15
 * Time: 9:57
 * Desc: 后台管理员模型类
 */
namespace app\common\model;

use think\Model;

class Order extends Base{

    // 设置当前模型对应的完整数据表名称
    protected $table = 't_order';


    /**
     * 获取支付方式
     */
    public function  getPayName($payid){

        if($payid==1){
            return '支付宝';
        }elseif($payid==2){
            return '微信';
        }elseif($payid==3){
            return '网银支付';
        }elseif($payid==4){
            return '现金支付';
        }elseif($payid==0){
            return '';
        }
    }

    /**
     * 获取订单状态
     */
    public function getStatusName($status){

        if($status==0){
            return '待付款';
        }elseif($status==1){
            return '待发货';
        }elseif($status==2){
            return '待收货';
        }elseif($status==3){
            return '已完成';
        }elseif($status==4){
            return '已取消';
        }elseif($status==5){
            return '已关闭';
        }
    }

}