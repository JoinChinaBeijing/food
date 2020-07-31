<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/3/6
 * Time: 16:22
 */

namespace app\common\model;


use think\Db;
use think\Exception;

class SerialTool extends CommonModel {

	private static $PAYORDER = '01';//支付订单

	private static $REFUNDORDER = '02';//支付订单

	private static $POSPAY = 'PO';//餐饮订单前缀，用来区分支付报表,商户

	private static $OTHERPAY = 'OT';


	//商户订单号
	public static function getPayOrderId($ordertype)
	{
		switch ($ordertype) {
			case 'pos':
				$prefix = self::$POSPAY;
				break;
			default:
				$prefix = self::$OTHERPAY;
				break;
		}
		return $prefix . self::getIdByType(self::$PAYORDER);
	}

	public static function getOrderId()
	{
		return self::getIdByType(self::$PAYORDER);
	}




	//客人订单号
	private static function getIdByType($typecode)
	{
		$time = date('Ymd');
		$date = date("Y-m-d");
		$seqId = Db::table("serial")
			->where("seqdate",$date)
			->where("type",$typecode)
			->value("seqvalue");
		if ($seqId == null) { //如果没有记录则插入一条记录
			try {
				$seqId = 1;
				$data['seqdate'] = $date;
				$data['type'] = $typecode;
				$data['seqvalue'] = $seqId;
				Db::table("serial")->insert($data);
			} catch (Exception $e) {//如果插入报错说明可能其他地方已经抢先插入记录，所以需要重新读取
				$seqId = Db::table("serial")
				           ->where("seqdate",$date)
				           ->where("type",$typecode)
				           ->value("seqvalue");
			}
		} else {
			$seqId = $seqId + 1;
			Db::startTrans();
			try{
				$data2['seqvalue'] = $seqId;
				Db::table("serial")
					->where("seqdate",$date)
					->where("type",$typecode)
					->update($data2);
			}catch ( Exception $e ){
				Db::rollback();
			}
			Db::commit();
		}
		$length = strlen($seqId);
		//判断用户id前是否需要补零
		if ($length < 4) {
			$id = '';
			for ($i = 0; $i < (4 - $length); $i++) {
				$id .= '0';
			}
			$seqId = $id . $seqId;
		}
		$sequenceid = $time . rand(10, 99) . $typecode . $seqId;
		return $sequenceid;
	}
}