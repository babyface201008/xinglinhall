<?php
namespace app\index\controller;
use app\common\model\Order;
use app\common\model\OrderDetail;
use app\common\model\Product as ProductModel;
use app\common\model\Shopcart;
use think\Db;

class Product extends Base{

    /**
     * 构造方法
     */
    public function _initialize(){

        $this->shopcartModel = new Shopcart();
        $this->productModel = new ProductModel();
        $this->orderModel = new Order();
        $this->orderDetailModel = new OrderDetail();
    }

    //产品页
    public function index(){

        $id = input('get.id');
        if(empty($id)) $this->page_redirect('id不为空！','index/index');

        //获取产品信息
        $productData = ProductModel::get($id);
        if(empty($productData)) $this->page_redirect('产品信息不存在！','index/index');

        $this->assign('productData',$productData);
        return $this->fetch();
    }


    //购物车页面
    public function load_shop_cart(){

        $cartData = $this->shopcartModel->getCart();
        $html = '';
        $total = 0;
        if($cartData){
            foreach($cartData as $key=>$val){

                $productData = ProductModel::get($val['productid']);
                $val['sum'] = round($val['qty']*$productData->price,2);
                $val['price'] = $productData['price'];
                $val['prodname'] = $productData['prodname'];
                $cartData[$key] = $val;

            }
        }
        $this->assign('cartData',$cartData);
        $this->fetch();
    }

    //添加到购物车
    public function addCart(){

        $pro_list = input('post.pro_list');
        if(empty($pro_list)) $this->_data1('数据出错,请联系管理员!');
        $pro_list = substr($pro_list,0,-1);
        $arrPro = explode(',',$pro_list);
        if(empty($arrPro)) $this->_data1('数据格式不正确!');

        $productid = $arrPro[0];//产品ID
        $qty = $arrPro[1];//数量


        $this->shopcartModel->addCart($productid,$qty);
        $this->_data1('添加成功');

    }

    //下订单
    public function place_order(){

        $pro_list = input('post.pro_list');
        $name = input('post.name');
        $sex = input('post.sex');
        $number = input('post.number');
        $district = input('post.district');
        $addr = input('post.addr');
        $msg = input('post.msg');

        if(empty($pro_list)){
            $this->page_alter('请选择购买商品！');
        }
        if(empty($name)){
            $this->page_alter('姓名不能为空!');

        }
        if(empty($number)){
            $this->page_alter('电话不能为空');
        }
        if(empty($addr)){
            $this->page_alter('详细地址不能为空!');
        }

        $pro_list = substr($pro_list,0,-1);
        $arrProd = explode(';',$pro_list);
        $orderno = $this->getSerialNumber();//订单编号

        $totalamount = 0;//订单总额
        $sumqty = 0;//订单总数
        $orderDetail = array();//订单明细
        if($arrProd){
            foreach($arrProd as $val){
                $val = explode(',',$val);
                $productData = ProductModel::get($val[0]);

                $orderDetail[] = array(
                    'orderno'=>$orderno,
                    'productid'=>$val[0],
                    'prodname'=>$productData['prodname'],
                    'standard'=>$productData['standard'],
                    'qty'=>$val[1],
                    'price'=>$productData['price'],
                    'amount'=>round($val[1]*$productData['price'],2)
                );
                //总金额
                $totalamount +=round($val[1]*$productData['price'],2);
                //商品数量
                $sumqty+=$val[1];
            }
        }else{
            $this->page_alter('请选择购买商品！');
        }

        //订单表
        $order = array(
            'orderno'=> $orderno,
            'totalamount'=>$totalamount,
            'sumqty'=>$sumqty,
            'sumprice'=>$totalamount,
            'name'=>$name,
            'sex'=>$sex=='f'?1:2,
            'phone'=>$number,
            'area'=>$district,
            'address'=>$addr,
            'message'=>$msg,
            'status'=>0,
            'ispay'=>0,
            'createtime'=>time()
        );


        //启动事务
        Db::startTrans();

        try{

            //添加订单表
            $this->orderModel->data($order)->save();
            //添加订详情表
            $this->orderDetailModel->saveAll($orderDetail);

            //提交事务
            Db::commit();

            $this->_data1('订单提交成功！');
        } catch (\Exception $e) {

            //回滚事务
            Db::rollback();

            $this->_data1('下单失败！');
        }
    }


    /**
     * 生成订单流水号
     * @param $type orderno(订单号)
     */
    public function getSerialNumber(){

        $number =  date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        return $number;

    }


}
