<?php
namespace app\index\controller;
use app\common\model\Aboutus as AboutusModel;

class Aboutus extends Base
{
    public function index()
    {
        $this->AboutusModel = new AboutusModel();
//        获取产品
        $aboutus=$this->AboutusModel->where('title','关于我们')->find()->toArray();
        $this->assign('aboutus',$aboutus);
        return $this->fetch();
    }
}
