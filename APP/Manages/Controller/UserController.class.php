<?php

namespace Manages\Controller;

use Think\Page;


/**
 * 用户控制器
 * 
 */
class UserController extends AdminController
{
    /**
     * 用户列表
     * 
     */
    public function index(){
		$querytype = trim(I('get.querytype'));
		$account = trim(I('get.keyword'));
		$coinpx = trim(I('get.coinpx'));
		if($querytype != ''){
			if($querytype=='mobile'){
				$map['account'] = $account;
			}elseif($querytype=='userid'){
				$map['userid'] = $account;
			}
		}else{
			$map = '';
		}
		$userobj = M('user');
		$count =$userobj->where($map)->count();
		$p = getpagee($count,50);
		 if($coinpx){
			if($coinpx == 1){
				$list = $userobj->where($map)->order('money desc')->limit($p->firstRow, $p->listRows)->select();
			}
		}else{
			$list = $userobj->where ($map)->order ('userid desc')->limit ( $p->firstRow, $p->listRows)->select();
		}

		$this->assign('count',$count);

    	$this->assign ( 'list', $list ); // 賦值數據集

		$this->assign('count',$count);

    	$this->assign ( 'page', $p->show() ); // 賦值分頁輸出

        $this->display();

    }

	

	//流水

	public function bill(){

		 $querytype = trim(I('get.querytype'));

		 $account = trim(I('get.keyword'));

		 $coinpx = trim(I('get.coinpx'));

		 if($querytype != ''){

			 if($querytype=='mobile'){

				 $map['account'] = $account;

			 }elseif($querytype=='userid'){

				  $map['uid'] = $account;

			 }

		 }else{

			 $map = '';

		 }

		$userobj = M('somebill');

		$count =$userobj->where($map)->count();

		$p = getpagee($count,15);

		if($coinpx){
			if($coinpx == 1){
				$list = $userobj->where ( $map )->order ( 'money desc' )->limit ( $p->firstRow, $p->listRows )->select ();
			}
		}else{
			$list = $userobj->where ( $map )->order ( 'uid desc' )->limit ( $p->firstRow, $p->listRows )->select ();
		}

		$this->assign('count',$count);

    	$this->assign ( 'list', $list ); // 賦值數據集

		$this->assign('count',$count);

    	$this->assign ( 'page', $p->show() ); // 賦值分頁輸出

        $this->display();

    }

	public function delbill(){
		$id=trim(I('get.id'));
		$re = M('somebill')->where(array('id'=>$id))->delete();
		if($re){
			$this->success('删除成功');exit;
		}else{
			$this->error('删除失败');exit;
		}
	}

	// 奖励
	public function jl(){
		$id = I('post.id','','intval');
		$money = I('post.money','','floatval');
		$status = M('User')->where(array('id'=>$id))->setInc('money',$money);
		if ($status) {
			$this->ajaxReturn(array('code'=>200));
		}else{
			$this->ajaxReturn(array('code'=>500));
		}
	}
	
	//提现列表
	public function recharge(){
		$querytype = trim(I('get.querytype'));
		$account = trim(I('get.keyword'));
		$coinpx = trim(I('get.coinpx'));
		if($querytype != ''){
			if($querytype=='mobile'){
				$map['account'] = $account;
			}elseif($querytype=='userid'){
				$map['uid'] = $account;
			}
		}else{
			$map = '';
		}
		$userobj = M('recharge');
		$count =$userobj->where($map)->count();
		$p = getpagee($count,50);
		if($coinpx){
			if($coinpx == 1){
				$list = $userobj->where ( $map )->order ( 'price desc' )->limit ( $p->firstRow, $p->listRows )->select ();
			}else{
				$list = $userobj->where ( $map )->order ( 'id desc' )->limit ( $p->firstRow, $p->listRows )->select ();

			 }

		 }else{

			 $list = $userobj->where ( $map )->order ( 'id desc' )->limit ( $p->firstRow, $p->listRows )->select ();

		 }

    	

		$conf = M('system')->where(array('id'=>1))->find();

		$this->assign('conf',$conf);

		

		$this->assign('count',$count);

    	$this->assign ( 'list', $list ); // 賦值數據集

		$this->assign('count',$count);

    	$this->assign ( 'page', $p->show() ); // 賦值分頁輸出

        $this->display();



	}

	

