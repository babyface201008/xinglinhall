<?php
/**
 * User: jeson
 * Date: 2017/6/16
 * Time: 10:35
 * Desc: 菜单管理控制器
 */
namespace app\frameset\controller;
use think\Controller;
use app\common\model\AdminMenu as adminMenuModel;
use think\Validate;
use think\Db;
class AdminMenu extends Base{

    /**
     * 构造方法
     */
    public function _initialize(){

        $this->adminMenuModel = new adminMenuModel();
    }
    /**
     * 显示菜单列表页
     */
    public function index(){
        return $this->fetch('admin_menu/index');
    }
    public function add(){
        $MenuTree=$this->getMenuTreeJSON();
        $this->assign('MenuTree',$MenuTree);
        return $this->fetch('admin_menu/add');
    }


    /**
     * 编辑菜单
     */
    public function edit(){
        $menuid = input('post.menuid');
        if(empty($menuid)) $this->_error('ID不能为空!');
        $menuData = $this->adminMenuModel->get($menuid);
        $MenuTree=$this->getMenuTreeJSON();
        return $this->fetch('admin_menu/edit',array('data'=>$menuData,'MenuTree'=>$MenuTree));
    }
    /*
    * 删除信息
    */
    public function del(){
        $menuid = input('post.menuid');
        if(empty($menuid)) $this->_error('id不为空');
        if($this->adminMenuModel->where("parentid ='{$menuid}'")->find()) $this->_error('请先删除子菜单！');
        if($this->adminMenuModel->where("menuid='{$menuid}'")->delete())
        {
            $this->_success('删除成功','adminmenu','treegrid');
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
            ['menuname','require|max:25','菜单名称不能为空!|菜单名称不能超过25个字符!'],
            ['parentid','require','父级id不能为空!'],
            ['icon','require','图标不能为空!'],
            ['url','require','链接不能为空!'],
        ];
        if($postData['op'] == 'edit'){//编辑
            $rule []= ['menuid','require','id!'];
        }
        $validate = new Validate($rule);
        if(!$validate->check($postData)){
            //验证不通过
            $this->_error($validate->getError());
        }
//        编辑数据
        $data = array(
            'menuname'=>$postData['menuname'],
            'parentid'=>$postData['parentid'],
            'icon'=>$postData['icon'],
            'url'=>$postData['url'],
            'rel'=>$postData['rel'],
            'sort'=>$postData['sort'],
            'isshow'=>$postData['isshow']
        );
        if($postData['op'] == 'add'){//添加
            $checkMenuName = adminMenuModel::getByMenuname($postData['menuname']);
            //判断菜单名不要重复
            if($checkMenuName){
                $this->_error('菜单名已存在,请重新输入');
            }
                //添加后台用户信息
            if($this->adminMenuModel->save($data)){
                $this->_success('添加成功','adminMenu','treegrid');
            }else{
                $this->_error('添加失败');
            }

        }elseif($postData['op'] == 'edit'){//修改
            $checkMenuName = $this->adminMenuModel->where("menuname='{$postData['menuname']}' and menuid !='{$postData['menuid']}'")->find();
            //判断菜单名不要重复
            if($checkMenuName){
                $this->_error('菜单名已存在,请重新输入');
            }
            if($postData['menuid'] == $postData['parentid']){
                $this->_error('上级菜单与子菜单不能是同一个!');
            }
            //启动事务
            Db::startTrans();
            try{
                //更新账户信息
                $this->adminMenuModel->save($data,['menuid'=>$postData['menuid']]);
                //提交事务
                Db::commit();
                $this->_success('更新成功','adminMenu','treegrid');
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
    public function getMenulistJSON(){


        //搜索栏
        $keyword = input('post.keyword');//关键字

        //组装搜索条件
        $condition = [];
        if($keyword!=''){
            $condition['menuname'] = ['like','%'.$keyword.'%'];
        }
        //获取列表
        $list = $this->adminMenuModel->where($condition)->order('parentid','asc')->order('sort','asc')->select()->toArray();
        $arrData=array();
        $sortdata=array();
        if($list){
            //树状结构
            $arrData = tree_array2($list,'menuid','parentid');
            foreach ($arrData as $k2 => $v1){
                $sortdata[]=$v1;
                if(isset($v1['children'])){
                    foreach ($v1['children'] as $child){
                        $sortdata[]=$child;
                        if(isset($child['children'])){
                            foreach ($child['children'] as $item){
                                $sortdata[]=$item;
                            }
                        }
                    }
                }
            }
        }
        $aaData['aaData'] = $sortdata;
        echo json_encode($aaData);
    }

    /*
    * 获取权限菜单树 json
    */
    public function getMenuTreeJSON(){

        $type = input('get.type');
        $condition = '1=1 ';
        $order=' parentid asc,sort asc';
        $list = $this->adminMenuModel->getList($condition,'menuid,parentid,menuname,icon',$order);
        $treeList = array();
        if($list){
            foreach($list as $value){
                $array = array('id'=>$value['menuid'],'pid'=>$value['parentid'],'text'=>$value['menuname'],'iconCls'=>$value['icon']);
                $treeList[] = $array;
            }
        }
        //树状结构
        $treeList = tree_array2($treeList,'id','pid');
        array_unshift($treeList,array('id'=>0,'pid'=>0,'text'=>'顶级分类'));
        return $treeList;
    }

    /**
     * 改变菜单状态
     */
    public function muneSwitchState(){

        $menuid = input('post.menuid');
        if(empty($menuid)) $this->_error('id不为空');

        //获取账户信息
        $menuData = adminMenuModel::get($menuid);
        if(!$menuData){
            $this->_error('用户不存在');
        }

        //改变选中id账号状态
        $isshow = '';
        if($menuData['isshow']==1){
            $isshow = 0;
        }elseif($menuData['isshow']==0){
            $isshow = 1;
        }
        $data = array('isshow'=>$isshow);
        $where = array('menuid'=>$menuid);
        $result = $this->adminMenuModel->save($data,$where);
        if($result) {
            $this->_success($isshow,'adminmenu');
        }else{
            $this->_error('操作失败');
        }
    }
}