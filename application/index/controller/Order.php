<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/3/11
 * Time: 13:42
 */

namespace app\index\controller;



use app\common\model\CacheTool;
use app\common\model\DateTimeTool;
use app\index\model\OrderModel;
use app\wechat\model\Wechat;
use think\Request;

class Order extends Base   {


	public function listorder(){
		$start = DateTimeTool::getDate("Y-m-d",0 )." 00:00:00";
		$end = DateTimeTool::getDate("Y-m-d",0 )." 23:59:59";
		$ordermodel = new  OrderModel();
		$sell = $ordermodel->getsell($start,$end);
		$this->assign("sell",$sell);
		$this->assign("start",$start);
		$this->assign("end",$end);
		return $this->fetch();
	}

	public function getOrders(){
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
		$params = session("repotstart");
		$start = isset($params["start"]) ? $params["start"] : DateTimeTool::getDate("Y-m-d")." 00:00:00";
		$end = isset($params["end"]) ? $params["end"] : DateTimeTool::getDate("Y-m-d")." 23:59:59";
		$status = isset($params["status"]) ? $params["status"]:999;
		$page = ($page -1 ) * 20;
		$ordermodel = new  OrderModel();
		$result  =$ordermodel->getorders($page, $rows,$start,$end,$status);
		if(!isset($_POST['page'])){
			session("repotstart",null);
		}
		echo json_encode($result);
	}

	public function paramsset(){
		session("repotstart",$_POST);
		echo 1;
	}

	public function report(){
		$ordermodel = new  OrderModel();
		$start = Request::instance()->post("start")." 00:00:00";
		$end = Request::instance()->post("end")." 23:59:59";
		$status = Request::instance()->post("orderstatus");
		$ordermodel->getReport( $start, $end, $status );
	}
	public function listrefund(){
		$start = isset($_POST['start']) ? $_POST['start'] : DateTimeTool::getDate("Y-m-d")." 00:00:00";
		$end = isset($_POST['end']) ? $_POST['end'] : DateTimeTool::getDate("Y-m-d")." 23:59:59";
		session('order_getlist_post', []);
		$this->assign("start",$start);
		$this->assign("end",$end);
		return $this->fetch();
	}

	public function getList(){
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
		$start = date('Y-m-d 00:00:00');
		$end   = date('Y-m-d 23:59:59');
		$orderid = 999;
		if (!isset($_POST['start']))
		{
			$order_getlist_post = session('order_getlist_post');
			$start = isset($order_getlist_post['start']) ? $order_getlist_post['start'] : date('Y-m-d 00:00:00');
			$end = isset($order_getlist_post['end']) ? $order_getlist_post['end'] : date('Y-m-d 23:59:59');
			$orderid = isset($order_getlist_post['orderid']) ? $order_getlist_post['orderid'] : 999;
		}
		else
		{
			$_POST['orderid'] = isset($_POST['orderid']) && !empty($_POST['orderid']) ? $_POST['orderid'] : 999;
			session('order_getlist_post', $_POST);
		}
		$page = ($page -1 ) * 20;
		$ordermodel = new  OrderModel();
		$result  =$ordermodel->getRefundList($page, $rows,$start,$end,$orderid);
		echo json_encode($result);
	}

	public function dorefund(){
		$refunddata = Request::instance()->post();
		//校验密码
		$ordermodel = new OrderModel();
		$checkResult = $ordermodel->checkPassword($refunddata['pass']);
		if($checkResult){
			$wechatModel = new Wechat();
			//验证是否可操作退款
			$checkstatus = $ordermodel->checkStatus($refunddata['orderid'],1);
			if($checkstatus){
				$res = $wechatModel->sixrefund($refunddata['transid'],$refunddata['orderid'],$refunddata['payamount'],$refunddata['refundamount'],$refunddata['refundremark']);
				if($res['code'] == '200' || $res['code'] == '202'){
					//修改订单状态
					$ordermodel->updateOrderStatus($refunddata['orderid'],4);
					//修改积分
					$ordermodel->decIntegral($refunddata['orderid'],$refunddata['refundamount']);
				}
			}else{
				$res['code'] = 405;
				$res['msg'] = "该订单不支持退款！";
			}
		}else{
			$res['code'] = 405;
			$res['msg'] = "操作密码错误！";
		}
		echo json_encode($res);
	}
}