<?php
namespace app\index\controller;
use app\common\model\Aboutus;
use app\common\model\Banner;
use app\common\model\Category;
use app\common\model\Product;
use app\common\model\Setting;

class Index extends Base
{
    public function index()
    {

        //获取banner图
        $arrBanner = Banner::where('display',1)->order('sort','asc')->select()->toArray();

        //关于我们
        $aboutusData = Aboutus::get(1);

        //产品分类
        $catData1 = Category::getByCatname('精美单品');
        $catData2 = Category::getByCatname('优选套装');

        //精美单品
        $productData1 = Product::where('catid',$catData1->catid)->select()->toArray();
        //优选套装
        $productData2 = Product::where('catid',$catData2->catid)->select()->toArray();


        $this->assign('arrBanner',$arrBanner);
        $this->assign('aboutusData',$aboutusData);
        $this->assign('productData1',$productData1);
        $this->assign('productData2',$productData2);
        return $this->fetch();
    }



}
