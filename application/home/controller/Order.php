<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/3/9
 * Time: 17:50
 */

namespace app\home\controller;


use app\common\model\CacheTool;
use app\common\model\PaysignTool;
use app\home\model\AddressModel;
use app\home\model\IndexModel;
use app\home\model\OrderModel;
use think\Request;


class Order extends Base  {

	public function index(){
		//获取用户地址数组数据
		$addressMocel = new AddressModel();
		$orderModel = new OrderModel();
		if($_POST){
			$orderData = Request::instance()->post();
			CacheTool::setCache("redis","order".$this->uid,$orderData);
		}else{
			$orderData = CacheTool::getCache("redis","order".$this->uid);
		}
		$resData = $orderModel->getOrderData( $orderData );
		//获取默认地址
		$defaultAdr = $addressMocel->getDefaultAddressByUser( $this->uid, $orderData['store'] );
		$adrData = $addressMocel->getAddressDataByUser( $this->uid ,$orderData['store']);
		$school = $orderModel->getSchoolBystore( $orderData['store'] );
		$this->assign("school",$school);
		$this->assign("defaultAdr",$defaultAdr);
		$this->assign("adrData",$adrData);
		$this->assign("store",$orderData['store']);
		$this->assign("data",$resData['data']);
		$this->assign("total",$resData['total']);
		$zqinfo = $orderModel->getzqinfo( $this->uid );
		$this->assign("zqinfo",$zqinfo);
		$this->assign("uid",$this->uid);
		$paynum = $orderData['chosenum'];
		$this->assign("yunfei",sprintf("%.2f",config("yunfei")) * $paynum);
		$this->assign("lowspend",config("lowspend"));
		return $this->fetch();
	}

	public function checkChose(){
		$data = Request::instance()->post();
		$foods = $data['deliveryfoods'];
		$cate = array();
	    $foodids = array();
		foreach ($foods as $k=>$v){
			$foodArr = explode("-",$v);
			$cate[]= $foodArr[0];
			$foodids[] = $foodArr[1];
		}
		$cate = array_unique($cate);
		$mastchosecate = array_intersect_assoc(config("chosecate"),$cate);
		if(count( $mastchosecate) > 0 ){
			$indexmodel = new IndexModel();
			foreach ( $mastchosecate as $k=>$v ){
				$foodresult = $indexmodel->getMastChoseFoodsByCidSid( $v,$data['store']);
				if( $foodresult ){
					$chosearr = array();
					foreach ($foodresult as $kk=>$vv ){
						if(!in_array($vv['id'],$foodids)){
							$chosearr[] = $vv['name'];
						}
					}
					if(count($chosearr) > 0 ){
						$res['code'] = 100;
						$res['msg'] = implode(",",$chosearr)."为必选哦";
						echo json_encode($res);return;
					}else{
						$res['code'] = 200;
						$res['msg'] = '验证通过';
						echo json_encode($res);return;
					}
				}else{ //必选分类里边没有必须选择的产品
					$res['code'] = 200;
					$res['msg'] = '验证通过';
					echo json_encode($res);return;
				}
			}
		}else{//选择的分类里边没有
			$res['code'] = 200;
			$res['msg'] = '验证通过';
			echo json_encode($res);return;
		}

	}

	//保存订单，支付
	public function addorder(){
		if($_POST){
			$orderData = Request::instance()->post();
			if($orderData){
				//保存订单
				$orderModel = new OrderModel();
				$insertRes = $orderModel->insertOrder($orderData);
				$address = new AddressModel();
				$address->updateSort($orderData['uid'],$orderData['store']);
				if( is_array($insertRes) ){
					//调取支付
					$getData['amount'] = $insertRes['amount'];
					$getData['orderid'] = $insertRes['orderid'];
					$getData['callbackUrl'] = "http://www.sixmeter.cn/home/order/myorder";
					$params = PaysignTool::getPayParam($getData);
					$openid = $orderModel->getOpenidByUser($orderData['uid']);
					$params = $params."&openid=$openid";
					$this->redirect("/wechat/index/sixmeterpay?$params");
				}
			}
		}
	}

	//订单列表
	public function myorder(){
		$ordermodel = new OrderModel();
		$orderList = $ordermodel->getorderList( $this->uid );
		$this->assign("orderList",$orderList);
		return $this->fetch();
	}

	//订单详情
	public function orderdetail(){
		$orderid = Request::instance()->param("orderid");
		$ordermodel = new OrderModel();
		$orderData = $ordermodel->getOrderDetail( $orderid );
		$this->assign("orderData",$orderData);
		$this->assign("homeUrl",session('homeUrl') ? session('homeUrl') : 'home/index/index');
		return $this->fetch();
	}
}