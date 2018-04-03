<?php
/**
 * User: Chris He
 * Date: 2017/6/15
 * Time: 14:10
 * Desc: 新闻分类控制器
 */
namespace app\manage\controller;
use app\common\model\News;
use think\Controller;
use think\Validate;
use app\common\model\NewsCategory as newscategoryModel;


class Newscategory extends Base{
    /**
     * 构造方法
     */
    public function _initialize(){

        $this->newscategoryModel = new newscategoryModel();
        $this->newsModel = new News();
    }

    //新闻分类列表
    public function index(){

        return $this->fetch('newscategory/list');
    }

    //添加新闻分类
    public function newscategoryAdd(){

        return $this->fetch('newscategory/newscategoryAdd');
    }

    //编辑新闻分类
    public function newscategoryEdit(){

        $id=input("get.id");
        if(empty($id)) $this->_error('数据不完整，刷新重试!');
        $data=newscategoryModel::get($id);
        $data['isshow'] = $data['isshow']=="1" ? "checked" : "";
        return $this->fetch('newscategory/newscategoryEdit',['data'=>$data]);
    }

    //保存新闻分类数据
    public function save(){
       //接收参数
        $postData = input('post.');
        //表单验证
        $rule = [
            ['catname','require','分类标题!'],
            ['pid','require','父级分类!'],
        ];
        if($postData['type'] == 'edit'){//编辑
            $rule []= ['catid','require','数据不完整，刷新重试!'];
        }
        $validate = new Validate($rule);
        if(!$validate->check($postData)){
            //验证不通过
            $this->_error($validate->getError());
        }

        //层级
        if($postData['pid']==0){
            $level = 0;
        }else{
            $pidData = $this->newscategoryModel->where("catid={$postData['pid']}")->find();
            $level = $pidData['level']+1;
        }

        //数据
        $data=array(
            'catname'=>$postData['catname'],
            'pid' =>$postData['pid'],
            'sort' =>$postData['sort'],
            'isshow' =>$postData['isshow'],
            'level'=>$level
        );

        if($postData['type'] == 'add'){

            $checkName = newscategoryModel::getByCatname($postData['catname']);
            //判断名称不要重复
            if($checkName){
                $this->_error('分类名已存在,请重新输入！');
            }
            $data['createtime']=time();
            //添加信息
           if($this->newscategoryModel->save($data)){
               $this->_success('添加成功！');
           }else{
               $this->_error('添加失败！');
           }

        }elseif($postData['type'] == 'edit'){

            $checkcatName = $this->newscategoryModel->where("catname='{$postData['catname']}' and catid !='{$postData['catid']}'")->find();
            //判断菜单名不要重复
            if($checkcatName){
                $this->_error('分类名已存在,请重新输入！');
            }
            if($postData['catid'] == $postData['pid']){
                $this->_error('不能选自己为父级分类！');
            }
            if($this->newscategoryModel->update($data,['catid'=>$postData['catid']])){
                $this->_success('编辑成功！');
            }else{
                $this->_error('编辑失败！');
            }

        }else{
            $this->_error('非法请求！');
        }

    }

    //删除分类
    public function delNewscategory(){

        $id=input('post.id');

        if(empty($id))$this->_error('数据不完整，刷新重试！');

        if($this->newscategoryModel->where('pid='.$id)->count()){
            $this->_error('请先删除子级分类');
        }
        //判断是否有产品
        if($this->newsModel->where("pid={$id}")->count()){
            $this->_error('分类下还有新闻信息,不能删除!');
        }
        $where = array('catid'=>$id);

        if($this->newscategoryModel->where($where)->delete()){

            $this->_success('删除成功');
        }else{
            $this->_error('删除失败');
        }

    }


   //获取菜单列表 json
    public function getNewscategoryListJson(){
        //获取列表
        $list = $this->newscategoryModel->order('pid','asc')->order('sort','asc')->select()->toArray();
        foreach ($list as $key => $item){
            $list[$key]['catname']=str_repeat("|-- ",$item['level']).$item['catname'];
        }
        $arrData = tree_array2($list,'catid','pid');
        die(json_encode($arrData));
    }

    //获取分类列表
    public function newscategoryList(){

        //关键字
        $key=input('post.key');
        $where=[];

        if($key)$where['catname']=['LIKE',"%".$key."%"];

        $List=$this->newscategoryModel->where($where)->select()->toArray();

        if($List){
            foreach ($List as $v){
                $v['show']=$v['isshow']==1?'是':'否';
                $arr[]=$v;
            }
            $arrData = tree_array2($arr,'catid','pid');

            return $arrData;
        }else{
            return false;
        }
    }

}