	//充值处理

	public function reedit(){

		$id = trim(I('get.id'));

		$st = trim(I('get.st'));

		$relist  = M('recharge')->where(array('id'=>$id))->find();

		$ulist = M('user')->where(array('userid'=>$relist['uid']))->find();

		

		if($st ==1){

			if($relist['status'] == 1){

				$re = M('recharge')->where(array('id'=>$id))->save(array('status'=>3));

				$ure = M('user')->where(array('userid'=>$relist['uid']))->setInc('money',$relist['price']);

			}else{

				$re = 0;

				$ure =0;

			}

		}elseif($st ==2){

			if($relist['status'] == 1){

				$re = M('recharge')->where(array('id'=>$id))->save(array('status'=>2));

				$ure = 1;

			}else{

				$re = 0;

				$ure =0;

			}

		}elseif($st ==3){

			if($relist['status'] == 3){

				$re = M('recharge')->where(array('id'=>$id))->delete();

				$ure = 1;

			}else{

				$re = 0;

				$ure =0;

			}

		}

		

		if($re && $ure){

			$this->success('操作成功');

		}else{

			$this->error('操作失败');

		}

	}
	

	//充值处理

	public function save_czset(){
		if(IS_POST){
	        if($_FILES['qrcode']['error'] != 4){
	            $upload = new \Think\Upload();// 实例化上传类  
	            $upload->maxSize   =     5145728 ;// 设置附件上传大小 
	            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型  
	            $upload->savePath  =      './'; // 设置附件上传目录   
	            // 上传文件    
	            $info   =   $upload->upload();  
	            if(!$info) {// 上传错误提示错误信息  
	                $this->error($upload->getError());  
	            }else{// 上传成功  
	                $cover= $info['qrcode']['savepath'].$info['qrcode']['savename'];
	                $qrcode = '/Uploads'.ltrim($cover,'.');
	                $data['qrcode'] = $qrcode;
	            }
	        }
			$data['cz_yh'] = trim(I('post.cz_yh'));
			$data['cz_xm'] = trim(I('post.cz_xm'));
			$data['cz_kh'] = trim(I('post.cz_kh'));
			$re = M('system')->where(array('id'=>1))->save($data);
			if($re){
				$this->success('修改成功');exit;
			}else{
				$this->error('修改失败');exit;
			}
		}
	}


	//提现列表

	public function withdraw(){

		 $querytype = trim(I('get.querytype'));

		 $account = trim(I('get.keyword'));

		 $coinpx = trim(I('get.coinpx'));



		 if($querytype != ''){

			 if($querytype=='mobile'){

				 $map['account'] = $account;

			 }elseif($querytype=='userid'){

				  $map['uid'] = $account;

			 }

		 }else{

			 $map = '';

		 }
		

		$userobj = M('withdraw');

		$count =$userobj->where($map)->count();

		$p = getpagee($count,50);


		 if($coinpx){

			 if($coinpx == 1){

				  $list = $userobj->where ( $map )->order ( 'price desc' )->limit ( $p->firstRow, $p->listRows )->select ();

			 }else{

				 $list = $userobj->where ( $map )->order ( 'id desc' )->limit ( $p->firstRow, $p->listRows )->select ();

			 }

		 }else{

			 $list = $userobj->where ( $map )->order ( 'id desc' )->limit ( $p->firstRow, $p->listRows )->select ();

		 }

		$this->assign('count',$count);

    	$this->assign ( 'list', $list ); // 賦值數據集

		$this->assign('count',$count);

    	$this->assign ( 'page', $p->show() ); // 賦值分頁輸出

        $this->display();

	}


	//提现处理

	public function wiedit(){

		$id = trim(I('get.id'));

		$st = trim(I('get.st'));

		$relist  = M('withdraw')->where(array('id'=>$id))->find();
	
		if($st ==1){

			$re = M('withdraw')->where(array('id'=>$id))->save(array('status'=>3));

		}elseif($st ==2){

			$withdraw = M('withdraw')->where(array('id'=>$id,'status'=>'1'))->find();

			$re = M('withdraw')->where(array('id'=>$id))->save(array('status'=>2));

			if($withdraw)

			{

				$a= M('user')->where(array('userid'=>$withdraw['uid']))->setInc('money',$withdraw['price']);

			}

		}elseif($st ==3){
			$re = M('withdraw')->where(array('u'=>$id))->save(array('status'=>3));
		}

		if($re){

			$this->success('操作成功');

		}else{

			$this->error('操作失败');

		}

	}


