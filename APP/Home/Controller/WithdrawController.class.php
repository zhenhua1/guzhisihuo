<?php

namespace Home\Controller;

use Think\Controller;

class WithdrawController extends CommonController {



	//提现记录管理

    public function index(){



		$uid = session('userid');

		$welist = M('withdraw')->where(array('uid'=>$uid))->order('id desc')->select();

		$this->assign('welist',$welist);





         if(is_mobile()){



            $this->display('withdraw/index');



        } else {



            $this->display('pc/tixian');

        }

    }



	//提现页面

	public function tixian(){



		 if(is_mobile()){





            $this->display('withdraw/tixian');



        } else {



            $this->display('pc/tixians');

        }



	}



	//提现处理

	public function drawup(){

		if($_POST){

			$uid = session('userid');

			$ulist = M('user')->where(array('userid'=>$uid))->find();

			/*******这里写提现条件********/



			$save['uid'] = $uid;

			$save['account'] = trim(I('post.account'));

			$save['name'] = trim(I('post.uname'));

			$save['way'] = trim(I('post.way'));

			$save['price'] = trim(I('post.price'));

			$save['addtime'] = time();

			$save['endtime'] = '';



			$save['status'] = 1;

			if($save['way'] == '微信'){

				if($save['account'] != $ulist['wx_no']){

					$data['status'] = 0;

					$data['msg'] = '请使用绑定的微信账号';

					$this->ajaxReturn($data);exit;

				}



			}elseif($save['way'] == '支付宝'){

				if($save['account'] != $ulist['alipay']){

					$data['status'] = 0;

					$data['msg'] = '请使用绑定的支付宝账号';

					$this->ajaxReturn($data);exit;

				}



			/*}elseif($save['way'] == '银行卡'){



				$data['status'] = 0;

				$data['msg'] = '没有此提现类型';

				$this->ajaxReturn($data);exit;



			}else{

				$data['status'] = 0;

				$data['msg'] = '没有此提现类型';

				$this->ajaxReturn($data);exit;*/

			}



			$clist = M('system')->where(array('id'=>1))->find();



			if($save['price'] < $clist['mix_withdraw']){



				$data['status'] = 0;

				$data['msg'] = '最小提现额度'.$clist['mix_withdraw'].'元';

				$this->ajaxReturn($data);exit;



			}



			if($save['price'] > $clist['max_withdraw']){



				$data['status'] = 0;

				$data['msg'] = '最大提现额度'.$clist['max_withdraw'].'元';

				$this->ajaxReturn($data);exit;



			}





			$pipei_sum_price = M('userrob')->where(array('uid'=>$uid,'status'=>3))->sum('price');

			$rech_sum_price = M('recharge')->where(array('uid'=>$uid,'status'=>3))->sum('price');



			$blz = $pipei_sum_price / $rech_sum_price;



			$cblz = $clist['tx_yeb'] / 100;



			if($blz < $cblz){



				$data['status'] = 0;

				$data['msg'] = '您的匹配收款额度不足';

				$this->ajaxReturn($data);exit;



			}





			if($save['price'] > $ulist['money']){

				$data['status'] = 0;

				$data['msg'] = '账户余额不足';

				$this->ajaxReturn($data);exit;

			}

			$re = M('withdraw')->add($save);



			$ure =  M('user')->where(array('userid'=>$uid))->setDec('money',$save['price']);//直接扣除提现金额



			if($re && $ure){



				$data['status'] = 1;

				$data['msg'] = '提现已提交';

				$this->ajaxReturn($data);exit;



			}else{



				$data['status'] = 0;

				$data['msg'] = '非法操作';

				$this->ajaxReturn($data);exit;



			}





		}else{

			$data['status'] = 0;

			$data['msg'] = '非法操作';

			ajaxReturn($data);exit;

		}



	}





}