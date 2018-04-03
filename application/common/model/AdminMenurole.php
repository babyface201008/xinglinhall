<?php
/**
 * User: jeson
 * Date: 2017/6/16
 * Time: 9:57
 * Desc: 菜单管理模型类
 */
namespace app\common\model;

use think\Model;
use think\Db;

class Adminmenurole extends Base{

    // 设置当前模型对应的完整数据表名称
    protected $table = 't_admin_menurole';


    /**角色与菜单列表数据
     * @param
     * @param
     * @return
     */
    public  static  function getMenuGroupList($groupids){
        $Data=Db::name('AdminMenurole')
            ->alias('menurole')
            ->join('t_admin_menu menu','menu.menuid = menurole.menuid')
            ->where("isshow ",1)
            ->where('groupid','in',$groupids)
            ->select();
        return $Data;
    }
}