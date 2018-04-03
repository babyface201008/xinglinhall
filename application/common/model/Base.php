<?php
/**
 * User: Chris He
 * Date: 2017/6/15
 * Time: 17:09
 * Desc: 模型基类
 */
namespace app\common\model;

use think\Model;

class Base extends Model{

    /**记录总数
     * @param $map array 条件
     * @return int 记录条数
     */
    public function totalCount($map){
        $count = $this->where($map)->count();
        return $count;
    }

    /**
	 * 分页获取列表数据
     * @param $map array 条件
     * @param $limit='当前页码，每页显示条数' String 分页
     * @param $order array 排序
     * @return array 结果集
	 */
    public function getPageList($map,$limit,$orderby){
        if(empty($limit)) return false;
        return $this->where($map)
            ->page($limit)
            ->order($orderby['orderField'],$orderby['orderDirection'])
            ->select()->toArray();
    }

    /**
     *
     * @param $map array 条件
     * @param string $file  要查询的字段
     * @return array|bool
     */
    public function getList($map,$field='*',$order=''){
        if(empty($map)) return false;
        $this->where($map)->field($field);
        if(!empty($order)){
            $this->order($order);
        }
        return $this->select()->toArray();
    }


}