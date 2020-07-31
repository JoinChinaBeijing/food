<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/3/11
 * Time: 13:48
 */

namespace app\index\model;


use app\common\model\CommonModel;
use app\common\model\ExcelTool;
use think\Db;
use PHPExcel;

class OrderModel extends CommonModel {


	public function getorders( $start, $rows,$starttime, $endtime, $status ){
		if($status == 999){
			$row = Db::table("order")
			         ->where('createtime','between',[$starttime,$endtime])
			         ->count();
		}else{
			if($status==9)$status = 0;
			$row = Db::table("order")
			         ->where('createtime','between',[$starttime,$endtime])
			         ->where("status",$status)
			         ->count();
		}
		$result["total"] = $row;
		if($status == 999){
			$rs = Db::table("order")
			        ->where('createtime','between',[$starttime,$endtime])
			        ->limit($start,$rows)
					->order("id desc")
			        ->select();
		}else{
			if($status ==9)$status = 0;
			$rs = Db::table("order")
			        ->where('createtime','between',[$starttime,$endtime])
			        ->where("status",$status)
			        ->limit($start,$rows)
					->order("id desc")
			        ->select();
		}
		if($rs){
			foreach ($rs as $k=>$v){
				if($rs[$k]['deyamount'] == 0){
					$rs[$k]['type'] = '自取';
				}else{
					$rs[$k]['type'] = '配送';
				}
				switch ($rs[$k]['status']){
					case 1:
						$rs[$k]['status'] = "已支付";
						break;
					case 0:
						$rs[$k]['status'] = "未支付";
						break;
					case 3:
						$rs[$k]['status'] = "发起退款";
						break;
					case 4:
						$rs[$k]['status'] = "退款完成";
						break;
					default:
						$rs[$k]['status'] = "其他";
						break;
				}
				if($rs[$k]['isbreakfast'] == 1){
					$rs[$k]['breakfast'] = '早餐';
				}else{
					$rs[$k]['breakfast'] = '小吃';
				}
			}
		}
		$result["rows"] = $rs;
		return $result;
	}


	public function getRefundList( $start, $rows,$starttime, $endtime, $orderid){
		if($orderid == 999){
			$row = Db::table("order")
			         ->where('createtime','between',[$starttime,$endtime])
			         ->count();
		}else{
			$row = Db::table("order")
			         ->where('createtime','between',[$starttime,$endtime])
			         ->where("orderid",$orderid)
			         ->count();
		}
		$result["total"] = $row;
		if($orderid == 999){
			$rs = Db::table("order")
			        ->where('createtime','between',[$starttime,$endtime])
			        ->limit($start,$rows)
					->order("id desc")
			        ->select();
		}else{
			$rs = Db::table("order")
			        ->where('createtime','between',[$starttime,$endtime])
			        ->where("orderid",$orderid)
			        ->limit($start,$rows)
					->order("id desc")
			        ->select();
		}
		if($rs){
			foreach ($rs as $k=>$v){
				if($rs[$k]['deyamount'] == 0){
					$rs[$k]['type'] = '自取';
				}else{
					$rs[$k]['type'] = '配送';
				}
				switch ($rs[$k]['status']){
					case 1:
						$rs[$k]['status'] = "已支付";
						break;
					case 0:
						$rs[$k]['status'] = "未支付";
						break;
					case 3:
						$rs[$k]['status'] = "发起退款";
						break;
					case 4:
						$rs[$k]['status'] = "退款完成";
						break;
					default:
						$rs[$k]['status'] = "其他";
						break;
				}

				if($rs[$k]['isbreakfast'] == 1){
					$rs[$k]['breakfast'] = '早餐';
				}else{
					$rs[$k]['breakfast'] = '小吃';
				}
			}
		}
		$result["rows"] = $rs;
		return $result;
	}


	public function getReport( $starttime, $endtime, $status ){
		if($status ==9)$status = 0;
		if($status == 999 ){
			$rs = Db::table("order")
			        ->where('createtime','between',[$starttime,$endtime])
			        ->field("orderid,amount,sid,status,deyamount,remark,createtime")
			        ->select();
		}else{
			$rs = Db::table("order")
			        ->where('createtime','between',[$starttime,$endtime])
			        ->where("status",$status)
			        ->field("orderid,amount,sid,status,deyamount,remark,createtime")
			        ->select();
		}
		foreach ($rs as $k=>$v){
			$rs[$k]["sid"] = Db::table("store")->where("id",$rs[$k]['sid'])->value("name");
		}
		$filename = "订单报表" . date('YmdHis') .".xls";
		$letter = array('A', 'B', 'C', 'D', 'E', 'F', 'G');
		$tableheader = array( '订单号', '订单金额','店铺', '订单状态','配送费','备注','创建时间');
		$excelTool = new ExcelTool();
		$excelTool->export($letter, $tableheader, $rs, $filename);

	}

	public function updateOrderStatus($orderid, $status ){
		$data['status'] = $status;
		Db::table("order")
			->where('orderid',$orderid)
			->update($data);
	}

	public function checkStatus( $orderid, $status ){
		$res = Db::table("order")
			->where("orderid",$orderid)
			->value("status");
		return $res == $status ? true :false;
	}

	public function checkPassword( $password ){
		$sign = md5($password.config("paystr"));
		$res = Db::table("setting")
			->where("name",'backpsd')
			->value("desc");
		return $sign == $res ? true :false;
	}

	public function decIntegral( $orderid, $amount){
		$amount = floor(floatval($amount));
		$res = Db::table("order")
			->where("orderid",$orderid)
			->find();
		if( $res && $res['isbreakfast'] == 0 ){//早餐积分不修改
			Db::table("integral")
				->where("uid",$res['userid'])
				->setDec("integral",$amount);
		}
	}

	public function getsell($start,$end){
		$sell =  Db::table("order")
		           ->where('createtime','between',[$start,$end])
					->where('status',1)
		           ->sum("amount");
		return $sell ? $sell : 0.00;
	}
}