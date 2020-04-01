<?php

namespace Home\ Controller;

use Think\Controller;

class RechargeController extends CommonController {



	//充值记录管理

    public function chongzhijilu(){

		$uid = session('userid');

		$relist = M('recharge')->where(array('uid'=>$uid,'status'=>1))->order('id desc')->select();

		

	    $this->assign('relist',$relist);



        if(is_mobile()){

                

            $this->display();



        } else {

        

            $this->display('pc/tables');

        }



    }

	

	//充值方式

	public function chongzhi(){

		

	

		if(is_mobile()){

                

            $this->display();



        } else {

        

            $this->display('pc/chongzhis');

        }

	}

	

	//充值方式

	public function yhkcz(){

		

		$conf = M('system')->where(array('id'=>1))->find();

		$this->assign('conf',$conf);

		$this->display();

	}

	

	//支付宝充值页面

	public function zfbcz(){

		$conf = M('system')->field('qrcode')->where(array('id'=>1))->find();

		$this->assign('conf',$conf);

		$this->display();

	}

	

	//从支付宝充值提交

	public function alipay_rc(){

		if($_POST){

			$uid = session('userid');

			$account = trim(I('post.account'));

			$rlist = M('user')->where(array('account'=>$account))->find();

			if(empty($rlist)){

				$data['status'] = 0;

				$data['msg'] = '该会员不存在';

				$this->ajaxReturn($data);exit;

			}

			$type = 1;

			$arr = I('post.');

			$st = $this->rc_up($type,$uid,$arr);

			if($st ==1){

				$data['status'] = 1;

				$data['msg'] = '充值提交成功';

				$this->ajaxReturn($data);exit;

			}else{

				$data['status'] = 0;

				$data['msg'] = '充值提交失败';

				$this->ajaxReturn($data);exit;

			}

			

			

		}else{

			$data['status'] = 0;

			$data['msg'] = '非法操作';

			$this->ajaxReturn($data);exit;

		}

	}

	//充值处理私有方法

	private function rc_up($type,$uid,$arr=''){

		

			$sava['account'] = trim($arr['account']);

			$sava['name'] = trim($arr['uname']);

			$sava['price'] = trim($arr['price']);

			$sava['marker'] = trim($arr['marker']);

			if($type ==1){

				$sava['way'] = 1;//支付宝

			}elseif($type ==2){

				$sava['way'] = 2;//微信

			}elseif($type ==3){

				$sava['way'] = 3;//银行卡

			}

			

			$sava['addtime'] = time();

			$sava['status'] = 1;//充值状态

			$sava['uid'] = $uid;

			$re = M('recharge')->add($sava);

			if($re){

				return 1;

			}else{

				return 2;

			}

	}

	

	//微信充值页面

	public function recharge_wx(){

		

		$this->display();

	}

	

	

	//从微信充值提交

	public function wx_rc(){

		if($_POST){

			$uid = session('userid');

			$account = trim(I('post.account'));

			$rlist = M('user')->where(array('account'=>$account))->find();

			if(empty($rlist)){

				$data['status'] = 0;

				$data['msg'] = '该会员不存在';

				$this->ajaxReturn($data);exit;

			}

			$type = 2;

			$arr = I('post.');

			$st = $this->rc_up($type,$uid,$arr);

			if($st ==1){

				$data['status'] = 1;

				$data['msg'] = '充值提交成功';

				$this->ajaxReturn($data);exit;

			}else{

				$data['status'] = 0;

				$data['msg'] = '充值提交失败';

				$this->ajaxReturn($data);exit;

			}

			

			

		}else{

			$data['status'] = 0;

			$data['msg'] = '非法操作';

			$this->ajaxReturn($data);exit;

		}

	}

	

	

	

	

	//银行卡充值页面

	public function recharge_bank(){

		

		$this->display();

	}

	

	//从银行卡充值提交

	public function bank_rc(){

		if($_POST){

			$uid = session('userid');

			$account = trim(I('post.account'));

			$rlist = M('user')->where(array('account'=>$account))->find();

			if(empty($rlist)){

				$data['status'] = 0;

				$data['msg'] = '该会员不存在';

				$this->ajaxReturn($data);exit;

			}

			$type = 3;

			$arr = I('post.');

			$st = $this->rc_up($type,$uid,$arr);

			if($st ==1){

				$data['status'] = 1;

				$data['msg'] = '充值提交成功';

				$this->ajaxReturn($data);exit;

			}else{

				$data['status'] = 0;

				$data['msg'] = '充值提交失败';

				$this->ajaxReturn($data);exit;

			}

			

			

		}else{

			$data['status'] = 0;

			$data['msg'] = '非法操作';

			$this->ajaxReturn($data);exit;

		}

	}

	



}