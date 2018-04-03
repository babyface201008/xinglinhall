<?php
/**
 * User: Chris He
 * Date: 2017/6/15
 * Time: 14:10
 * Desc: 案例分类控制器
 */
namespace app\manage\controller;
use think\Controller;
use think\Validate;
use app\common\model\SolutionType as solutiontypeModel;
use app\common\model\Solution as solutionModel;


class Solutiontype extends Base{
    /**
     * 构造方法
     */
    public function _initialize(){

        $this->solutiontypeModel = new solutiontypeModel();
        $this->solutionModel = new solutionModel();
    }

    //案例分类列表
    public function index(){

        return $this->fetch('solutiontype/list');
    }

    //添加案例分类
    public function solutiontypeAdd(){

        return $this->fetch('solutiontype/solutiontypeAdd');
    }

    //编辑案例分类
    public function solutiontypeEdit(){
        $id=input("get.id");

        if(empty($id)) $this->_error('数据不完整，刷新重试!');

        $arr=$this->solutiontypeModel->where(['pid'=>'0','isshow'=>'1'])->select()->toArray();

        $data=solutiontypeModel::get($id);

        $data['isshow'] = $data['isshow']=="1" ? "checked" : "";


        return $this->fetch('solutiontype/solutiontypeEdit',['data'=>$data]);
    }

    //保存案例分类数据
    public function save(){
        $postData = input('post.');
//        pr($postData);die;
        $rule = [
            ['catname','require','分类名称!'],
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
            $pidData = $this->solutiontypeModel->where("catid={$postData['pid']}")->find();
            $level = $pidData['level']+1;
        }

        if($postData['type'] == 'add'){//编辑
            $data['createtime'] =time();
        }
        $data=array(
            'catname'=>$postData['catname'],
            'pid' =>$postData['pid'],
            'sort' =>$postData['sort'],
            'isshow' =>$postData['isshow'],
            'level'=>$level
        );

        if($postData['type'] == 'add'){
            $checkcatName = $this->solutiontypeModel->where("catname='{$postData['catname']}'")->find();
            //判断菜单名不要重复
            if($checkcatName){
                $this->_error('分类名已存在,请重新输入！');
            }


           if($this->solutiontypeModel->save($data)){
               $this->_success('添加成功！');
           }else{
               $this->_error('添加失败！');
           }

        }else{
            $checkcatName = $this->solutiontypeModel->where("catname='{$postData['catname']}' and catid !={$postData['catid']}")->find();
            //判断菜单名不要重复
            if($checkcatName){
                $this->_error('分类名已存在,请重新输入！');
            }
            if($postData['catid'] == $postData['pid']){
                $this->_error('不能选自己为父级分类！');
            }

            if($this->solutiontypeModel->update($data,['catid'=>$postData['catid']])){
                $this->_success('编辑成功！');
            }else{
                $this->_error('编辑失败！');
            }

        }

    }

    //删除分类
    public function delsolutiontype(){

        $id=input('post.id');

        if(empty($id))$this->_error('数据不完整，刷新重试！');

        if($this->solutiontypeModel->where('pid='.$id)->count()){
            $this->_error('请先删除子级分类');
        }

        if($this->solutionModel->where('catid='.$id)->count()){
            $this->_error('分类下还有案例信息,不能删除!');
        }

        $where = array('catid'=>$id);

        if($this->solutiontypeModel->where($where)->delete()){

            $this->_success('删除成功');
        }else{
            $this->_error('删除失败');
        }

    }

    /*
    * 获取菜单列表 json
    */
    public function getTypeListJson(){
        //获取列表
        $list = $this->solutiontypeModel->where('isshow','1')->order('pid','asc')->order('sort','asc')->select()->toArray();
        $arrData = tree_array2($list,'catid','pid');
        die(json_encode($arrData));
    }

    //获取分类列表
    public function solutiontypeList(){

        $List=$this->solutiontypeModel->select()->toArray();

        if($List){
            foreach ($List as $v){
                $v['show']=$v['isshow']==1?'是':'否';
                $arr[]=$v;
            }
           // $arr2=array2_sort($arr,'sort');
            $arrData = tree_array2($arr,'catid','pid');


            return $arrData;
        }else{
            return false;
        }
    }

}