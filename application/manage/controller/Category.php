<?php
/**
 * User: jeson
 * Date: 2017/6/16
 * Time: 10:35
 * Desc: 菜单管理控制器
 */
namespace app\manage\controller;
use app\common\model\Product;
use think\Controller;
use app\common\model\Category as CategoryModel;
use think\Validate;
use think\File;
use think\Paginator;
use think\Db;
class Category extends Base{

    /**
     * 构造方法
     */
    public function _initialize(){

        $this->categoryModel = new CategoryModel();
        $this->productModel = new Product();
    }
    /**
     * 显示合作伙伴列表页
     */
    public function index(){
        return $this->fetch('category/index');
    }
//    新增合作伙伴
    public function add(){
        return $this->fetch('category/add');
    }

    /**
     * 编辑菜单
     */
    public function edit(){
        $catid = input('get.catid');
        if(empty($catid)) $this->_error('请选择编辑行!');
        $catData = $this->categoryModel->get($catid);
        $this->assign('catData',$catData);
        return $this->fetch('category/edit');
    }
    /*
    * 删除信息
    */
    public function del(){
        $catid = input('post.catid');
        if(empty($catid)) $this->_error('请选择删除行!');
        //判断是否有子类
        if($this->categoryModel->where("pid={$catid}")->count()){
            $this->_error('请先删除子级分类!');
        }
        //判断是否有产品
        if($this->productModel->where("catid={$catid}")->count()){
            $this->_error('分类下还有产品,不能删除!');
        }
        if($this->categoryModel->where("catid={$catid}")->delete())
        {
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
//        pr($postData);
        $rule = [
            ['catname','require','网站名称不能为空!'],
            ['pid','require','网站链接不能为空!'],
            ['sort','require','网站排序不能为空!'],
        ];
        if($postData['op'] == 'edit'){//编辑
            $rule []= ['catid','require','请选择编辑数据!'];
        }
        $validate = new Validate($rule);
        if(!$validate->check($postData)){
            //验证不通过
            $this->_error($validate->getError());
        }
        $level=$this->categoryModel->get($postData['pid']);

//        编辑数据
        $data = array(
            'catname'=>$postData['catname'],
            'pid'=>$postData['pid'],
            'sort'=>$postData['sort'],
            'desc'=>$postData['desc'],
            'level'=>isset($level['level'])?$level['level']+1:0,
            'isshow'=>input('post.isshow')=='on'?'1':0
        );

        if($postData['op'] == 'add'){//添加
            $checkName = categoryModel::getByCatname($postData['catname']);
            //判断菜单名不要重复
            if($checkName){
                $this->_error('分类名已存在,请重新输入！');
            }
            $data['createtime']=time();
                //添加后台用户信息
            if($this->categoryModel->save($data)){
                $this->_success('添加成功！');
            }else{
                $this->_error('添加失败！');
            }
        }elseif($postData['op'] == 'edit'){//修改
            $checkcatName = $this->categoryModel->where("catname='{$postData['catname']}' and catid !='{$postData['catid']}'")->find();
            //判断菜单名不要重复
            if($checkcatName){
                $this->_error('分类名已存在,请重新输入！');
            }
            if($postData['catid'] == $postData['pid']){
                $this->_error('不能选自己为父级分类！');
            }
            //启动事务
            Db::startTrans();
            try{
                //更新账户信息
                $this->categoryModel->save($data,['catid'=>$postData['catid']]);
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
    public function getCategoryListJson(){
        //获取列表
        $list = $this->categoryModel->order('pid','asc')->order('sort','asc')->select()->toArray();
        foreach ($list as $key => $item){
            $list[$key]['catname']=str_repeat("|-- ",$item['level']).$item['catname'];
        }
        $arrData = tree_array2($list,'catid','pid');
        die(json_encode($arrData));
    }

    /*
    * 获取菜单列表
    */
    public function getCategoryList(){
        //搜索栏
        $keyword = input('post.keyword');//关键字
        //组装搜索条件
        $condition = [];
        if($keyword!=''){
            $condition['a.catname'] = ['like','%'.$keyword.'%'];
        }
        //获取列表
        $list = $this->categoryModel->alias('a')->join('t_category b ','b.catid= a.pid','Left')->where($condition)->order('a.pid','asc')->order('a.sort','asc')->field('a.*,b.catname as pname')->select()->toArray();
        foreach ($list as$key=> $item){
            $list[$key]['createtime']=date('Y-m-d',$item['createtime']);
            $list[$key]['isshow']=$item['isshow']==1?'显示':'隐藏';
            if($item['pname']==null){
                $list[$key]['pname']='顶级分类';
            }else{
                $list[$key]['pname']=$item['pname'].'('.$item['pid'].')';
            }

        }
        $arrData = tree_array2($list,'catid','pid');
        $this->_data($arrData);
    }
}