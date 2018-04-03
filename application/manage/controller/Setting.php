<?php
/**
 * User: jeson
 * Date: 2017/6/16
 * Time: 10:35
 * Desc: 菜单管理控制器
 */
namespace app\manage\controller;
use think\Controller;
use app\common\model\Setting as SettingModel;
use think\Validate;
use think\Paginator;
use think\Db;
class Setting extends Base{

    /**
     * 构造方法
     */
    public function _initialize(){

        $this->SettingModel = new SettingModel();
    }
    /**
     * 显示合作伙伴列表页
     */
    public function index(){
        $codeData = $this->SettingModel->getcode();
        $this->assign('codeData',$codeData);
        return $this->fetch('setting/index');
    }
//    新增合作伙伴
    public function add(){
        $codeData = $this->SettingModel->getcode();
        $this->assign('codeData',$codeData);
        return $this->fetch('setting/add');
    }

    /**
     * 编辑菜单
     */
    public function edit(){
        $settingid = input('get.settingid');
        if(empty($settingid)) $this->_error('请选择编辑行!');
        $settingData = $this->SettingModel->get($settingid);
        $codeData = $this->SettingModel->getcode();
        $this->assign('codeData',$codeData);
        $this->assign('settingData',$settingData);
        return $this->fetch('setting/edit');
    }
    /*
    * 删除信息
    */
    public function del(){
        $settingid = input('post.settingid');
        if(empty($settingid)) $this->_error('请选择编辑行!');
        if($this->SettingModel->where("settingid in ({$settingid})")->delete())
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
            ['variable','require','配置名不能为空!'],
            ['value','require','配置值不能为空!'],
            ['code','require','配置分类不能为空!'],
            ['desc','require','配置标题不能为空!'],
        ];
        if($postData['op'] == 'edit'){//编辑
            $rule []= ['settingid','require','请选择编辑数据!'];
        }
        $validate = new Validate($rule);
        if(!$validate->check($postData)){
            //验证不通过
            $this->_error($validate->getError());
        }
//        编辑数据
        $data = array(
            'variable'=>$postData['variable'],
            'value'=>$postData['value'],
            'code'=>$postData['code'],
            'desc'=>$postData['desc']
        );
        if($postData['op'] == 'add'){//添加
            $checkName = SettingModel::getByVariable($postData['variable']);
            //判断菜单名不要重复
            if($checkName){
                $this->_error('配置名已存在,请重新输入');
            }
                //添加后台用户信息
            if($this->SettingModel->save($data)){
                $this->_success('添加成功！');
            }else{
                $this->_error('添加失败！');
            }
        }elseif($postData['op'] == 'edit'){//修改
            $settingid=$postData['settingid'];
            $checksettingName = $this->SettingModel->where("variable='{$postData['variable']}' and settingid !='{$settingid}'")->find();
            //判断菜单名不要重复
            if($checksettingName){
                $this->_error('配置名已存在,请重新输入');
            }
            //启动事务
            Db::startTrans();
            try{
                //更新产品信息
                $this->SettingModel->save($data,['settingid'=>$settingid]);
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
    public function getSettinglist(){
        //搜索栏
        $keyword = input('post.keyword');//关键字
        $code = input('post.code');//分类
        $curr = input('post.curr');//关键字
        //组装搜索条件
        $condition = [];
        if($keyword!=''){
            $condition['desc'] = ['like','%'.trim($keyword).'%'];
        }
        if($code!=''){
            $condition['code'] = $code;
        }
        $options=[
            'page'=>$curr,
        ];
        $config=config('paginate');
        //获取列表
        $list = $this->SettingModel->where($condition)->order('code','desc')->paginate($config['list_rows'],false,$options)->toArray();
        if(isset($list['data'])){
            foreach ($list['data'] as $key=> $datum){
                $list['data'][$key]['code']=$this->SettingModel->getcode($datum['code']);
            }
        }
        $this->_data($list);
    }






}