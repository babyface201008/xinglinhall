<?php
namespace app\index\controller;
use app\common\model\Aboutus as AboutusModel;

class Contactus extends Base
{
    public function index()
    {

        $gaodeAPI =config('gaode_api');
        $this->assign('key',$gaodeAPI['key']);
        return $this->fetch();
    }
}
