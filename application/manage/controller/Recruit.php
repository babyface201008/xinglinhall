<?php
/**
 * User: jeson
 * Date: 2017/6/16
 * Time: 10:35
 * Desc: 菜单管理控制器
 */
namespace app\manage\controller;
use think\Controller;
use app\common\model\Recruit as RecruitModel;
use think\Validate;
use think\Paginator;
use think\Db;
class Recruit extends Base{

    /**
     * 构造方法
     */
    public function _initialize(){

        $this->RecruitModel = new RecruitModel();
    }
    /**
     * 显示合作伙伴列表页
     */
    public function index(){
        return $this->fetch('recruit/index');
    }
//    新增合作伙伴
    public function add(){
        return $this->fetch('recruit/add');
    }

    /**
     * 编辑菜单
     */
    public function edit(){
        $recruitid = input('get.recruitid');
        if(empty($recruitid)) $this->_error('请选择编辑行!');
        $recruitData = $this->RecruitModel->get($recruitid);
        $this->assign('recruitData',$recruitData);
        return $this->fetch('recruit/edit');
    }
    /*
    * 删除信息
    */
    public function del(){
        $recruitid = input('post.recruitid');
        if(empty($recruitid)) $this->_error('请选择编辑行!');
        if($this->RecruitModel->where("recruitid in ({$recruitid})")->delete())
        {
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
            ['title','require','招聘标题不能为空!'],
            ['post','require','招聘职位不能为空!'],
            ['sort','require','招聘排序不能为空!'],
            ['desc','require','招聘详情不能为空!'],
        ];
        if($postData['op'] == 'edit'){//编辑
            $rule []= ['recruitid','require','请选择编辑数据!'];
        }
        $validate = new Validate($rule);
        if(!$validate->check($postData)){
            //验证不通过
            $this->_error($validate->getError());
        }
//        编辑数据
        $data = array(
            'title'=>$postData['title'],
            'post'=>$postData['post'],
            'sort'=>$postData['sort'],
            'isshow'=>input('post.isshow')=='on'?'1':0,
            'desc'=>$postData['desc']

        );
        if($postData['op'] == 'add'){//添加
            $checkName = RecruitModel::getByTitle($postData['title']);
            //判断菜单名不要重复
            if($checkName){
                $this->_error('招聘标题已存在,请重新输入');
            }
            $data['createtime']=time();
                //添加后台用户信息
            if($this->RecruitModel->save($data)){
                $this->_success('添加成功！');
            }else{
                $this->_error('添加失败！');
            }
        }elseif($postData['op'] == 'edit'){//修改
            $recruitid=$postData['recruitid'];
            $checkrecruitName = $this->RecruitModel->where("title='{$postData['title']}' and recruitid !='{$recruitid}'")->find();
            //判断菜单名不要重复
            if($checkrecruitName){
                $this->_error('招聘标题已存在,请重新输入');
            }



            //启动事务
            Db::startTrans();
            try{
                //更新产品信息
                $this->RecruitModel->save($data,['recruitid'=>$recruitid]);
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
    public function getRecruitlist(){
        //搜索栏
        $keyword = input('post.keyword');//关键字
        $curr = input('post.curr');//关键字
        //组装搜索条件
        $condition = [];
        if($keyword!=''){
            $condition['title'] = ['like','%'.$keyword.'%'];
        }
        $options=[
            'page'=>$curr,
        ];
        $config=config('paginate');
//        测试
//        $config['list_rows']=4;
        //获取列表
        $list = $this->RecruitModel->where($condition)->order('createtime','desc')->paginate($config['list_rows'],false,$options)->toArray();
        if(isset($list['data'])){
            foreach ($list['data'] as $key=> $datum){
                $list['data'][$key]['isshow']=$datum['isshow']==1?'显示':'隐藏';
                $list['data'][$key]['createtime']=date('Y-m-d H:i:s',$datum['createtime']);
            }
        }
        $this->_data($list);
    }

}