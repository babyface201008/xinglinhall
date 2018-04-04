<?php
namespace app\index\controller;
use app\common\model\Product as ProductModel;
use app\common\model\Shopcart;

class Product extends Base{


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
    public function shopCart(){
        return $this->fetch();
    }

    //添加到购物车
    public function addCart(){

        $pro_list = input('post.pro_list');
        if(empty($pro_list)) $this->_data1('数据出错,请联系管理员!');

        $arrPro = explode(',',$pro_list);
        if(empty($arrPro)) $this->_data1('数据格式不正确!');

        $productid = $arrPro[0];//产品ID
        $qty = $arrPro[1];//数量

        $shopcartModel = new Shopcart();
        $shopcartModel->addCart($productid,$qty);
        $this->_data1('添加成功');

    }


}
