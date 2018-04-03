<?php
/**
 * User: jeson
 * Date: 2017/6/16
 * Time: 10:35
 * Desc: 菜单管理控制器
 */
namespace app\manage\controller;
use think\Controller;
use app\common\model\FriendLink as FriendLinkModel;
use think\Validate;
use think\File;
use think\Paginator;
use think\Db;
class Friendlink extends Base{

    /**
     * 构造方法
     */
    public function _initialize(){

        $this->FriendLinkModel = new FriendLinkModel();
    }
    /**
     * 显示列表页
     */
    public function index(){
        return $this->fetch('friend_link/index');
    }
//    新增合作伙伴
    public function add(){
        return $this->fetch('friend_link/add');
    }

    /**
     * 编辑
     */
    public function edit(){
        $friendid = input('get.friendid');
        if(empty($friendid)) $this->_error('请选择编辑行!');
        $friendData = $this->FriendLinkModel->get($friendid);
        $this->assign('friendData',$friendData);
        return $this->fetch('friend_link/edit');
    }
    /*
    * 删除信息
    */
    public function del(){
        $friendid = input('post.friendid');
        if(empty($friendid)) $this->_error('请选择编辑行!');
        $data=$this->FriendLinkModel->where("friendid in({$friendid})")->select()->toArray();
        if($this->FriendLinkModel->where("friendid in ({$friendid})")->delete())
        {
//            删除图片
            foreach ($data as $item){
                if(is_file(ROOT_PATH . 'public' . DS . 'upload/manage/'.$item['logo'])){
                    unlink(ROOT_PATH . 'public' . DS . 'upload/manage/'.$item['logo']);
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
//        pr($postData);die;
        $rule = [
            ['webname','require','网站名称不能为空!'],
            ['url','require','网站链接不能为空!'],
            ['sort','require','网站排序不能为空!'],
            ['logourl','require','网站logo不能为空!'],
        ];
        if($postData['op'] == 'edit'){//编辑
            $rule []= ['friendid','require','请选择编辑数据!'];
        }
        $validate = new Validate($rule);
        if(!$validate->check($postData)){
            //验证不通过
            $this->_error($validate->getError());
        }
//        编辑数据
        $data = array(
            'webname'=>$postData['webname'],
            'url'=>$postData['url'],
            'sort'=>$postData['sort'],
            'logo'=>$postData['logourl'],
            'isshow'=>input('post.isshow')=='on'?'1':0,
            'desc'=>$postData['desc'],
        );

        if($postData['op'] == 'add'){//添加
            $checkName = FriendLinkModel::getByWebname($postData['webname']);
            //判断菜单名不要重复
            if($checkName){
                $this->_error('网站名已存在,请重新输入');
            }
            $data['createtime']=time();
                //添加后台用户信息
            if($this->FriendLinkModel->save($data)){
                $this->_success('添加成功！');
            }else{
                $this->_error('添加失败！');
            }
        }elseif($postData['op'] == 'edit'){//修改
            $checkfriendName = $this->FriendLinkModel->where("webname='{$postData['webname']}' and friendid !='{$postData['friendid']}'")->find();
            //判断菜单名不要重复
            if($checkfriendName){
                $this->_error('网站名已存在,请重新输入');
            }
            //启动事务
            Db::startTrans();
            try{
                //更新账户信息
                $this->FriendLinkModel->save($data,['friendid'=>$postData['friendid']]);
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
    public function getfriendlist(){
        //搜索栏
        $keyword = input('post.keyword');//关键字
        $curr = input('post.curr');//关键字
        //组装搜索条件
        $condition = [];
        if($keyword!=''){
            $condition['webname'] = ['like','%'.$keyword.'%'];
        }
        $options=[
            'page'=>$curr,
        ];
        $config=config('paginate');
        //获取列表
        $list = $this->FriendLinkModel->where($condition)->order('sort')->order('createtime','desc')->paginate($config['list_rows'],false,$options)->toArray();
        if(isset($list['data'])){
            foreach ($list['data'] as $key=> $datum){
                $list['data'][$key]['isshow']=$datum['isshow']==1?'显示':'隐藏';
            }
        }
        $this->_data($list);
    }


    /**
     * 上传合作媒体logo文件
     */
    public function uploadimg(){
        $file = request()->file('logo');
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