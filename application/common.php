<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * User: Chris He
 * Date: 15-9-10
 * Time: 上午10:42
 * Desc: 网站公共方法
 */
function show_msg($msg){
    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
    echo "<script>alert('".strip_tags(trim($msg))."');history.back();</script>";
    exit;
}

/**
 * 树状结构整理成多维数组
 * @param $arrInfo需要整理的一维数组
 * @param $tb_id ID键名
 * @param $tb_pid父ID键名
 * @return array
 */
function tree_array($arrInfo,$tb_id,$tb_pid){
    $res = array(); //结果数组
    $ind = array(); //索引数组

    foreach($arrInfo as $key=>$val){
        list($id, $pid) = array($val[$tb_id],$val[$tb_pid]);
        $ind[$id] = $val;
        if(isset($ind[$pid])) $ind[$pid]['details'][] =& $ind[$id]; //构造索引
        if($pid == 0) $res[] =& $ind[$id]; //转存根节点组
    }
    return $res;
}

/**
 * 树状结构整理成多维数组
 * @param $arrInfo需要整理的一维数组
 * @param $tb_id ID键名
 * @param $tb_pid父ID键名
 * @return array
 */
function tree_array2($arrInfo,$tb_id,$tb_pid){
    $res = array(); //结果数组
    $ind = array(); //索引数组

    foreach($arrInfo as $key=>$val){
        list($id, $pid) = array($val[$tb_id],$val[$tb_pid]);
        $ind[$id] = $val;
        if(isset($ind[$pid])) $ind[$pid]['children'][] =& $ind[$id]; //构造索引
        if($pid == 0) $res[] =& $ind[$id]; //转存根节点组
    }
    return $res;
}

/*
 * 二维数组排序
 * */
function array2_sort($a,$sort,$d='') {
    $num=count($a);
    if(!$d){
        for($i=0;$i<$num;$i++){
            for($j=0;$j<$num-1;$j++){
                if($a[$j][$sort] > $a[$j+1][$sort]){
                    $t=$a[$j+1];
                    $a[$j+1]=$a[$j];
                    $a[$j]=$t;
                }
            }
        }
    }
    else{
        for($i=0;$i<$num;$i++){
            for($j=0;$j<$num-1;$j++){
                if($a[$j][$sort] < $a[$j+1][$sort]){
                    $t=$a[$j+1];
                    $a[$j+1]=$a[$j];
                    $a[$j]=$t;
                }
            }
        }
    }
    return $a;
}

/**
 * 二维数组转一维
 */
function array_change($a){
    static $arr2;
    foreach($a as $v){
        if(is_array($v)){
            arrayChange($v);
        }
        else{
            $arr2[]=$v;
        }
    }
    return $arr2;
}

/**
 * 树状结构整理成多维数组
 * @param $arrInfo需要整理的一维数组
 * @param $tb_id ID键名
 * @param $tb_pid父ID键名
 * @return array
 */
function treeToArray($arrInfo,$tb_id,$tb_pid,$_pid=0){
    $res = array(); //结果数组
    $ind = array(); //索引数组

    foreach($arrInfo as $key=>$val){
        list($id, $pid) = array($val[$tb_id],$val[$tb_pid]);
        $ind[$id] = $val;
        if(isset($ind[$pid])) $ind[$pid]['details'][] =& $ind[$id]; //构造索引
        if($pid == $_pid ) $res[] =& $ind[$id]; //转存根节点组
    }
    return $res;
}

/*
 * 二维数组排序
 * */
function array2sort($a,$sort,$d='') {
    $num=count($a);
    if(!$d){
        for($i=0;$i<$num;$i++){
            for($j=0;$j<$num-1;$j++){
                if($a[$j][$sort] > $a[$j+1][$sort]){
                    $t=$a[$j+1];
                    $a[$j+1]=$a[$j];
                    $a[$j]=$t;
                }
            }
        }
    }
    else{
        for($i=0;$i<$num;$i++){
            for($j=0;$j<$num-1;$j++){
                if($a[$j][$sort] < $a[$j+1][$sort]){
                    $t=$a[$j+1];
                    $a[$j+1]=$a[$j];
                    $a[$j]=$t;
                }
            }
        }
    }
    return $a;
}

