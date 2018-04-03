<?php
/**
 * User: Chris He
 * Date: 2017/6/15
 * Time: 14:10
 * Desc: 新闻控制器
 */
namespace app\manage\controller;


use think\Controller;
use think\Validate;
use app\common\model\Solution as solutionModel;


class Solution extends Base{
    /**
     * 构造方法
     */
    public function _initialize(){

        $this->solutionModel = new solutionModel();
    }

    //案例分类列表
    public function index(){

        return $this->fetch('solution/list');
    }

    /**
      *获取列表数据
      **/
    public function getSolutionList(){

        $keyword=input('post.key');
        $curr = input('post.curr');
        $map = [];
        $data=[];

        if($keyword!=''){
            $map['name'] = ['like','%'.$keyword.'%'];
        }

        $options=[
            'page'=>$curr,
        ];

        //排序
        $List = $this->solutionModel
            ->alias('s')
            ->join('t_solution_type type','type.catid = s.catid')
            ->field('s.*,type.catname')
            ->where($map)
            ->order('s.createtime','desc')
            ->paginate(13,false,$options)
            ->toArray();

        if($List){
            foreach ($List['data'] as $k=>$v){
                $v['show']=$v['isshow']==1?'是':'否';
                $v['createtime']=date("Y-m-d",$v['createtime']);
                $data[]=$v;
            }
        }
        $List['code']=200;
        $List['data']=$data;

        return $List;
    }

    //修改案例
    public function changeSolution(){
        $type=input('post.type');
        $id=input('post.id');
        $ischeck=input('post.ischeck');
        $data=array();
        if(empty($id))$this->_error('数据不完整，刷新重试！');
        $where = array('solutionid'=>$id);
        if($type == 'isshow'){
            $data = array(
                'isshow'=>$ischeck,
            );
        }

        if($this->solutionModel->save($data,$where)){
            $this->_success('修改成功',array('checked'=>$ischeck));
        }else{
            $this->_error('修改失败');
        }

    }

    //删除案例
    public function delSolution(){

        $id=input('post.id');

        if(empty($id))$this->_error('数据不完整，刷新重试！');
        $where = array('solutionid'=>$id);

        if($this->solutionModel->where($where)->delete()){

            $this->_success('删除成功');
        }else{
            $this->_error('删除失败');
        }

    }


    //删除案例多条
    public function delSolutionList(){

        $ids=input('post.ids');

        if(empty($ids))$this->_error('数据不完整，刷新重试！');

        if($this->solutionModel-> where('solutionid','exp',' IN ('.$ids.') ')->delete()){
            $this->_success('删除成功');
        }else{
            $this->_error('删除失败');
        }

    }

    //保存案例数据
    public function save(){
        $postData = input('post.');
        $rule = [
            ['name','require','案例名称!'],
            ['desc','require','案例详情!'],
        ];

        if($postData['type'] == 'edit'){//编辑
            $rule []= ['solutionid','require','数据不完整，刷新重试!'];
        }
        $validate = new Validate($rule);
        if(!$validate->check($postData)){
            //验证不通过
            $this->_error($validate->getError());
        }

        //时间转换
        if(empty($postData['time'])){
            $createtime=time();
        }else{
            $createtime=strtotime($postData['time']);
        }

        $Data=array(
            'name'=>$postData['name'],
            'desc' =>$postData['desc'],
            'isshow' =>$postData['isshow'],
            'catid' =>$postData['catid'],
            'sort' =>$postData['sort'],
            'createtime' =>$createtime,
        );

       if($postData['type'] == 'add'){//添加

            if($this->solutionModel->save($Data)){
                $this->_success('添加成功！');
            }else{
                $this->_error('添加失败！');
            }

       }else if($postData['type'] == 'edit'){
           if($this->solutionModel->update($Data,['solutionid'=>$postData['solutionid']])){
               $this->_success('修改成功！');
           }else{
               $this->_error('修改失败！');
           }
       }

    }

    //添加页面
    public function solutionAdd(){

        return $this->fetch('solution/solutionAdd',['time'=>date('Y-m-d',time())]);
    }

    //编辑页面
    public function solutionEdit(){
        $id=input("get.id");

        if(empty($id)) $this->_error('数据不完整，刷新重试!');

        $data=solutionModel::get($id);

        $data['time']=date('Y-m-d',$data['createtime']);
        $data['show'] = $data['isshow']=="1" ? "checked" : "";

        $this->assign('data',$data);
        return $this->fetch('solution/solutionEdit');
    }

}