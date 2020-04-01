<?php 
function is_mobile()
{
    static $is_mobile;
  
  	//自己加的，阻止pc端运行
    return $is_mobile = true;

    if (isset($is_mobile)) {
        return $is_mobile;
    }

    if (empty($_SERVER['HTTP_USER_AGENT'])) {
        $is_mobile = false;
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false
    ) {
        $is_mobile = true;
    } else {
        $is_mobile = false;
    }

    return $is_mobile;
}

function re_week(){	
	$da = @date("w");
	switch( $da ){ 
		case 1 : return 1;break; 
		case 2 : return 1;break; 
		case 3 : return 1;break; 
		case 4 : return 1;break; 
		case 5 : return 1;break; 
		case 6 : return 2;break; 
		case 0 : return 2;break; 
		default : return 2; 
	}; 
}

//返回提现时间状态，上午8点至下午17点返回1，否则返回2
function re_txhour(){
	
	$ti = @date('H:i:s',time());
	$arr = explode(':',$ti);
	$hour = $arr[0];
	
	if($hour>=8 && $hour < 17){
		return 1;
	}else{
		return 2;
	}
	
}

function getclass($class){
	if($class == 1){
		$str = '微信收款';
	}elseif($class == 2){
		$str = '支付宝收款';
	}elseif($class == 3){
		$str = '银行收款';
	}
	
	return $str;
}

function getstatus($n){
	
	if($n==1){
		return  '挂单中';
	}elseif($n==2){
		return  '待付款';
	}elseif($n==3){
		return  '已完成';
	}
	
	
}



function setUpload($file){
    $upload = new \Think\Upload();// 实例化上传类
    $upload->maxSize   =     31457280 ;// 设置附件上传大小
    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    $upload->rootPath  =     './Uploads/ewm/'; // 设置附件上传根目录
    $upload->savePath  =     ''; // 设置附件上传（子）目录
    // 上传文件 
    $info   =   $upload->upload();
    if(!$info) {// 上传错误提示错误信息
        return $upload->getError();
    }else{// 上传成功
        return $upload->rootPath.$info['pic']['savepath'].$info['pic']['savename'];
    }
}







//获取设备IP
function get_userip(){
    //判断服务器是否允许$_SERVER
    if(isset($_SERVER)){    
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }elseif(isset($_SERVER['HTTP_CLIENT_IP'])) {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        }else{
            $realip = $_SERVER['REMOTE_ADDR'];
        }
    }else{
        //不允许就使用getenv获取  
        if(getenv("HTTP_X_FORWARDED_FOR")){
              $realip = getenv( "HTTP_X_FORWARDED_FOR");
        }elseif(getenv("HTTP_CLIENT_IP")) {
              $realip = getenv("HTTP_CLIENT_IP");
        }else{
              $realip = getenv("REMOTE_ADDR");
        }
    }
    return $realip;
} 
//密码加密
function pwd_md5($value, $salt){
	$user_pwd = md5(md5($value) . $salt);
	return $user_pwd;
}

/*随机生成邀请码*/
function strrand($islt=0, $char = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
	$string = '';
    if($islt != 0){
        for($i = 4; $i > 0; $i--) {
            $string .= $char[mt_rand(0, strlen($char) - 1)];
        }
    }else{
        $str = '';
        $str.= substr(time(), -6,6);
        $str.= rand(01,99);
        for($i = 4; $i > 0; $i--) {
            $str .= $char[mt_rand(0, strlen($char) - 1)];
        }

        $string = str_shuffle($str);
    }
    return $string;
}
/*随机生成订单号*/
function getordernum($length = 12, $char = '0123456789') {
	if(!is_int($length) || $length < 0) {
         return false;
    }
     $string = '';
    for($i = $length; $i > 0; $i--) {
         $string .= $char[mt_rand(0, strlen($char) - 1)];
    }
     return 'N'.$string;
}

//h后台分页
function getpage(&$m,$where,$pagesize=10){
    $m1=clone $m;//浅复制一个模型
    $count = $m->where($where)->count();//连惯操作后会对join等操作进行重置
    $m=$m1;//为保持在为定的连惯操作，浅复制一个模型
    $p=new Think\Page($count,$pagesize);
    $p->lastSuffix=false;
    $p->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
    $p->setConfig('prev','上一页');
    $p->setConfig('next','下一页');
    $p->setConfig('last','末页');
    $p->setConfig('first','首页');
    
    $p->parameter=I('get.');

    $m->limit($p->firstRow,$p->listRows);

    return $p;
}