/**
 * 写日志
 * 注意：服务器需要开通fopen配置
 * @param $word 要写入日志里的文本内容 默认值：空值
 */
function my_log($word='') {
    if(DIRECTORY_SEPARATOR=='\\'){
        $path = ROOT_PATH."log/winlog.txt";
    }else{
        $path =  "/server/log/ecshop/web.log";
    }
    $fp = fopen($path,"a");
    flock($fp, LOCK_EX) ;
    fwrite($fp,"date:".date("Y-m-d H:m:s",time())."\n".$word."\n");
    flock($fp, LOCK_UN);
    fclose($fp);
}

/**
 * 错误写日志
 * 注意：服务器需要开通fopen配置
 * @param $word 要写入日志里的文本内容 默认值：空值
 */
function web_error_log($word='') {
    if(DIRECTORY_SEPARATOR=='\\'){
        $path = ROOT_PATH."log/error.txt";
    }else{
        $path =  "/server/log/ecshop/error.log";
    }
    $fp = fopen($path,"a");
    flock($fp, LOCK_EX) ;
    fwrite($fp,"date:".date("Y-m-d H:m:s",time())."\n".$word."\n");
    flock($fp, LOCK_UN);
    fclose($fp);
}


/*
 * 删除目录及文件
 */
function delete_dir($dirName){
    if(is_dir($dirName)){
        if ( $handle = opendir( "$dirName" ) ) {
            while ( false !== ( $item = readdir( $handle ) ) ) {
                if ( $item != "." && $item != ".." ) {
                    if ( is_dir( "$dirName/$item" ) ) {
                        deleteDir( "$dirName/$item" );
                    } else {
                        unlink( "$dirName/$item" );
                    }
                }
            }
            closedir( $handle );
            if( rmdir( $dirName ) ) return true;
        }
    }
}


/**
 * 检查目录是否可写
 * @param  string   $path    目录
 * @return boolean
 */
function checkPath($path)
{
    if (is_dir($path)) {
        return true;
    }
    if (mkdir($path, 0755, true)) {
        return true;
    } else {
        return '目录创建失败！';
    }
}

/**
 * 获得唯一uuid值
 * @param string $sep 分隔符
 * @return string
 */
function create_uid($sep = ''){
    if (function_exists('com_create_guid')) {
        return com_create_guid();
    } else {
        mt_srand((double)microtime() * 10000);
        //optional for php 4.2.0 and up.
        $id = strtoupper(md5(uniqid(rand(), true)));
        $sep = '';
        // "-"
        $uuid = substr($id, 0, 8) . $sep . substr($id, 8, 4) . $sep . substr($id, 12, 4) . $sep . substr($id, 16, 4) . $sep . substr($id, 20, 12);
        return $uuid;
    }
}
/**调用接口函数
 * @param
 * @param
 * @return
 */
function api_request($url,$data,$method="GET",$opt="")
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_URL,$url);

    $method = strtoupper($method);
    if( $method == "POST" )
    {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    $content = curl_exec($ch);

    curl_close($ch);
    return $content;
}

/*
 * post提交
 * @param url
 * @param data
 * @return
 * */
function http_post($url,$data)
{

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5); //设置cURL允许执行的最长秒数
    $result = curl_exec($curl);
    if (curl_errno($curl)) {
        return 'Errno'.curl_error($curl);
    }
    curl_close($curl);
    return $result;
}


/**
 * get提交
 * @param url
 * @return
 */
