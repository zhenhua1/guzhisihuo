<?php



namespace Home\Controller;



use Think\Controller;



class IndexController extends CommonController

{



    public function index(){



		$ulist = M('user')->order('zsy desc')->limit(6)->select();



		foreach($ulist as $k=>&$v){

			$v['username'] = $this->substr_cut($v['username']);

		}



		$num = count($ulist);



		$this->assign('num',$num);

		$this->assign('ulist',$ulist);

        $this->display();

    }



	public function substr_cut($user_name){

		$strlen     = mb_strlen($user_name, 'utf-8');

		$firstStr     = mb_substr($user_name, 0, 2, 'utf-8');

		$lastStr     = mb_substr($user_name, -2, 2, 'utf-8');

		return $strlen == 2 ? mb_substr($user_name, 0, 1, 'utf-8') . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . "**" . $lastStr;

	}



	public function qdgame(){

		$userid = session('userid');

		$ulist = M('user')->where(array('userid'=>$userid))->find();

		$clist = M('system')->where(array('id'=>1))->find();

		if($ulist['money'] > 0){

			$max_pipeinone = $ulist['money'] * ($clist['qd_cf']  / 100);

		}else{

			$max_pipeinone = 0;

		}







		$tarr = explode(',',$clist['qd_ndtime']);

		/*******刷新一次更改一次，不行*******/

		/* $st = in_array($h,$tarr);



		$nd = explode(',',$clist['qd_nd']);



		$num = count($nd);

		$key = rand(0,$num-1);



		if($st){



			$key = rand(0,$num-1);



			if($key=='' || $key == 0){

				$key = '0';

			}

			if($m > 0 && $m <= 59){

				$tkey = $key;

			}

			$qd_yjjc = $nd[$tkey];

		}else{

			$qd_yjjc = '0';

		}  */



		/******只能手动后台更改了*****/

		$tbmatch = M('tbmatch')->select();

		$this->assign('tbmatch',$tbmatch);



		$this->assign('tarr',$tarr);

		$this->assign('qd_nd',$clist['qd_nd']);

		$this->assign('qd_yjjc',$clist['qd_yjjc']);

		$this->assign('max_pipeinone',$max_pipeinone);



		if(is_mobile()){



            $this->display();



        } else {



            $this->display('pc/typography');

        }



	}



	public function shoudan(){

		$userid = session('userid');

		$slist = M('userrob')->where(array('uid'=>$userid,'status'=>2))->select();

		$flist = M('userrob')->where(array('uid'=>$userid,'status'=>3))->select();

		$dlist = M('userrob')->where(array('uid'=>$userid,'status'=>4))->select();

		$this->assign('slist',$slist);

		$this->assign('flist',$flist);

		$this->assign('dlist',$dlist);



		if (is_mobile()) {

			$this->display();

		} else {

			$userid = session('userid');

			$olist = M('userrob')->where(array('uid'=>$userid))->select();

				$this->assign('olist',$olist);

			$this->display('pc/shou');

		}



	}



	//会员抢单详请

	public function qiangdanxq(){

		if($_GET){



			$userid = session('userid');

			$ulist = M('user')->where(array('userid'=>$userid))->find();

			$id = trim(I('get.id'));

			$olist = M('userrob')->where(array('id'=>$id))->find();

			$ewmlist = M('ewm')->where(array('uid'=>$userid,'ewm_price'=>$olist['price'],'ewm_class'=>$olist['class']))->find();

			$this->assign('olist',$olist);

			$this->assign('ewmlist',$ewmlist);

			$this->display();



		}else{

			$this->error('未知错误',U('Index/shoudan'));

		}



	}



	//生成抢单订单

