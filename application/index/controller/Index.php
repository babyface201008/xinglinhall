<?php
namespace app\index\controller;
class Index extends Base
{
    public function index()
    {
//        获取banner图
        $this->getBanner();
//        获取分类产品
        $cateData=$this->CategoryModel->alias('a')->join('t_product b','b.catid= a.catid')->where('a.isshow','1')->order('b.sort','asc')->order('b.createtime','asc')->field('a.*,b.intro')->group('a.catid')->select()->toArray();
        foreach ($cateData as $key=> $item){
            $cateData[$key]['intro']=hd_substr($item['intro'],60);

        }
        $this->assign('cateData',$cateData);

        return $this->fetch();
    }

    public function getBanner()
    {
        $indexData=$this->NavModel->where('navname','首页')->find();
        $banner=explode(',',$indexData['ad_src']);
        $this->assign('indexData',$indexData);
        $this->assign('banner',$banner);
    }


}
