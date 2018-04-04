<?php
/**
 * User: Chris He
 * Date: 2018/4/4
 * Time: 11:30
 * Desc: 购物车类
 */
namespace app\common\model;

use think\Model;

class Shopcart extends Base{



    /**
     * 获取购物车商品
     */
    public function getCart(){

        $cartData = session('cart');//购物车

        return $cartData;
    }

    /**
     * 获取购物车去结算商品
     */
    public function getCheckOutCart(){

        $noLoginCartData = $this->getCart();//购物车
        if($noLoginCartData){
            foreach($noLoginCartData as $val){
                if($val['ischeck']==1){
                    $cartData[] = $val;
                }
            }
        }
        return $cartData;
    }

    /**
     * 获取购物车商品数量
     */
    public function getCartNum(){
        $cartNum = 0;
        //购物车商品
        $cartData = $this->getCart();
        if($cartData){
            foreach ($cartData as $val){
                $productData = $this->product_model->getDetail($val['productid']);
                if ($productData) {
                    $cartNum += $val['qty'];
                }
            }
        }
        return $cartNum;
    }

    /**
     * 勾选结算商品
     */
    public function check($arrProductId,$flag){

        $cartData = $this->getCart();

        if($arrProductId){
            foreach($arrProductId as $val){

                $cartData[$val]['ischeck'] = $flag;
            }
            session('cart',$cartData);
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取总计和已优惠和结算数量
     */
    public function getTotal(){

        //购物车商品
        $cartData = $this->getCart();

        //遍历获取产品信息
        $totalPrice = 0;//总计
        $origPrice = 0;//原价
        $countCheck = 0;//去结算
        $count=0;//商品总数
        if($cartData){
            foreach($cartData as $val){

                $productData = $this->product_model->getDetail($val['productid']);
                if($productData){

                    $val['price'] = $productData['price'];
                    $val['showPrice'] = $this->product_model->getPrice($val['productid']);

                    if( $productData['isshow'] == '1' && $productData['stock'] > 0 ){
                        $count +=  $val['qty'];
                    }
                    if($val['ischeck']==1 && $productData['isshow'] == 1 && $productData['stock'] > 0){//判断是否选中，上下架，库存
                        $countCheck += $val['qty'];
                        $totalPrice += $val['qty']*$val['showPrice'];
                        $origPrice += $val['qty']*$val['price'];
                    }
                }
            }
        }
        //节省多少钱
        $specialPrice = $origPrice-$totalPrice;
        return array('totalPrice'=>$totalPrice,'specialPrice'=>$specialPrice,'countCheck'=>$countCheck,'count'=>$count);
    }

    /**
     * 添加购物车
     */
    public function addCart($productid,$qty){


        $cart = $this->getCart();//购物车
        if(!empty($cart)){//购物车存在
            //查看产品是否有存在购物车中
            if(array_key_exists($productid,$cart)){//存在,数量增加

                $cart[$productid]['qty'] += $qty;
            }else{//不存,新增记录
                $cart[$productid] = array(
                    'productid'=>$productid,
                    'qty'=>$qty,
                    'createtime'=>time(),
                    'ischeck'=>1
                );
            }
        }else{//不存在购物车
            $cart[$productid] = array(
                'productid'=>$productid,
                'qty'=>$qty,
                'createtime'=>time(),
                'ischeck'=>1
            );

        }
        session('cart',$cart);
        return true;
    }

    /**
     * 修改购物车
     */
    public function updateCart($productid,$qty){

        $cart = $this->getCart();//购物车
        if(!empty($cart)){//购物车存在
            //修改购物车中
            $cart[$productid]['qty'] = $qty;
        }
        session('cart',$cart);
        return true;
    }

    /**
     * 删除购物车
     */
    public function deleteCart($arrProductId){

        $cart = $this->getCart();//购物车
        if(!empty($cart)){//购物车存在
            foreach($arrProductId as $val){
                //删除购物车中
                unset($cart[$val]);
            }

        }
        session('cart',$cart);
        return true;

    }



    /**
     * 清空购物车
     */
    public function clearCart(){

        session('cart',null);
        return true;
    }
}