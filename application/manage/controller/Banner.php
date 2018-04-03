<?php
/**
 * User: Chris He
 * Date: 2018/4/2
 * Time: 15:03
 * Desc: banner控制器
 */
namespace app\manage\controller;
use think\Controller;
use app\common\model\Banner as BannerModel;
use think\Validate;
use think\File;
use think\Paginator;
use think\Db;

class Banner extends Base{

    /**
     * 构造方法
     */
    public function _initialize(){
        $this->BannerModel = new BannerModel();
    }

    /**
     * 显示列表
     */
    public function index(){
        return $this->fetch('banner/index');
    }

    /**
     * 新增
     */
    public function add(){
        return $this->fetch('banner/add');
    }

    /**
     * 编辑
     */
    public function edit(){

        $id = input('get.id');
        if(empty($id)) $this->_error('请选择编辑行!');
        //获取信息
        $bannerData = $this->BannerModel->get($id);

        $this->assign('bannerData',$bannerData);
        return $this->fetch('banner/edit');
    }

    /**
     * 删除
     */
    public function del(){

        $id = input('post.id');
        if(empty($id))$this->_error('请选择要删除行!');
        $data=$this->BannerModel->where("bannerid in({$id})")->select()->toArray();
        if($this->BannerModel->where("bannerid in ({$id})")->delete())
        {
            //删除图片
            foreach ($data as $item){
                if(is_file(ROOT_PATH . 'public' . DS . 'upload/manage/'.$item['bannerimg'])){
                    unlink(ROOT_PATH . 'public' . DS . 'upload/manage/'.$item['bannerimg']);
                }
            }
            $this->_success('删除成功');
        }else{
            $this->_error('删除失败');
        }
    }

    /**
     * 保存数据
     */
    public function save(){

        //接受参数
        $postData = input('post.');
        $rule = [
            ['bannerimg','require','轮播图不能为空!'],
            ['sort','require','网站排序不能为空!'],
        ];
        if($postData['op'] == 'edit'){//编辑
            $rule []= ['id','require','请选择编辑数据!'];
        }
        //表单验证
        $validate = new Validate($rule);
        if(!$validate->check($postData)){
            //验证不通过
            $this->_error($validate->getError());
        }

        $data = array(
            'bannerimg'=>$postData['bannerimg'],
            'bannerurl'=>$postData['bannerurl'],
            'sort'=>$postData['sort'],
            'display'=>$postData['display']=='on'?'1':2,
        );

        if($postData['op'] == 'add'){//添加

            $data['createtime']=time();
            //添加信息
            if($this->BannerModel->save($data)){
                $this->_success('添加成功！');
            }else{
                $this->_error('添加失败！');
            }
        }elseif($postData['op'] == 'edit'){//修改

            //更新信息
            $result = $this->BannerModel->save($data,['bannerid'=>$postData['id']]);
            if($result){
                $this->_success('更新成功');
            }else{
                $this->_error('更新失败');
            }


        }
    }


    //===============返回JSON格式==================
    /**
     * 获取列表数据json格式
     */
    public function getIndexJson(){


        $curr = input('post.curr');//页码
        //组装搜索条件
        $condition = [];

        $options = [
            'page'=>$curr,
        ];
        $config = config('paginate');
        //获取列表
        $list = $this->BannerModel->where($condition)->order('sort')->order('createtime','desc')->paginate($config['list_rows'],false,$options)->toArray();
        if(isset($list['data'])){
            foreach ($list['data'] as $key=> $datum){
                $list['data'][$key]['display']=$datum['display']==1?'显示':'隐藏';
            }
        }
        $this->_data($list);
    }

    /**
     * 上传banner图
     */
    public function uploadimg(){

        $file = request()->file('image');
        if(empty($file)){
            $this->error('请选择上传banner图');
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