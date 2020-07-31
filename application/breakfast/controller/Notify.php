<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/5/6
 * Time: 12:49
 */

namespace app\breakfast\controller;


use app\breakfast\model\OrderModel;
use think\Controller;
use think\Request;

class Notify extends Controller {

	//微信模块返回的支付成功异步通知
	public function notify(){
		$orderid = Request::instance()->get("orderid");
		$sign = Request::instance()->get("sign");
		$ordermodel = new OrderModel();
		$ordermodel->notify( $orderid, $sign );
	}
}