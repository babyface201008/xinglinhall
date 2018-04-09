<?php
/**
 * User: Chris He
 * Date: 2018/4/8
 * Time: 16:47
 * Desc:
 */
namespace app\manage\controller;
use app\common\model\OrderDetail;
use think\Controller;
use app\common\model\Order as OrderModel;
use think\Validate;
use think\Paginator;
use think\Db;

class Order extends Base{

    /**
     * 构造方法
     */
    public function _initialize(){
        $this->orderModel = new OrderModel();
        $this->orderDetailModel = new OrderDetail();
    }

    /**
     * 订单列表
     */
    public function index(){

        return $this->fetch('order/index');
    }

    /**
     * 查看订单详情
     */
    public function orderDetail(){

        $orderid = input('get.orderid');
        if(empty($orderid)) $this->_error('请选择查看订单!');

        //获取订单信息
        $orderData = $this->orderModel->get($orderid);
        if(empty($orderData)) $this->_error('订单不存在!');

        //获取订单明细
        $orderDetail = $this->orderDetailModel->getOrderProduct($orderData['orderno']);

        $orderData['sex'] =  $orderData['sex']==1?'男':'女';
        $orderData['createtime'] =!empty($orderData['createtime'])?date('Y-m-d H:i:s',$orderData['createtime']):'';
        $orderData['paytime'] = !empty($orderData['paytime'])?date('Y-m-d H:i:s',$orderData['paytime']):'';
        $orderData['payname'] = $this->orderModel->getPayName($orderData['payid']);
        $orderData['statusname'] = $this->orderModel->getStatusName($orderData['status']);
        $this->assign('orderData',$orderData);
        $this->assign('orderDetail',$orderDetail);
        return $this->fetch('order/orderDetail');
    }

    //===============返回JSON格式==================
    /*
    * 获取列表 json
    */
    public function getOrderList(){
        //搜索栏
        $keyword = input('post.keyword');//关键字
        $curr = input('post.curr');//关键字
        //组装搜索条件
        $condition = [];
        if($keyword!=''){
            $condition['a.prodname'] = ['like','%'.$keyword.'%'];
        }
        $options=[
            'page'=>$curr,
        ];
        $config=config('paginate');

        $config['list_rows'] = config('paginate.list_rows');
        //获取列表
        $list = $this->orderModel->where($condition)->order('createtime','desc')->paginate($config['list_rows'],false,$options)->toArray();

        if(isset($list['data'])){
            foreach ($list['data'] as $key=> $val){
                $list['data'][$key]['createtime'] = date('Y-m-d H:i:s',$val['createtime']);
                $list['data'][$key]['paytime'] = !empty($val['paytime'])?date('Y-m-d H:i:s',$val['paytime']):'';
                $list['data'][$key]['payname'] = $this->orderModel->getPayName($val['payid']);
                $list['data'][$key]['statusname'] = $this->orderModel->getStatusName($val['status']);
            }
        }
        $this->_data($list);
    }
}