/**
 * 下载远程文件
 * @param  string  $url     网址
 * @param  string  $filename    保存文件名
 * @param  integer $timeout 过期时间
 * return boolean|string
 */
function http_down($url, $filename, $timeout = 60) {
    $path = dirname($filename);
    if (!is_dir($path) && !mkdir($path, 0755, true)) {
        return false;
    }
    $fp = fopen($filename, 'w');
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
    return $filename;
}

//oss上传 
/* 
 *$fFiles:文件域 
 *$n：上传的路径目录 
 *$ossClient   
 *$bucketName 
 *$web:oss访问地址 
 *$isThumb:是否缩略图 
 */ 

function ossUpPic($fFiles,$n,$ossClient,$bucketName,$web,$isThumb=1){ 

	$fType=$fFiles['type']; 
    $back=array( 
        'code'=>0, 
        'msg'=>'', 
    ); 
	
    if(!in_array($fType, C('oss_exts'))){ 
        $back['msg']='文件格式不正确a'; 
        return $back; 
        exit; 
    } 
    $fSize=$fFiles['size']; 
    if($fSize>C('oss_maxSize')){ 
        $back['msg']='文件超过了1M'; 
        return $back; 
        exit; 
    } 
     
    $fname=$fFiles['name']; 
    $ext=substr($fname,stripos($fname,'.')); 
     
    $fup_n=$fFiles['tmp_name']; 
    //$file_n=time().'_'.rand(100,999); 
    $file_n=substr(md5_file($fup_n), 8, 16); 
    $object = $n."/".date('Ymd')."/".$file_n.$ext;//目标文件名 
     
 
    if (is_null($ossClient)) exit(1);     
    $ossClient->uploadFile($bucketName, $object, $fup_n); 
       
    $back['code']=1; 
    $back['url']=$web."/".$object; 
	if($isThumb==1){ 
        // 图片缩放，参考https://help.aliyun.com/document_detail/44688.html?spm=5176.doc32174.6.481.RScf0S  
        $back['url']=$web."/".$object.C('OSS_PICEND');  
    }else{
		$back['url']=$web."/".$object;
	}
    return $back; 
    exit;     
}


function mkdirs($dir, $mode = 0777)
{
    if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
    if (!mkdirs(dirname($dir), $mode)) return FALSE;
    return @mkdir($dir, $mode);
} 



//前台分页

function Fgetpage(&$m,$where,$pagesize=10){
    $m1=clone $m;//浅复制一个模型
    $count = $m->where($where)->count();//连惯操作后会对join等操作进行重置
    $m=$m1;//为保持在为定的连惯操作，浅复制一个模型
    $p=new Think\Page($count,$pagesize);
    $p->lastSuffix=false;
    $p->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
    $p->setConfig('prev','上一页');
    $p->setConfig('next','下一页');
    $p->setConfig('last','末页');
    $p->setConfig('first','首页');

    $p->parameter=I('get.');

    $m->limit($p->firstRow,$p->listRows);

    return $p;
}

//生成唯一订单
function build_order_no()
{
    $no = 'PAY' . date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    //检测是否存在
    $db = M('trans', 'ysk_');
    $count = $db->where(array('pay_no' => $no))->count(1);
    ($count > 0) && $no = build_order_no();
    return $no;
}

//生成唯一订单
function build_wallet_add()
{
    // 密码字符集，可任意添加你需要的字符
    $chars = 'abcdefghijklmuvwxyzABCDNOPQRSTUVWXYZ0123456';
    $password = "";
    for ( $i = 0; $i < 34; $i++ )
    {
        $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }
    //检测是否存在
    $db = M('user', 'ysk_');
    $count = $db->where(array('wallet_add' => $password))->count(1);
    ($count > 0) && $no = build_wallet_add();
    return $password;
}

/**
* 验证手机号是否正确
* @author 陶
* @param $mobile
*/
function isMobile($mobile) {
    if (!is_numeric($mobile)) {
        return false;
    }
    return preg_match('#^1[34578]\d{9}$#', $mobile) ? true : false;
}

/**
 * 检测用户是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 * @author jry <598821125@qq.com>
 */
function user_login()
{
    return D('Home/user')->user_login();
}

function get_userid(){
	$userid =session('userid');
	return $userid;
}






