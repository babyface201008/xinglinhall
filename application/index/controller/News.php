<?php
namespace app\index\controller;
use app\common\model\News as NewsModel;
use think\Paginator;


class News extends Base
{
    public function index()
    {

        $options=[
            'page'=>input('get.page')?input('get.page'):1,
        ];
        $this->NewsModel=new NewsModel();
        $config['list_rows'] = config('paginate.list_rows');
        $config['list_rows']=4;
        $NewsData=$this->NewsModel->where('isshow','1')->order('istuijian')->order('createtime','desc')->order('sort')->paginate($config['list_rows'],'',$options)->toArray();
        if($NewsData['data']){
            foreach ($NewsData['data'] as $k =>$v){
                $NewsData['data'][$k]['intro']=hd_substr($v['intro'],100);
            }
        }

        $this->assign('NewsData',$NewsData);
        return $this->fetch();
    }

    public function show()
    {
        $this->NewsModel=new NewsModel();
        $newsid=input('get.newsid');
        if(empty($newsid)){
            $this->page_redirect('请选择新闻！','news/index');
        }
        $NewsDetail=$this->NewsModel->get($newsid);
        if(empty($NewsDetail)){
            $this->page_redirect('该新闻已删除！','news/index');
        }

        $this->assign('NewsDetail',$NewsDetail);
        return $this->fetch();
    }

}
