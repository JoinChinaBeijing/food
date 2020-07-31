<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/18
 * Time: 12:09
 */

namespace app\home\controller;


use app\common\model\CacheTool;
use app\home\model\AdModel;
use app\wechat\model\Wechat;
use think\Controller;

class Ad extends Base  {

	public function shareindex(){
		$uid = $this->uid;
		$access_token = CacheTool::getCache("redis","sharetoken");
		$weixinModel = new Wechat();
		if(empty($access_token)){
			$wxresponse = $weixinModel->getAccess_token();
			$access_token = $wxresponse['access_token'];
			CacheTool::setCache("redis","sharetoken",$access_token,7100);
		}
		$ticket = $weixinModel->getJsapiTicket( $access_token );
		$timestamp = time();
		$noncestr = config("noncestr");
		$signature = $weixinModel->getSignature($ticket['ticket'],$timestamp,$noncestr);
		$this->assign("timestamp",$timestamp);
		$this->assign("noncestr",$noncestr);
		$this->assign("signature",$signature);
		$this->assign("uid",$uid);
		return $this->fetch();
	}

	public function activeMid(){
		$uid = $_GET['uid'];
		if($uid){
			$ad = new AdModel();
			if(config("activestatus") == 1 ){
				$activedata = $ad->getActiveData( $uid );
				if($activedata){
					$ad->incIntegral($uid);
					$this->redirect("/home/ad/shareindex");
				}else{
					$this->redirect("/home/ad/shareindex");
				}
			}else{
				$this->redirect("/home/ad/shareindex");
			}
		}else{
			$this->redirect("/home/ad/shareindex");
		}
	}

}