<?php
/**
 * User: jeson
 * Date: 2017/6/16
 * Time: 10:35
 * Desc: 菜单管理控制器
 */
namespace app\manage\controller;
use think\Controller;
use app\common\model\Product as ProductModel;
use think\Validate;
use think\File;
use think\Image;
use think\Paginator;
use think\Db;
class Product extends Base{

    /**
     * 构造方法
     */
    public function _initialize(){

        $this->ProductModel = new ProductModel();
    }
    /**
     * 显示合作伙伴列表页
     */
    public function index(){
        return $this->fetch('product/index');
    }
//    新增合作伙伴
    public function add(){
        return $this->fetch('product/add');
    }

    /**
     * 编辑菜单
     */
    public function edit(){
        $productid = input('get.productid');
        if(empty($productid)) $this->_error('请选择编辑行!');
        $productData = $this->ProductModel->get($productid);
        $productData['att']='';
        if(!empty($productData['image'])){
            $productData['att']=explode(',',$productData['image']);
        }
        $this->assign('productData',$productData);
        return $this->fetch('product/edit');
    }
    /*
    * 删除信息
    */
    public function del(){
        $productid = input('post.productid');
        if(empty($productid)) $this->_error('请选择编辑行!');
        $data=$this->ProductModel->where("productid in({$productid})")->select()->toArray();
        if($this->ProductModel->where("productid in ({$productid})")->delete())
        {
            $uploadConfig=config('upload');
            foreach ($data as $item){
//               删除主图
                if(is_file($uploadConfig['path'].'manage/product/'.$item['productid'].'/'.$item['sourceimg'])){
                    unlink($uploadConfig['path'].'manage/product/'.$item['productid'].'/'.$item['sourceimg']);
                    foreach ($uploadConfig['thumb'] as $value){
                        unlink($uploadConfig['path'].'manage/product/'.$item['productid'].'/'.$value.'/'.$item['sourceimg']);
                    }
                }
//                删除附图
                $attimg=explode(',',$item['image']);
                if($attimg){
                    foreach ($attimg as $v){
                        if(is_file($uploadConfig['path'].'manage/product/'.$item['productid'].'/'.$v)){
                            unlink($uploadConfig['path'].'manage/product/'.$item['productid'].'/'.$v);
                            foreach ($uploadConfig['thumb'] as $v1){
                                unlink($uploadConfig['path'].'manage/product/'.$item['productid'].'/'.$v1.'/'.$v);
                            }
                        }
                    }
                }
            }
            $this->_success('删除成功');
        }else{
            $this->_error('删除失败');
        }
    }

