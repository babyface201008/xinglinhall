<?php
/**
 * User: Chris He
 * Date: 2017/6/15
 * Time: 9:57
 * Desc: 后台管理员模型类
 */
namespace app\common\model;

use think\Model;
use think\Db;

class AdminUsergroup extends Base{

    // 设置当前模型对应的完整数据表名称
    protected $table = 't_admin_usergroup';

    /**获取后台用户角色菜单
     * @param $adminid 后台用户ID
     * @param
     * @return
     */
    public static function getGroupByAdminid($adminid){

        $data=Db::name('AdminUsergroup')
            ->alias('usergroup')
            ->join('t_admin_group group','group.groupid = usergroup.groupid')
            ->where("adminid = $adminid")
            ->select();

        return $data;
    }


}