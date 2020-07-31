<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/3/11
 * Time: 19:52
 */
namespace app\home\model;


use app\common\model\CommonModel;
use think\Db;

class PersonalModel extends CommonModel {

	public function getPersonalData( $uid ){
		if($uid){
			$selfIntegral = Db::table("integral")
				->where("uid",$uid)
				->find();
			$helpIntegral = Db::table("integral_total")
			          ->where("uid",$uid)
			          ->find();
			$result = array();
			if($selfIntegral){
				$result['selfIntegral'] = $selfIntegral['integral'];
			}else{
				$result['selfIntegral'] = 0;
			}
			if($helpIntegral){
				$result['lastpersonal']= $helpIntegral['total_integral'] - $helpIntegral['exchange_integral'];
			}else{
				$result['lastpersonal'] = 0;
			}
			$user = $this->getUserNameById($uid);
			$result['user'] = $user['nickname'];
			$result['userimg'] = $user['wexinImg'];
			$result['uid']     = $uid;
			return $result;
		}
	}

	private function getUserNameById( $id ){
		$name = Db::table("home_user")
			->where("id",$id)
			->find();
		return $name;
	}
}