    /*
     * 保存数据
     */
    public function save(){
        $postData = input('post.');
        $rule = [
            ['prodname','require','产品名称不能为空!'],
           /* ['sourceimg','require','产品主图不能为空!'],*/
            ['catid','require','产品分类不能为空!'],
        ];
        if($postData['op'] == 'edit'){//编辑
            $rule []= ['productid','require','请选择编辑数据!'];
        }
        $validate = new Validate($rule);
        if(!$validate->check($postData)){
            //验证不通过
            $this->_error($validate->getError());
        }
//        编辑数据
        $data = array(
            'prodname'=>$postData['prodname'],
            'catid'=>$postData['catid'],
            'sourceimg'=>$postData['sourceimg'],
            'image'=>trim($postData['images'],','),
            'sort'=>$postData['sort'],
            'intro'=>$postData['intro'],
            'isshow'=>input('post.isshow')=='on'?'1':0,
            'desc'=>$postData['product_desc']
        );
        if($postData['op'] == 'add'){//添加
            $checkName = ProductModel::getByProdname($postData['prodname']);
            //判断菜单名不要重复
            if($checkName){
                $this->_error('产品名已存在,请重新输入');
            }
            $data['createtime']=time();
                //添加后台用户信息
            if($this->ProductModel->save($data)){
                $productid= $this->ProductModel->productid;
//                把图片从临时移至相应文件夹
                $uploadConfig=config('upload');
//                主图生成缩略图
                if(is_file($uploadConfig['path'].'product/'.$postData['sourceimg'])){
                    $image = \think\Image::open($uploadConfig['path'].'product/'.$postData['sourceimg']);
                    checkPath($uploadConfig['path'].'manage/product/'.$productid.'/');
                    $image->save($uploadConfig['path'].'manage/product/'.$productid.'/'.$postData['sourceimg']);
                    foreach ($uploadConfig['thumb'] as $item){
                        checkPath($uploadConfig['path'].'manage/product/'.$productid.'/'.$item.'/');
                        $image->thumb($item, $item)->save($uploadConfig['path'].'manage/product/'.$productid.'/'.$item.'/'.$postData['sourceimg']);
                    }
                    unlink($uploadConfig['path'].'product/'.$postData['sourceimg']);

                }
//               附图生成缩略图
                if($postData['images']){
                    $attimg=explode(',',trim($postData['images'],','));
                    foreach ($attimg as $value ){
                        if(is_file($uploadConfig['path'].'product/'.$value)){
                            $image = \think\Image::open($uploadConfig['path'].'product/'.$value);
                            $image->save($uploadConfig['path'].'manage/product/'.$productid.'/'.$value);
                            foreach ($uploadConfig['thumb'] as $item){
                                $image->thumb($item, $item)->save($uploadConfig['path'].'manage/product/'.$productid.'/'.$item.'/'.$value);
                            }
                            unlink($uploadConfig['path'].'product/'.$value);
                        }
                    }
                }
                $this->_success('添加成功！');
            }else{
                $this->_error('添加失败！');
            }
        }elseif($postData['op'] == 'edit'){//修改
            $productid=$postData['productid'];
            $checkproductName = $this->ProductModel->where("prodname='{$postData['prodname']}' and productid !='{$productid}'")->find();
            //判断菜单名不要重复
            if($checkproductName){
                $this->_error('产品名已存在,请重新输入');
            }
            //启动事务
            Db::startTrans();
            try{
                //更新产品信息
                $this->ProductModel->save($data,['productid'=>$productid]);
                $uploadConfig=config('upload');
//                主图生成缩略图
                if(is_file($uploadConfig['path'].'product/'.$postData['sourceimg'])){
                    $image = \think\Image::open($uploadConfig['path'].'product/'.$postData['sourceimg']);
                    checkPath($uploadConfig['path'].'manage/product/'.$productid.'/');
                    $image->save($uploadConfig['path'].'manage/product/'.$productid.'/'.$postData['sourceimg']);
                    foreach ($uploadConfig['thumb'] as $item){
                        checkPath($uploadConfig['path'].'manage/product/'.$productid.'/'.$item.'/');
                        $image->thumb($item, $item)->save($uploadConfig['path'].'manage/product/'.$productid.'/'.$item.'/'.$postData['sourceimg']);
                    }
                    unlink($uploadConfig['path'].'product/'.$postData['sourceimg']);

                }
//               附图生成缩略图
                if($postData['images']){
                    $attimg=explode(',',trim($postData['images'],','));
                    foreach ($attimg as $value ){
                        if(is_file($uploadConfig['path'].'product/'.$value)){
                            $image = \think\Image::open($uploadConfig['path'].'product/'.$value);
                            $image->save($uploadConfig['path'].'manage/product/'.$productid.'/'.$value);
                            foreach ($uploadConfig['thumb'] as $item){
                                $image->thumb($item, $item)->save($uploadConfig['path'].'manage/product/'.$productid.'/'.$item.'/'.$value);
                            }
                            unlink($uploadConfig['path'].'product/'.$value);
                        }
                    }
                }
//                删除修改的图
                if($postData['delimg']){
                    $delimg=explode(',',trim($postData['delimg'],','));
                    foreach ($delimg as $v2 ){
                        if(is_file($uploadConfig['path'].'manage/product/'.$productid.'/'.$v2)){
                            unlink($uploadConfig['path'].'manage/product/'.$productid.'/'.$v2);
                            foreach ($uploadConfig['thumb'] as $item){
                                unlink($uploadConfig['path'].'manage/product/'.$productid.'/'.$item.'/'.$v2);
                            }
                        }
                    }
                }

                //提交事务
                Db::commit();
                $this->_success('更新成功');
            } catch (\Exception $e) {
                //回滚事务
                Db::rollback();
                $this->_error('更新失败');
            }
        }
    }

    //===============返回JSON格式==================
    /*
    * 获取菜单列表 json
    */
    public function getProductlist(){
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
//        测试
        $config['list_rows'] = config('paginate.list_rows');
        //获取列表
        $list = $this->ProductModel->field('a.*,catname')->alias('a')->join('t_category b ','b.catid= a.catid','Left')->where($condition)->order('a.createtime','desc')->paginate($config['list_rows'],false,$options)->toArray();

        if(isset($list['data'])){
            foreach ($list['data'] as $key=> $val){
                $list['data'][$key]['isshow']=$val['isshow']==1?'上架':'下架';
                $list['data'][$key]['createtime']=date('Y-m-d H:i:s',$val['createtime']);
            }
        }
        $this->_data($list);
    }


    /**
     * 上传产品图片
     */
    public function uploadimg(){

        $uploadConfig=config('upload');
        $file = request()->file('img');
        $images = request()->file('att');
        // 移动到框架应用根目录/public/upload/ 目录下
        if(!empty($file)){
            $info = $file->rule('uniqid')->move($uploadConfig['path'].'product/');
        }else if(!empty($images)){
            $imginfo = $images->rule('uniqid')->move($uploadConfig['path'].'product/');
        }else{
            $this->error('请选择上传文件');
        }
        // 上传成功返回文件名信息
        if(isset($info)){
            $this->_success($info->getSaveName(),'sourceimg');
        }else if(isset($imginfo)) {
            $this->_success($imginfo->getSaveName(),'images');
        }else{
            // 上传失败获取错误信息
            $this->_error($file->getError());
        }
    }
    /**
     * 删除不要的图片
     */
    public function delimg(){
        if(empty(input('post.imgsrc'))){
            $this->error('请选择删除图片！');
        }
        $uploadConfig=config('upload');
        if(empty(input('post.productid')) && is_file($uploadConfig['path'].'product/'.input('post.imgsrc'))){
            unlink($uploadConfig['path'].'product/'.input('post.imgsrc'));
            $this->_success('删除成功！');
        }else if(is_file($uploadConfig['path'].'manage/product/'.input('post.productid').'/'.input('post.imgsrc'))){
            unlink($uploadConfig['path'].'manage/product/'.input('post.productid').'/'.input('post.imgsrc'));
            foreach ($uploadConfig['thumb'] as $value){
                unlink($uploadConfig['path'].'manage/product/'.input('post.productid').'/'.$value.'/'.input('post.imgsrc'));
            }
            $this->_success('删除成功！');
        }else{
            $this->_error('删除失败！');
        }

    }
}