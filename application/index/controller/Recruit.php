<?php
namespace app\index\controller;
use app\common\model\Recruit as RecruitModel;

class Recruit extends Base
{
    public function index()
    {
        $this->RecruitModel=new RecruitModel();
        $recruirData=$this->RecruitModel->where('isshow','1')->order('createtime','desc')->order('sort')->select()->toArray();
        $this->assign('recruirData',$recruirData);
        return $this->fetch();
    }
}
