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
use think\Session;
use app\common\model\News as newsModel;


class News extends Base{
    /**
     * 构造方法
     */
    public function _initialize(){

        $this->newsModel = new newsModel();
    }

    //新闻分类列表
    public function index(){

        return $this->fetch('news/list');
    }

    /**
      *获取列表数据
      **/
    public function getNewsList(){

        $keyword=input('post.keyword');
        $curr = input('post.curr');
        $map = [];
        $data=[];

        if($keyword!=''){
            $map['title'] = ['like','%'.$keyword.'%'];
        }

        $options=[
            'page'=>$curr,
        ];

        //排序
        $List = $this->newsModel->where($map)->order('createtime','desc')->paginate(13,false,$options)->toArray();

        if($List){
            foreach ($List['data'] as $k=>$v){

                $v['tuijian']=$v['istuijian']==1?'是':'否';
                $v['show']=$v['isshow']==1?'是':'否';
              $v['createtime']=date("Y-m-d",$v['createtime']);
                $data[]=$v;
            }
        }
        $List['code']=200;
        $List['data']=$data;

        return $List;
    }

    //修改新闻
    public function changeNews(){
        $type=input('post.type');
        $id=input('post.id');
        $ischeck=input('post.ischeck');
        $data=array();
        if(empty($id))$this->_error('数据不完整，刷新重试！');
        $where = array('newsid'=>$id);

        if($type == 'isshow'){
            $data = array(
                'isshow'=>$ischeck,
            );
        }
        elseif($type == 'istuijian'){
            $data = array(
                'istuijian'=>$ischeck,
            );
        }

        if($this->newsModel->save($data,$where)){
            $this->_success('修改成功',array('checked'=>$ischeck));
        }else{
            $this->_error('修改失败');
        }

    }

    //删除新闻
    public function delNews(){

        $id=input('post.id');

        if(empty($id))$this->_error('数据不完整，刷新重试！');
        $where = array('newsid'=>$id);

        if($this->newsModel->where($where)->delete()){

            $this->_success('删除成功');
        }else{
            $this->_error('删除失败');
        }

    }


    //删除新闻
    public function delNewsList(){

        $ids=input('post.ids');

        if(empty($ids))$this->_error('数据不完整，刷新重试！');

        if($this->newsModel-> where('newsid','exp',' IN ('.$ids.') ')->delete()){
            $this->_success('删除成功');
        }else{
            $this->_error('删除失败');
        }

    }

    //保存新闻数据
    public function save(){
        $postData = input('post.');

        $rule = [
            ['title','require','请输入新闻标题!'],
            ['contant','require','请输入新闻内容!'],
            ['imageURL','require','请添加新闻封面!'],
        ];
        if($postData['type'] == 'edit'){//编辑
            $rule []= ['newsid','require','数据不完整，刷新重试!'];
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

        if(empty($postData['author'])){
            $postData['author']=session('admin.realname');
        }
        //作者
        $intro='';
        if(empty($postData['intro'])){
            $intro = substr(strip_tags($postData['contant']),0,300);
        }else{
            $intro = $postData['intro'];
        }
        $data=array(
            'author'=>$postData['author'],
            'contant' =>$postData['contant'],
            'intro' =>$postData['intro'],
            'image' =>$postData['imageURL'],
            'isshow' =>$postData['isshow'],
            'istuijian' =>$postData['istuijian'],
            'intro'=>$intro,
            //'pid' =>$postData['pid'],
            'title' =>$postData['title'],
        );

       if($postData['type'] == 'add'){//添加

            $data['createtime'] = $createtime;
            if($this->newsModel->save($data)){
                $this->_success('添加成功！');
            }else{
                $this->_error('添加失败！');
            }

       }else if($postData['type'] == 'edit'){
           $ret = $this->newsModel->update($data,['newsid'=>$postData['newsid']]);
           if($ret){
               $this->_success('修改成功！');
           }else{
               $this->_error('修改失败！');
           }
       }

    }

    //添加页面
    public function newsAdd(){

        return $this->fetch('news/newsAdd',array('createtime'=>date('Y-m-d')));
    }

    //编辑页面
    public function newsEdit(){
        $id=input("get.id");

        if(empty($id)) $this->_error('数据不完整，刷新重试!');

        $data=newsModel::get($id);

        $data['time']=date('Y-m-d',$data['createtime']);
        $data['show'] = $data['isshow']=="1" ? "checked" : "";
        $data['tuijian'] = $data['istuijian']=="1" ?  "checked" : "";

        $this->assign('data',$data);
        return $this->fetch('news/newsEdit');
    }

    //上传新闻图片
    public function uploadImg(){
        $file = request()->file('image');
        if(empty($file)){
            $this->error('请选择上传文件');
        }
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'upload/manage');
        if($info){
            // 上传成功返回文件名信息
            $this->_data($info->getSaveName());
        }else{
            // 上传失败获取错误信息
            $this->_error($file->getError());
        }

    }
}