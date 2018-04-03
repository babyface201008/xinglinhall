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

class Adminmenu extends Base{

    // 设置当前模型对应的完整数据表名称
    protected $table = 't_admin_menu';


    /**
     *
     * @param $map array 条件
     * @param string $file  要查询的字段
     * @return array|bool
     *
     */
    public static function getAdminMenuList(){
        return Db::name('AdminMenu')->order( 'parentid', 'asc')->where('isshow','1')->select();
    }
}