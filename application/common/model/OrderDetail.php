<?php
/**
 * User: Chris He
 * Date: 2017/6/15
 * Time: 9:57
 * Desc: 后台管理员模型类
 */
namespace app\common\model;

use think\Model;

class OrderDetail extends Base{

    // 设置当前模型对应的完整数据表名称
    protected $table = 't_order_detail';


    /**
     * 根据订单号获取订单明细
     */
    public function getOrderProduct($orderno){

        if(empty($orderno)) return false;

        $list = $this->field('a.*,b.sourceimg')->alias('a')->join('t_product b ','b.productid= a.productid','Left')->where('a.orderno',$orderno)->select()->toArray();
        return $list;
    }
}