function AddUserLevel($uid){
  $where['uid']=$uid;
  //直推人数
  $table=M('user_level');
  $info=$table->where($where)->field('children_num,land_num,user_level')->find();
  $children_count=$info['children_num'];
  $land_count=$info['land_num'];

  if($land_count>=1){
    $level=1;
  }
  if($land_count>=10 && $children_count>=10){
    $level=2;
  }
  if($land_count>=15 && $children_count>=20){
    $level=3;
  }
  if($land_count>=15 && $children_count>=30){
    $level=4;
  }
  if($land_count>=15 && $children_count>=40){
    $level=5;
  }
  if($land_count>=15 && $children_count>=60){
    $level=5;
  }
  //低等级才修改
  if($level && $info['user_level']<$level){
    $table->where($where)->setField('user_level',$level);
  }

}


/**
 * [SearchDate 获取上周的还是时间和结束时间]
 */
function SearchDate(){
        $date=date('Y-m-d');  //当前日期
        $first=1; //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
        $w=date('w',strtotime($date));  //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
        $now_start=date('Y-m-d',strtotime("$date -".($w ? $w - $first : 6).' days')); //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
        $last_start=strtotime("$now_start - 7 days");  //上周开始时间
        $last_end=strtotime("$now_start - 1 days");  //上周结束时间
        //获取上周起始日期
        $arr['week_start'] = $last_start;
        $arr['week_end'] = strtotime($now_start);//本周开始时间,即上周最后时间
        return $arr;
}

function img_uploading($path_old=null)
{    
    $images_path='./Uploads/';   
    if (!is_dir($images_path)) {
        mkdir($images_path);
    }

        $upload             = new \Think\Upload();// 实例化上传类    
        $upload->maxSize    =     3145728 ;// 设置附件上传大小    
        $upload->exts       =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型    
        $upload->rootPath   =      $images_path; // 设置上传根目录    // 上传文件     
        $upload->savePath   =      ''; // 设置上传子目录    // 上传文件     
        $info               =   $upload->upload();
        dump($info);echo 132;exit;
            if(!$info)
            {// 上传错误提示错误信息
                $res['status']=0;        
                $res['res']=$upload->getError();
            }
            else
            {// 上传成功 
                foreach($info as $file){ 
                       $img_path = $file['savepath'].$file['savename'];
                }
                //上传成功后删除原来的图片
                if($path_old && $img_path)
                {
                    unlink('.'.$path_old);
                   // echo '删除成功';
                }
                $res['status']=1;
                $res['res']='/Uploads/'.$img_path;
            }
        return $res;
}





function getCode() {
    return  rand(100000,999999);
}

function check_code($value,$send_email){
    $time=session('set_time');
    $email=session('user_email');
    $code=session('sms_code');
    if(time() - $time > 600 ||  $code !=  $value  || $code == '' || $email != $send_email ){
       return false;
    }
    return true;
}











//验证短信验证码
function check_sms($code, $mobile)
{
    $md5_code = sha1(md5(trim($code) . trim($mobile)));
    $set_time = session('set_time');
    $sms_code = session('sms_code');
    if (time() - $set_time <= 300 && $code != '' && $md5_code == $sms_code) {
        $res = true;
    } else {
        $res = false;
    }
    return $res;
}


function curlPost($url,$postFields){
    $postFields = json_encode($postFields);
    $ch = curl_init ();
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8'
        )
    );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_POST, 1 );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt( $ch, CURLOPT_TIMEOUT,10);
    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
    $ret = curl_exec ( $ch );
    if (false == $ret) {
        $result = curl_error(  $ch);
    } else {
        $rsp = curl_getinfo( $ch, CURLINFO_HTTP_CODE);
        if (200 != $rsp) {
            $result = "请求状态 ". $rsp . " " . curl_error($ch);
        } else {
            $result = $ret;
        }
    }
    curl_close ( $ch );
    return $result;
}



function add_seed($num,$uid){

   $table=M('user_seed');
   $where['uid']=$uid;
   $count=$table->where($where)->count(1);
   if($count==0){
      $data['uid']=$uid;
      $data['zhongzi_num']=$num;
      return $table->where($where)->add($data);
   }

  return $table->where($where)->setInc('zhongzi_num',$num);
}

//获取当前用户的父级
function parent_account(){
    $userid=session('userid');
    $user=D('User');
    $pid=$user->where(array('userid'=>$userid))->getField('pid');
    $account=$user->where(array('userid'=>$pid))->getField('account');
    if($account)
        return $account;
    else
        return '无';
}

//数字只显示两位
function Showtwo($nums){
    $nums = floor($nums*100)/100;
    return $nums;
}
