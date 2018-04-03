<?php
namespace app\index\controller;
use app\common\model\Product as ProductModel;

class Product extends Base
{
    protected $catid;
    public function index($catid=0)
    {
        $this->ProductModel=new ProductModel();
        $catData=$this->CategoryModel->where('isshow','1')->order('sort','asc')->order('createtime','asc')->select()->toArray();
        if($catid==0){
            $catid=$catData[0]['catid'];
        }
       foreach ($catData as $datum){
            if($datum['catname']=='258服务广告' &&  $datum['catid']==$catid){
                return $this->fetch('product/product');
                die;
            }
       }
//        获取相应分类下产品
        $productData=$this->ProductModel->where('isshow','1')->where('catid',$catid)->order('sort','asc')->order('createtime','asc')->select()->toArray();
        $this->assign('catid',$catid);
        $this->assign('catData',$catData);
        $this->assign('productData',$productData);
        return $this->fetch();
    }


}