	//提现列表

	public function ewm(){

		 $querytype = trim(I('get.querytype'));

		 $account = trim(I('get.keyword'));

		 $coinpx = trim(I('get.coinpx'));



		 if($querytype != ''){

			 if($querytype=='mobile'){

				 $map['uaccount'] = $account;

			 }elseif($querytype=='userid'){

				  $map['uid'] = $account;

			 }

		 }else{

			 $map = '';

		}

		$userobj = M('ewm');

		$count =$userobj->where($map)->count();

		$p = getpagee($count,50);

		 if($coinpx){

			 if($coinpx == 1){

				  $list = $userobj->where ( $map )->order ( 'ewm_price desc' )->limit ( $p->firstRow, $p->listRows )->select ();

			 }else{

				 $list = $userobj->where ( $map )->order ( 'id desc' )->limit ( $p->firstRow, $p->listRows )->select ();

			 }

		 }else{

			 $list = $userobj->where ( $map )->order ( 'id desc' )->limit ( $p->firstRow, $p->listRows )->select ();

		 }


		$this->assign('count',$count);

    	$this->assign ( 'list', $list ); // 賦值數據集

		$this->assign('count',$count);

    	$this->assign ( 'page', $p->show() ); // 賦值分頁輸出

        $this->display();

	}


	//二维码详情

	public function ewminfo(){		

		$id= trim(I('get.id'));

		$ewminfo = M('ewm')->where(array('id'=>$id))->find();

		$this->assign('info',$ewminfo);

		$this->display();

	}
	

	//删除二维码

	public function delewm(){

		$id= trim(I('get.id'));

		$re = M('ewm')->where(array('id'=>$id))->delete();

		if($re){

			$this->success('删除成功');

		}else{

			$this->error('删除失败');

		}

	}
	

	//银行卡列表

	public function bankcard(){

		 $querytype = trim(I('get.querytype'));

		 $account = trim(I('get.keyword'));

		 $coinpx = trim(I('get.coinpx'));



		 if($querytype != ''){

			 if($querytype=='mobile'){

				 $map['name'] = $account;

			 }elseif($querytype=='userid'){

				  $map['uid'] = $account;

			 }

		 }else{

			 $map = '';

		 }
		

		$userobj = M('bankcard');

		$count =$userobj->where($map)->count();

		$p = getpagee($count,50);
		

		 if($coinpx){

			 if($coinpx == 1){

				  $list = $userobj->where ( $map )->order ( 'addtime desc' )->limit ( $p->firstRow, $p->listRows )->select ();

			 }else{

				 $list = $userobj->where ( $map )->order ( 'id desc' )->limit ( $p->firstRow, $p->listRows )->select ();

			 }

		 }else{

			 $list = $userobj->where ( $map )->order ( 'id desc' )->limit ( $p->firstRow, $p->listRows )->select ();

		 }


		$this->assign('count',$count);

    	$this->assign ( 'list', $list ); // 賦值數據集

		$this->assign('count',$count);

    	$this->assign ( 'page', $p->show() ); // 賦值分頁輸出

        $this->display();

	}
	

	public function delbankcard(){

		$id= trim(I('get.id'));

		$re = M('bankcard')->where(array('id'=>$id))->delete();

		if($re){

			$this->success('删除成功');

		}else{

			$this->error('删除失败');

		}

	}


	//冻结会员

	public function set_status(){

		if($_GET){

			$userid = trim(I('get.userid'));

			$st = trim(I('get.st'));

			$list = M('user')->where(array('userid'=>$userid))->find();

			if(empty($list)){

				$this->error('该会员不存在');

			}

			if($st == 1){

				$re = M('user')->where(array('userid'=>$userid))->save(array('status'=>0));

				if($re){

					$this->error('该会员已被冻结');

				}else{

					$this->error('网络错误！');

				}

				

			}elseif($st == 2){

				$re = M('user')->where(array('userid'=>$userid))->save(array('status'=>1));

				if($re){

					$this->error('该会员已被解冻');

				}else{

					$this->error('网络错误！');

				}

			}else{

				$this->error('网络错误！');

			}


		}else{

			$this->error('网络错误！');

		}


	}


