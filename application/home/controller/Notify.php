<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/3/6
 * Time: 12:49
 */

namespace app\home\controller;


use app\home\model\OrderModel;
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