function http_get($url) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
    curl_setopt($curl, CURLOPT_TIMEOUT, 5); //设置cURL允许执行的最长秒数
    $result = curl_exec($curl);
    //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
    curl_close($curl);

    return $result;
}

/*
 * 判断是否为微信浏览器
 */
function is_weixin(){
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    if (strpos($user_agent, 'MicroMessenger') === false) {
        // 非微信浏览器禁止浏览
        // echo "HTTP/1.1 401 Unauthorized";
        return false;
    } else {
        // 微信浏览器，允许访问
        // echo "MicroMessenger";
        // 获取版本号
        // preg_match('/.*?(MicroMessenger\/([0-9.]+))\s*/', $user_agent, $matches);
        // echo '<br>Version:'.$matches[2];
        return true;
    }
}

/**
 * 截取长度
 * 使用自定义标签时截取字符串
 * @param        $string 字符串
 * @param int $len 长度
 * @param string $end 结尾符
 * @return string
 */
function hd_substr($string, $len = 20, $end = '...')
{
    $con = mb_substr($string, 0, $len, 'utf-8');
    if ($con != $string) {
        $con .= $end;
    }
    return $con;
}
/*
 * curl模拟请求
 *
 */
function request_post($url = '', $post_data = array()) {
    if (empty($url) || empty($post_data)) {
        return false;
    }

    $o = "";
    foreach ( $post_data as $k => $v )
    {
        $o.= "$k=" . urlencode( $v ). "&" ;
    }
    $post_data = substr($o,0,-1);

    $postUrl = $url;
    $curlPost = $post_data;
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_TIMEOUT,10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch);//运行curl
    curl_close($ch);

    return $data;
}


/**
 * 增加操作日志
 * @param $operationtype 操作类型 1增加，2修改，3删除
 * @param $mune 操作菜单
 * @param string $remark 操作备注
 * @return bool
 */
function add_log($operationtype,$mune,$remark=''){
    if(empty($operationtype) || empty($mune)){
        return false;
    }
    $data=array(
        'adminid'=>$_SESSION['admin']['adminid'],
        'operationtype'=>$operationtype,
        'menu'=>$mune,
        'operationip'=>$_SERVER['REMOTE_ADDR'],
        'createtime'=>time(),
        'remark'=>$remark,
    );
    $CI =& get_instance();
    $CI->load->model("operation_log_model");
    return $CI->operation_log_model->save($data);
}
function page_redirect($url){
    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
    echo '<script>window.location.href="'.$url.'";</script>';
    exit;
}

/**
 * 获得客户端IP地址
 * @param int $type 类型
 * @return int
 */
function get_client_ip($type = 0) {
    $type = intval($type);
    $ip = '';
    //保存客户端IP地址
    if (isset($_SERVER)) {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif(isset($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif(isset($_SERVER["REMOTE_ADDR"])){
            $ip = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else {
            $ip = getenv("REMOTE_ADDR");
        }
    }
    $long = ip2long($ip);
    $clientIp = $long ? array($ip, $long) : array("0.0.0.0", 0);
    return $clientIp[$type];
}


/**
 * 获取6位随机数的一个方法：
 * @param
 * @param
 * @return
 */
function randstr($len=6) {

     $chars='abcdefghijklmnopqrstuvwxyz0123456789';

     mt_srand((double)microtime()*1000000*getmypid());

     $password='';

     while(strlen($password)<$len)

         $password.=substr($chars,(mt_rand()%strlen($chars)),1);

     return $password;

}

/**
 * 用户密码加密
 * @param $password 密码
 * @param $salt 加密随机数
 * @return
 */
function encryptPassword($password,$salt){
    if(empty($password)||empty($salt)) return false;
    return md5(md5($password).$salt);
}


/**
 * 用户密码加密
 * @param $password 密码
 * @param $salt 加密随机数
 * @return
 */
function pr($arr){
  echo '<pre>';
  print_r($arr);
  echo '</pre>';
}