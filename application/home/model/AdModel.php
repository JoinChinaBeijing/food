<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/18
 * Time: 12:09
 */

namespace app\home\model;


use app\common\model\DateTimeTool;
use think\Db;
use think\Model;

class AdModel extends Model {

	public function getActiveData( $uid ){
		$res = Db::table("active")
			->where("uid",$uid)
			->where("active",config("active"))
			->find();
		if( $res ){
			return false;
		}else{ // 不存在就添加
			$insertdata['active'] = config("active");
			$insertdata['uid'] = $uid;
			Db::table("active")
				->insert($insertdata);
			return true;
		}
	}

	public function incIntegral( $uid ){
		$integral = config("integral");
		$res = Db::table("d_integral")
			->where("uid",$uid)
			->find();
		if($res){
			Db::table("d_integral") //增加积分
			  ->where("uid",$uid)
				->setInc('integral', $integral);
			Db::table("integral_total")
				->where('uid',$uid)
				->setInc("total_integral",$integral);
		}else{
			$insertdata['uid'] = $uid;
			$insertdata['integral'] = $integral;
			$insertdata['create_time'] = DateTimeTool::getDate();
			Db::table("d_integral")->insert($insertdata);
			$total['total_integral'] = $integral;
			$total['exchange_integral'] = 0;
			$total['uid'] = $uid;
			Db::table("integral_total")
			  ->insert($total);
		}
	}

}