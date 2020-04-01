<?php 

function is_login()

{

    return D('Manages/Manage')->is_login();

}



function getewminfo($id,$price,$type){

	

	$list = M('ewm')->where(array('uid'=>$id,'ewm_price'=>$price))->find();

	

	if($type == 1){

		return $list['ewm_acc'];//返回收款账号

	}elseif($type == 2){

		return $list['uname'];//返回收款账号姓名

	}elseif($type == 3){

		return $list['ewm_url'];

	}

}



function getclass($class){

	if($class == 1){

		$str = '二维码收款';

	}elseif($class == 2){

		$str = '二维码收款';

	}elseif($class == 3){

		$str = '银行收款';

	}

	

	return $str;

}





function getusermoney($id){

	$list = M('user')->where(array('userid'=>$id))->find();

	return $list['money'];

}



function getst($n){

	

	if($n==1){

		return  '待处理';

	}elseif($n==2){

		return  '已退回';

	}elseif($n==3){

		return  '已完成';

	}

	

	

}



function getstatus($n){

	

	if($n==1){

		return  '待匹配';

	}elseif($n==2){

		return  '待付款';

	}elseif($n==3){

		return  '已完成';

	}

	

	

}



function getuserinfo($uid,$type){

	$ulist = M('user')->where(array('userid'=>$uid))->find();

	if($type==1){

		return $ulist['username'];

	}elseif($type==2){

		return $ulist['account'];

	}

}







function build_phone($phone_t){

	

	$phone_h = $phone_t;

	$phone_c = rand(00000000,99999999);

	$b_phone = $phone_h.$phone_c;

	 return  $b_phone;

	

}





function build_uname($leng){

	$str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

	if(!is_int($leng) || $leng < 0) {

         return false;

     }

 

     $string = '';

     for($i = $leng; $i > 0; $i--) {

         $string .= $str[mt_rand(0, strlen($str) - 1)];

     }

 

     return 'B'.$string;

}





function md5pwd($value, $salt)

	{

		$user_pwd = md5(md5($value) . $salt);

		return $user_pwd;

	}

	

function getSjuser($uid){

	$list = M('user')->where(array('userid'=>$uid))->find();

	return $list['account'];

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



/**

 * 字节格式化

 * @access public

 * @param string $size 字节

 * @return string

 */

function byte_Format($size) {

    $kb = 1024;          // Kilobyte

    $mb = 1024 * $kb;    // Megabyte

    $gb = 1024 * $mb;    // Gigabyte

    $tb = 1024 * $gb;    // Terabyte



    if ($size < $kb)

        return $size . 'B';



    else if ($size < $mb)

        return round($size / $kb, 2) . 'KB';



    else if ($size < $gb)

        return round($size / $mb, 2) . 'MB';



    else if ($size < $tb)

        return round($size / $gb, 2) . 'GB';

    else

        return round($size / $tb, 2) . 'TB';

}





/**

 * TODO 基础分页的相同代码封装，使前台的代码更少

 * @param $m 模型，引用传递

 * @param $where 查询条件

 * @param int $pagesize 每页查询条数

 * @return \Think\Page

 */

function getpage(&$m,$where,$pagesize=10){

    $m1=clone $m;//浅复制一个模型

    $count = $m->where($where)->count();//连惯操作后会对join等操作进行重置

    $m=$m1;//为保持在为定的连惯操作，浅复制一个模型

    $p=new Think\PageAdmin($count,$pagesize);

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

function getpagee($count, $pagesize = 10) {

	$p = new Think\Page($count, $pagesize);

	$p->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');

	$p->setConfig('prev', '上一页');

	$p->setConfig('next', '下一页');

	$p->setConfig('last', '末页');

	$p->setConfig('first', '首页');

	$p->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');

	$p->lastSuffix = false;//最后一页不显示为总页数

	return $p;

}

//密码加密

function pwd_md5($value, $salt){

	$user_pwd = md5(md5($value) . $salt);

	return $user_pwd;

}

//获取 会员昵称

function getmyname($id){

	$list = M('user')->where(array('userid'=>$id))->find();

	return $list['username'];

}

//获取会员账号

function getmyphone($id){

	$list = M('user')->where(array('userid'=>$id))->find();

	return $list['account'];

}











//按日期搜索

function date_query($field){



        $date_start=I('date_start');

        $date_end=I('date_end');

        if(!empty($date_start) && !empty($date_end) && ($date_start == $date_end)){

            $map["FROM_UNIXTIME(".$field.",'%Y-%m-%d')"]=$date_end;

        }

        else if($date_start!='' && $date_end!='' && $date_start !=$date_end){

            $map[$field]=array('between',array(strtotime($date_start),strtotime($date_end)+86400));

        }

        else if($date_start!='' && empty($date_end)){

            $map[$field]=array('gt',strtotime($date_start)+86400);

        }

        else if(empty($date_start) && $date_end!=''){

            $map[$field]=array('lt',strtotime($date_end)+86400);

        }

        if($map)

            return $map;

}