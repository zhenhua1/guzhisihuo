<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller
{
    /**
     * 登陆
     */
    public function login()
    {
        //判断网站是否关闭
        $close=is_close_site();

      
        if($close['value']==0){
            $this->assign('message',$close['tip'])->display('closesite');
        }else{
            if(is_mobile()){
                
                $this->display();

            } else {

                $this->display('pc/login');
            }
            
        }
    }




    //注册
	/**GG Bond 更新2019.01.21**/
	public function register(){
		if(IS_AJAX){
			
			$u_yqm = trim(I('post.pid'));
			$sonelist = M('user')->where(array('u_yqm'=>$u_yqm))->find();
			if(empty($sonelist)){
				$re_data['status'] = 0;
				$re_data['message'] = "推荐人不存在！";				
				 $this->ajaxReturn($re_data);exit;
			}
			
			$username = trim(I('post.username'));
			$mobile = trim(I('post.mobile'));
			$login_pwd = trim(I('post.login_pwd'));
			//$safety_pwd = trim(I('post.safety_pwd'));
			$salt = strrand(4);
			$cuser= M('user')->where(array('account'=>$mobile))->find();
			$muser= M('user')->where(array('mobile'=>$mobile))->find();
			if(!empty($cuser) || !empty($muser)){
				$re_data['status'] = 1;
				$re_data['message'] = "手机号已经被注册";																			
				$this->ajaxReturn($re_data);exit;	
			}
			$data['pid'] = $sonelist['userid'];
			$data['gid'] = $sonelist['pid'];
			$data['ggid'] = $sonelist['gid'];
			$data['account'] = $mobile;
			$data['mobile'] = $mobile;
			$data['u_yqm'] = strrand();
			$data['username'] = $username;
			$data['login_pwd'] = pwd_md5($login_pwd,$salt);
			$data['login_salt'] = $salt;
			$data['reg_date'] = time();
			$data['reg_ip'] = get_userip();
			$data['status'] = 1;			
			$path=$sonelist['path'];      
            if(empty($path)){
                $data['path']='-'.$sonelist['userid'].'-';
            }else{
                $data['path']=$path.$sonelist['userid'].'-';
            }
			//$data['user_credit']= 5;
			$data['use_grade']= 1;
			$data['u_ztnum']= 0;	
			$data['tx_status']= 1;	
			
			$ure_re = M('user')->add($data);
			if($ure_re){
				if($sonelist['pid'] != '' || $sonelist['pid'] != 0){
					M('user')->where(array('userid'=>$sonelist['userid']))->setInc('u_ztnum',1);//增加会员直推数
				}
				$re_data['status'] = 1;
				$re_data['message'] = "注册成功!";																			
				$this->ajaxReturn($re_data);exit;		
			}else{
				$re_data['status'] = 1;
				$re_data['message'] = "网络错误";																			
				$this->ajaxReturn($re_data);exit;	
			}	
		}else{
			$yqm = I('get.mobile');
			if($yqm != ''){
				$this->assign('mobile',$yqm);
			}
			
			 $this->display();
			 
		}
	}
	
	

	//登陆
	/**GG Bond 更新2019.01.21**/
    public function checkLogin(){
        if (IS_AJAX) {
            $account = I('account');
            $password = I('password');
        
            // 验证用户名密码是否正确
            $user_object = D('Home/User');
            $user_info   = $user_object->login($account, $password);
            if (!$user_info) {
                ajaxReturn($user_object->getError(),0);
            }
            session('account',$account,86400);



             $user_info   = $user_object->Quicklogin($account);
            if (!$user_info) {
                ajaxReturn($user_object->getError(),0);
            }
            // 设置登录状态
            $uid = $user_object->auto_login($user_info);
            // 跳转
            if (0 < $uid && $user_info['userid'] === $uid) {
                session('in_time',time(),86400);
                ajaxReturn('登录成功',1,U('User/index'));
            }


        }
    }

    /**
     * 注销
     * 
     */
    public function logout()
    {   
        cookie('msg',null);
        session(null);
        $this->redirect('Login/login');
    }

    /**
     * 图片验证码生成，用于登录和注册
     * 
     */
    public function verify()
    {
        set_verify();
    }


    //找回密码
    public function getpsw(){
        
        $this->display();
    }

    public function setpsw(){
        if(!IS_AJAX)
            return ;

        $mobile=I('post.mobile');
        $wx_no=I('post.wx_no');
        $password=I('post.password');
        $reppassword=I('post.passwordmin');
        if(empty($mobile)){
            ajaxReturn('手机号码不能为空');
        }
        if(empty($password)){
            ajaxReturn('密码不能为空');
        }
        if($password  != $reppassword){
            ajaxReturn('两次输入的密码不一致');
        }

      

        $user=D('User');
        $mwhere['mobile']=$mobile;
        $userid=$user->where($mwhere)->getField('userid');
        if(empty($userid)){
            ajaxReturn('手机号码错误或不在系统中');
        }
		$codewhere['alipay']=$wx_no;


		$where['_logic'] = 'or';
		$map['_complex'] = $codewhere;
		$map['mobile']=$mobile;

        $user=D('User');
        $codeuserid=$user->where($map)->getField('userid');
		if(empty($codeuserid)){
            ajaxReturn('已绑定的微信号/支付宝不存在');
        }
        $where['userid']=$userid;
        //密码加密
        $salt=user_salt();
        $data['login_pwd']=$user->pwdMd5($password,$salt);
        $data['login_salt']=$salt;
        $res=$user->field('login_pwd,login_salt')->where($where)->save($data);
        if($res){
            session('sms_code',null);
            ajaxReturn('修改成功',1,U('Login/logout'));
        }
        else{
            ajaxReturn('修改失败');
        }

    }




}
