<?php
namespace app\index\controller;
use app\common\model\Solution as SolutionModel;

class Solution extends Base
{
    protected $catid;
    public function index($catid=0)
    {
        $this->SolutionModel=new SolutionModel();
        $catData=$this->SolutionTypeModel->where('isshow','1')->order('sort','asc')->order('createtime','asc')->select()->toArray();
        if($catid==0){
            $catid=$catData[0]['catid'];
        }

//        获取相应分类下产品
        $solutionData=$this->SolutionModel->where('isshow','1')->where('catid',$catid)->order('sort','asc')->order('createtime','asc')->select()->toArray();
        $this->assign('catid',$catid);
        $this->assign('catData',$catData);
        $this->assign('solutionData',$solutionData);
        return $this->fetch();
    }


}