    /**
     * 编辑用户
     * 
     */

    public function edit(){

		$userid = trim(I('get.userid'));

		$ulist = M('user')->where(array('userid'=>$userid))->find();

		if($_POST){

			$data['username'] = trim(I('post.username'));

			$data['mobile'] = trim(I('post.mobile'));

			$data['truename'] = trim(I('post.truename'));

			$data['wx_no'] = trim(I('post.wx_no'));

			$data['alipay'] = trim(I('post.alipay'));

			$data['nsc_money'] = trim(I('post.nsc_money'));

			$data['eth_money'] = trim(I('post.eth_money'));

			$data['eos_money'] = trim(I('post.eos_money'));

			$data['btc_money'] = trim(I('post.btc_money'));

			$data['money'] = trim(I('post.money'));

			$data['adj_point'] = trim(I('post.adj_point'));

			$login_pwd = trim(I('post.login_pwd'));

			
			if($login_pwd != ''){

				$data['login_pwd'] = pwd_md5($login_pwd,$ulist['login_salt']);

			}

			$safety_pwd = trim(I('post.safety_pwd'));

			if($login_pwd != ''){
				$data['safety_pwd'] = pwd_md5($safety_pwd,$ulist['safety_salt']);
			}
			
			$re = M('user')->where(array('userid'=>$userid))->save($data);

			if($re !== false){

				$this->success('资料修改成功');

			}else{

				$this->error('资料修改失败');

			}

		}else{

			$this->assign('info',$ulist);

			$this->display();

		}

    }


    /**
     * 编辑用户
     * 
     */

    public function del(){

		$userid = trim(I('get.userid'));

		M('user')->where(array('userid'=>$userid))->delete();

		$this->success('会员删除成功');

    }

	

	//限制出售币和提币

	public function restrict(){

		$userid = trim(I('get.userid'));

		$ulist = M('user')->where(array('userid'=>$userid))->find();

		if($_POST){

			

			$sell_status = trim(I('post.sell_status'));

			

			$tx_status = trim(I('post.tx_status'));

			

			$zz_status = trim(I('post.zz_status'));

			

			if($ulist['sell_status'] == 1){

				

				if($sell_status != ''){

					$data['sell_status'] = 0;

				}

			}else{

				if($sell_status != ''){

					$data['sell_status'] = 1;

				}

			}

			if($ulist['tx_status'] == 1){

				if($tx_status != ''){

					$data['tx_status'] = 0;

				}

			}else{

				if($tx_status != ''){

					$data['tx_status'] = 1;

				}

			}

			

			if($ulist['zz_status'] == 1){

				

				if($zz_status != ''){

					$data['zz_status'] = 0;

				}

			}else{

				if($zz_status != ''){

					$data['zz_status'] = 1;

				}

			}

			$re = M('user')->where(array('userid'=>$userid))->save($data);

			if($re){
				$this->success('修改成功');
			}else{
				$this->error('修改失败');
			}

		}else{

			$this->assign('info',$ulist);

			$this->display();

		}

	}


    /**
     * 设置一条或者多条数据的状态
     * 
     */
    public function setStatus($model = CONTROLLER_NAME){
  

    }





	/**
     * 设置会员隐蔽的状态
     * 
     */
    public function setStatus1($model = CONTROLLER_NAME)
    {
        $id =(int)I('request.id');    
        $userid =(int)I('request.userid');    
        $user_object = D('User');    
        $result=D('User')->where(array('userid'=>$userid))->setField('yinbi',$id);
        if ($result) {
           $this->success('更新成功', U('index'));
        }else {
            $this->error('更新失败', $user_object->getError());
        }
    }

	
    //用户登录
    public function userlogin()
    {
        $userid=I('userid',0,'intval');
        $user=D('Home/User');
        $info=$user->find($userid);
        if(empty($info)){
            return false;
        }
        $login_id=$user->auto_login($info);
        if($login_id){
            session('in_time',time());
            session('login_from_admin','admin',10800);
            $this->redirect('Home/Index/index');
        }
    }
}