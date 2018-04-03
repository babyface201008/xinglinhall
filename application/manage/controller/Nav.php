<?php
/**
 * User: jeson
 * Date: 2017/6/16
 * Time: 10:35
 * Desc: 菜单管理控制器
 */
namespace app\manage\controller;
use think\Controller;
use app\common\model\Nav as NavModel;
use think\Validate;
use think\File;
use think\Paginator;
use think\Db;
class Nav extends Base{

    /**
     * 构造方法
     */
    public function _initialize(){

        $this->NavModel = new NavModel();
    }
    /**
     * 显示合作伙伴列表页
     */
    public function index(){
        return $this->fetch('nav/index');
    }
//    新增合作伙伴
    public function add(){
        return $this->fetch('nav/add');
    }

    /**
     * 编辑菜单
     */
    public function edit(){
        $navid = input('get.navid');
        if(empty($navid)) $this->_error('请选择编辑行!');
        $navData = $this->NavModel->get($navid);
        if($navData['navname']=='首页'){
            $navData['att']=explode(',',trim($navData['ad_src'],','));
        }
        $this->assign('navData',$navData);
        return $this->fetch('nav/edit');
    }
    /*
    * 删除信息
    */
    public function del(){
        $navid = input('post.navid');
        if(empty($navid)) $this->_error('请选择编辑行!');
        $data=$this->NavModel->where("navid in({$navid})")->select()->toArray();
        if($this->NavModel->where("navid in ({$navid})")->delete())
        {
//            删除图片
            $uploadConfig=config('upload');
            foreach ($data as $item){
                if(is_file($uploadConfig['path'].'/manage/ad/'.$item['ad_src'])){
                    unlink($uploadConfig['path'].'/manage/ad/'.$item['ad_src']);
                    foreach ($uploadConfig['thumb'] as $value){
                        unlink($uploadConfig['path'].'manage/ad/'.'/'.$value.'/'.$item['ad_src']);
                    }
                }
            }
            $this->_success('删除成功');
        }else{
            $this->_error('删除失败');
        }
    }

    /*
     * 保存数据
     */
    public function save(){
        $postData = input('post.');
        $rule = [
            ['navname','require','菜单名称不能为空!'],
            ['parentid','require','菜单父级不能为空!'],
//            ['url','require','菜单链接不能为空!'],
            ['ad_src','require','菜单ad不能为空!'],
            ['sort','require','菜单排序不能为空!'],
        ];
        if($postData['op'] == 'edit'){//编辑
            $rule []= ['navid','require','请选择编辑数据!'];
        }
        $validate = new Validate($rule);
        if(!$validate->check($postData)){
            //验证不通过
            $this->_error($validate->getError());
        }
//        编辑数据
        $data = array(
            'navname'=>$postData['navname'],
            'parentid'=>$postData['parentid'],
            'url'=>$postData['url'],
            'ad_src'=>trim($postData['ad_src'],','),
            'ad_url'=>$postData['ad_url'],
            'sort'=>$postData['sort'],
            'isshow'=>input('post.isshow')=='on'?'1':0

        );

        if($postData['op'] == 'add'){//添加
            $checkName = NavModel::getByNavname($postData['navname']);
            //判断菜单名不要重复
            if($checkName){
                $this->_error('菜单名已存在,请重新输入');
            }
                //添加后台用户信息
            if($this->NavModel->save($data)){
                $this->_success('添加成功！');
            }else{
                $this->_error('添加失败！');
            }
        }elseif($postData['op'] == 'edit'){//修改
            $checkNavName = $this->NavModel->where("navname='{$postData['navname']}' and navid !='{$postData['navid']}'")->find();
            //判断菜单名不要重复
            if($checkNavName){
                $this->_error('菜单名已存在,请重新输入');
            }
            //启动事务
            Db::startTrans();
            try{
                //更新账户信息
                $this->NavModel->save($data,['navid'=>$postData['navid']]);
                //提交事务
                Db::commit();
                $this->_success('更新成功');
            } catch (\Exception $e) {
                //回滚事务
                Db::rollback();
                $this->_error('更新失败');
            }
        }
    }

    //===============返回JSON格式==================
    /*
    * 获取菜单列表 json
    */
    public function getNavListJson(){

        //获取列表
        $list = $this->NavModel->order('parentid','asc')->order('sort','asc')->select()->toArray();
        $arrData = tree_array2($list,'navid','parentid');
        die(json_encode($arrData));
    }

    //===============返回JSON格式==================
    /*
    * 获取菜单列表
    */
    public function getNavlist(){
        //搜索栏
        $keyword = input('post.keyword');//关键字
        $curr = input('post.curr');//关键字
        //组装搜索条件
        $condition = [];
        if($keyword!=''){
            $condition['a.navname'] = ['like','%'.$keyword.'%'];
        }
        $options=[
            'page'=>$curr,
        ];
        //获取列表
        $list = $this->NavModel->alias('a')->join('t_nav b ','b.navid= a.parentid','Left')->where($condition)->order('a.parentid','asc')->order('a.sort','asc')->field('a.*,b.navname as pname')->select()->toArray();
        $arrData=array();
        if($list){
            foreach ($list as $key=> $datum){
                $list[$key]['isshow']=$datum['isshow']==1?'显示':'隐藏';
                if($list[$key]['pname']==''){
                    $list[$key]['pname']='顶级菜单';
                }
                if($list[$key]['navname']=='首页'){
                    $adsrc=explode(',',trim($datum['ad_src'],','));
                    $list[$key]['ad_src']=$adsrc[0];
                }
            }
            $arrData = tree_array2($list,'navid','parentid');
        }
        $this->_data($arrData);
    }

    /**
     * 上传菜单ad
     */
    public function uploadimg(){
        $file = request()->file('nav_ad');
        if(empty($file)){
            $this->error('请选择上传文件');
        }
        $config=config('upload');
        $image = \think\Image::open($file);
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->rule('uniqid')->move($config['path'].'/manage/ad/');
        foreach ($config['thumb'] as $item){
            checkPath($config['path'].'manage/ad/'.$item.'/');
            $image->thumb($item, $item)->save($config['path'].'manage/ad/'.$item.'/'.$info->getSaveName());
        }
        if($info){
            // 上传成功返回文件名信息
            $this->_data($info->getSaveName());
        }else{
            // 上传失败获取错误信息
            $this->_error($file->getError());
        }
    }
}