	public function pipeiorder(){

		if($_POST){

			$updata['pipeitime'] = time();

			$updata['pipeitime'] = time();



			$qdclass=trim(I('post.qdclass'));

			$userid = session('userid');

			$ulist = M('user')->where(array('userid'=>$userid))->find();

			$ewm = M('ewm');

			$ewmList = $ewm->where(array('uid'=>$userid))->find();

			if($ulist['money'] < $ewmList['ewm_price'])

			{

				$data['status'] = 0;

				$data['msg'] = '您账户余额不足';

				$this->ajaxReturn($data);exit;

			}

			$clist = M('system')->where(array('id'=>1))->find();

			if($ulist['rz_st'] != 1){

				$data['status'] = 0;

				$data['msg'] = '请先完善资料';

				$this->ajaxReturn($data);exit;

			}

			if($ulist['tx_status'] != 1){

				$data['status'] = 0;

				$data['msg'] = '您的账号被管理员禁止抢单';

				$this->ajaxReturn($data);exit;

			}

			if($ulist['money'] > 0){

				$max_pipeinone = $ulist['money'] * ($clist['qd_cf']  / 100);

			}else{

				$max_pipeinone = 0;

			}



			if($max_pipeinone < $clist['qd_minmoney']){

				$data['status'] = 0;

				$data['msg'] = '最低抢单额度为'.$clist['qd_minmoney'];

				$this->ajaxReturn($data);exit;

			}



			/****需要添加一个未完成订单限制*******/

			$where['status'] = array('not in','3,4');

			$where['uid'] =$userid;

			$no_count = M('userrob')->where($where)->count();

			if($no_count ){

				$data['status'] = 0;

				$data['msg'] = '您有一条匹配订单未完成';

				$this->ajaxReturn($data);exit;

			}

			/********************/



			$count_qrnum = M('ewm')->where(array('uid'=>$userid,'ewm_class'=>$qdclass))->count();



			if($qdclass == 1){

				$str = '微信收款二维码';

			}elseif($qdclass== 2){

				$str = '支付宝收款二维码';

			}elseif($qdclass==3){

				$str = '银行收款二维码';

			}



			if($count_qrnum < 1){

				$data['status'] = 0;

				$data['msg'] = '您没有上传'.$str;

				$this->ajaxReturn($data);exit;

			}





			/*********这里需要区分直接匹配成功，和后台没有发布订单时的排队匹配两种情况********/

			$orderlist = M('roborder')->where(array('class'=>$qdclass,'status'=>1))->order('price asc')->select();



			if(!empty($orderlist)){//后台有符合条件的待匹配订单，生成一条直接匹配好的记录。

				//符合条件的最小额度的记录为$orderlist[0],所以直接匹配最小的这一条，如果最小金额的都不够匹配，同样也生成一条匹配记录，提示等待(不采用)

				//这里写业务

				//循环匹配收款二维类型及金额都符合则匹配成功

				$ewmlist = M('ewm')->where(array('uid'=>$userid,'ewm_class'=>$qdclass))->select();

				foreach($orderlist as $k=>$v){

					foreach($ewmlist as $val){

						if($v['price'] == $val['ewm_price']){

							$st = 1;

							$pid = $v['id'];

							break;

						}

					}

				}

				if($st == '' || $st ==0){

					$pipeist = 0;

				}elseif($st == 1){

					$pipeist = 1;

				}



				if($pipeist == 1){//匹配成功更新后台发布的订单/生成一条匹配成功的会员匹配记录  同时减去会员账号余额，且加上佣金'qd_yjjc' 生成日录(确认后做这些操作)







					$tolist = M('roborder')->where(array('id'=>$pid))->find();//被匹配的这一条记录



					if($tolist['status'] == 1){



						$psave['uid'] = $userid;

						$psave['uname'] = $ulist['truename'];

						$psave['umoney'] = $ulist['money'];

						$psave['pipeitime'] = time();

						$psave['status'] = 2;



						$pipei_re = M('roborder')->where(array('id'=>$pid))->save($psave);



						if($pipei_re){



							$updata['uid'] = $userid;

							$updata['class'] = $qdclass;

							$updata['price'] = $tolist['price'];

							$updata['yjjc'] = $clist['qd_yjjc'];

							$updata['umoney'] = $ulist['money'];

							$updata['uaccount'] = $ulist['account'];

							$updata['uname'] = $ulist['truename'];

							$updata['ppid'] = $pid;

							$updata['status'] = 2;

							$updata['addtime'] = time();

							$updata['pipeitime'] = time();

							$updata['ordernum'] = getordernum();



							$up_re = M('userrob')->add($updata);

							if($up_re){

								$data['status'] = 1;

								$data['msg'] = '匹配成功';

								$this->ajaxReturn($data);exit;

							}else{

								$data['status'] = 0;

								$data['msg'] = '未知错误';

								$this->ajaxReturn($data);exit;

							}

						}else{

							$data['status'] = 0;

							$data['msg'] = '未知错误';

							$this->ajaxReturn($data);exit;

						}

					}else{



						$updata['uid'] = $userid;

						$updata['class'] = $qdclass;

						$updata['price'] = '';

						$updata['yjjc'] = '';

						$updata['umoney'] = $ulist['money'];

						$updata['uaccount'] = $ulist['account'];

						$updata['uname'] = $ulist['truename'];

						$updata['ppid'] = '';

						$updata['status'] = 1;

						$updata['addtime'] = time();



						$updata['ordernum'] = getordernum();

						$up_re = M('userrob')->add($updata);



						if($up_re){



							$data['status'] = 1;

							$data['msg'] = '已生成订单等待自动匹配';

							$this->ajaxReturn($data);exit;

						}else{



							$data['status'] = 0;

							$data['msg'] = '未知错误';

							$this->ajaxReturn($data);exit;

						}



					}





				}else{





					$erm = M('ewm')->where(array('uid'=>$userid,'ewm_price'=>array('elt',$max_pipeinone)))->order('ewm_price asc')->select();

					if(!$erm){

						$data['status'] = 0;

						$data['msg'] = '抢单额度不足';

						$this->ajaxReturn($data);exit;

					}



					$updata['uid'] = $userid;

					$updata['class'] = $qdclass;

					$updata['price'] = '';

					$updata['yjjc'] = '';

					$updata['umoney'] = $ulist['money'];

					$updata['uaccount'] = $ulist['account'];

					$updata['uname'] = $ulist['truename'];

					$updata['ppid'] = '';

					$updata['status'] = 1;

					$updata['addtime'] = time();

					$updata['ordernum'] = getordernum();

					$up_re = M('userrob')->add($updata);



					if($up_re){



						$data['status'] = 1;

						$data['msg'] = '已生成订单等待自动匹配';

						$this->ajaxReturn($data);exit;

					}else{



						$data['status'] = 0;

						$data['msg'] = '未知错误';

						$this->ajaxReturn($data);exit;

					}

				}





			}else{//后台没有符合条件的单则生成一条记录，提示等待



				$updata['uid'] = $userid;

				$updata['class'] = $qdclass;

				$updata['price'] = '';

				$updata['yjjc'] = '';

				$updata['umoney'] = $ulist['money'];

				$updata['uaccount'] = $ulist['account'];

				$updata['uname'] = $ulist['truename'];

				$updata['ppid'] = '';

				$updata['status'] = 1;

				$updata['addtime'] = time();

				$updata['ordernum'] = getordernum();

				$up_re = M('userrob')->add($updata);



				if($up_re){



					$data['status'] = 1;

					$data['msg'] = '已生成订单等待自动匹配';

					$this->ajaxReturn($data);exit;

				}else{



					$data['status'] = 0;

					$data['msg'] = '未知错误';

					$this->ajaxReturn($data);exit;

				}



			}



		}else{

			$data['status'] = 0;

			$data['msg'] = '非法操作';

			$this->ajaxReturn($data);exit;



		}





	}





	public function pipeiauto(){

		if($_POST){

			$data['status'] = 0;

			$data['msg'] = '抢单业务繁忙！';

			$this->ajaxReturn($data);exit;

		}else{

			$data['status'] = 0;

			$data['msg'] = '非法操作';

			$this->ajaxReturn($data);exit;

		}

	}











}