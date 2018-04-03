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

class Setting extends Base{

    // 设置当前模型对应的完整数据表名称
    protected $table = 't_setting';

    /*
    * 获取系统参数设置
    * 返回结果为数组
    */
    public function getListInfo($condition){

        $arrR1=array();
        $result = $this->getList($condition);
        foreach($result as $key=>$val) {
            $arrR1[$val['variable']] =$val['value'];
        }
        return $arrR1;
    }

    /*
* 更新系统参数设置
*/
    public function updateSetting($data){
        //step1 开启事务
        //启动事务
        Db::startTrans();
        try{
            foreach($data as $key=>$val) {
                //step2 更新
                $this->where('variable',$key)->update(array('value'=>$val));
            }
            //提交事务
            Db::commit();
            return true;
        } catch (\Exception $e) {
            //回滚事务
            Db::rollback();
            return false;
        }
    }

/*
 * 获取配置类型
 */
public function getcode($code=''){
    $codearr=array(
        'basic'=>'基本配置',
        'system'=>'系统配置',
        'sms'=>'短信配置',
        'email'=>'邮件配置',
        'score'=>'积分配置',
        'seo'=>'seo配置'
    );
    if(empty($code)){
        return $codearr;
    }else{
        return $codearr[$code];
    }



}


}