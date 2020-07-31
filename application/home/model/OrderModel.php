<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/3/9
 * Time: 17:53
 */

namespace app\home\model;


use app\common\model\CacheTool;
use app\common\model\CommonModel;
use app\common\model\DateTimeTool;
use app\common\model\SerialTool;
use think\Db;
use think\Exception;

class OrderModel extends CommonModel {

	public function getOrderData( $data ){
		//按类别获得分类下的商品
		if($data){
			$result = array();
			$cate = $this->getCategoryByid($data["store"]);
			foreach ( $data as $k => $v ){
				if($v == 0 ){
					unset( $data[$k] );
				}
			}
			$foodsdatas = $this->getFoods($data["store"]);
			foreach ($cate['cate'] as $k=>$v ){
				$result[] = $this->getCateByid($v);
				foreach ($data as $kk=>$vv ){
					$foodarr = explode("-",$kk);
					if(count($foodarr) == 3 ){
						if($cate['cate'][$k] == $foodarr[1]){
							foreach ($foodsdatas as $kkk => $vvv){
								if($foodarr[2] == $vvv['id']){
									$foodData = $vvv;
									if($foodData){
										$foodData['num'] = $vv;
										if($foodData['disstatus'] == 1 ){
											$foodData['foodtotal'] = sprintf("%.2f",$vv*$foodData['price']*$foodData['discount']);
											$foodData['discountprice'] = sprintf("%.2f",$foodData['price']) * sprintf("%.2f",$foodData['discount']);
										}else{
											$foodData['foodtotal'] = sprintf("%.2f",$vv*$foodData['price']);
											$foodData['discountprice'] = sprintf("%.2f",$foodData['price']);
										}
										//获取口味
										$foodData['tastes'] = $this->getTasteByFid($foodarr[2]);
										$result[$k]['foods'][] = $foodData;
									}
								}
							}
						}
					}
				}
			}
			$total = 0;
			foreach ( $result as $k => $v ){
				if(!empty($result[$k]['foods'])){
					$totalprice = 0;
					foreach ($v['foods'] as $kk => $vv ){
						if($vv['disstatus'] == 1){
							$totalprice += $vv['discount'] * $vv['price'] * $vv['num'];
						}else{
							$totalprice+= $vv['price'] * $vv['num'];
						}
					}
					$result[$k]['catetotal'] = sprintf("%.2f",$totalprice);
					$total+=sprintf("%.2f",$totalprice);
				}else{
					unset($result[$k]);
				}
			}
			//总价
			$findres['data'] = $result;
			$findres['total'] = $total;
			return $findres;
		}
	}
	private function getCategoryByid( $id ){
		if( $id ){
			 $res =  Db::table("store_category")
				->where("sid",$id)
				->find();
			if($res){
				$res['cate'] = explode(",",$res['cids']);
				return $res;
			}
		}
	}

	private function getCateByid( $id ){
		if( $id ){
			return Db::table("category")
				->where("id",$id)
				->find();
		}
	}

	private function getFoods( $sid ){
		$foods = Db::table("food")->alias("f")
		         ->join("store_food s","f.id=s.fid",'LEFT')
		         ->where("s.sid",$sid)
		         ->field("f.id,name,picdate,pic,price,discount,cid,s.sid,disstatus,deftaste,unit")
		         ->select();
		if($foods){
			foreach ($foods as $k=>$v ){
				$foods[$k]['pic'] = $foods[$k]['picdate'] ."/".$foods[$k]["pic"];
			}
			return $foods;
		}
	}

	public function insertOrder( $orderData ){
		//获取总价
		if($orderData){
			$orderData = self::dataFilter($orderData);
			$orderid = SerialTool::getOrderId();
			$amount = 0 ;
			foreach ($orderData['deliveryfoods'] as $k=>$v ){
				$foodArr = explode("-",$v);
				$amount += $this->getFoodPriceById($foodArr[1]) * (int)$orderData['fooddeliverynum'][$k];
			}
			$mainOrder = array();
			$mainOrder['orderid'] = $orderid;
			$mainOrder['sid'] = $orderData['store'];
			$mainOrder['userid'] = $orderData['uid'];
			$mainOrder['status'] = 0;
			$mainOrder['delivery'] = 0;
			$mainOrder['remark'] = $orderData['deliveryremark'] ? $orderData['deliveryremark'] : "无";
			if($orderData['typeinput'] == 0 ){
				$mainOrder['adrid'] = $orderData['findyaddress'];
				$mainOrder['deyamount'] = sprintf("%.1f",$orderData['deliveryamount']);
				$amount = $amount + sprintf("%.1f",$orderData['deliveryamount']);
			}else{
				$mainOrder['deyamount'] = 0;
				$mainOrder['adrid'] = 0;
				$mainOrder['zqname'] = $orderData['zqname'];
				$mainOrder['zqphone'] = $orderData['zqphone'];
			}
			$mainOrder['amount'] = sprintf("%.1f",$amount);
			$mainOrder['notifyurl'] = "http://www.sixmeter.cn/home/notify/notify";
			$mainOrder['createtime'] = DateTimeTool::getDate();
			Db::startTrans();
			try{
				//插入主订单表
				$sqlId = Db::table("order")
				  ->insertGetId($mainOrder);
				//插入订单详情表
				$foodsdatas = $this->getFoods($orderData['store']);
				foreach ($orderData['deliveryfoods'] as $k=>$v ){
					$food = "";
					$foodnewArr = explode("-",$v);
					foreach ($foodsdatas as $kk=>$vv ){
						if($foodnewArr[1] == $vv['id']){
							$food = $vv;
						}
					}
					$detailData['mainid'] = $sqlId;
					if($food != "" ){
						$detailData['cid'] = $food['cid'];
						$detailData['fid'] = $foodnewArr[1];
						if($food['disstatus'] == 1){ //打折
							$foodprice = sprintf("%.1f",$food['price']) * sprintf("%.1f",$food["discount"]);
						}else{ //不打折
							$foodprice =  sprintf("%.1f",$food["price"]);
						}
						$detailData['price'] = $foodprice;
						$detailData['num'] = $orderData['fooddeliverynum'][$k];
						$detailData['status'] = 0;
						$tasteid = $foodnewArr[2];
						if($tasteid != 0 ){
							$tasteName = $this->getTaateNmae($tasteid);
							$detailData['fname'] = $food['name']."(".$tasteName.")";
						}else{
							$detailData['fname'] = $food['name'];
						}
						$detailData['total'] = $orderData['fooddeliverynum'][$k] * $foodprice;
						Db::table("order_detail")
						  ->insert($detailData);
					}
				}
				Db::commit();
				$returnresult['orderid'] = $mainOrder['orderid'];
				$returnresult['amount'] = $amount;
				return $returnresult;
			}catch (Exception $e ){
				Db::rollback();
				return false;
			}
		}else {
			return false;
		}
	}

	private function getFoodPriceById( $id ){
		$res =  Db::table("food")
			->where("id",$id)
			->find();
		if($res){
			if($res['disstatus'] == 1){ //打折
				return sprintf("%.1f",$res['price']) * sprintf("%.1f",$res["discount"]);
			}else{ //不打折
				return sprintf("%.1f",$res["price"]);
			}
		}else{
			return 0;
		}
	}

	public function updateIntegral( $uid, $amount ){
		//积分向下取整
		$amount = floor(floatval($amount));
		$integral = Db::table("integral")->where("uid",$uid)->find();
		try{
			if( $integral ){
				Db::table("integral")
				  ->where("uid",$uid)
				  ->setInc("integral", $amount);
			}else{
				$data['uid'] = $uid;
				$data['integral'] = $amount;
				Db::table("integral")
				  ->insert($data);
			}
			return null;
		}catch (Exception $exception){
			return "修改积分出现异常！";
		}
	}

	public function updatezqinfo( $uid, $zqname, $zqphone ){

		if( $zqname && $zqphone ){
			$zqinfo = Db::table("zqinfo")
				->where("uid",$uid)
				->find();
			try{
				if($zqinfo){ //改
					$data['zqname'] = substr($zqname,0,20);
					$data['zqphone'] = $zqphone;
					Db::table("zqinfo")
					  ->where("uid",$uid)
					  ->update($data);
				}else{//插
					$data['zqname'] = substr($zqname,0,20);
					$data['zqphone'] = $zqphone;
					$data['uid'] = $uid;
					Db::table("zqinfo")
					  ->insert($data);
				}
				return null;
			}catch (Exception $e){
				return "修改自取人信息异常！";
			}

		}
	}

	public function updataDefaultStore( $uid, $sid ){
		CacheTool::rmCache("redis","order".$uid);
		$row = Db::table("user_store")->where("uid",$uid)->find();
		try{
			if($row){
				$data2['sid'] = $sid;
				$res = Db::table("user_store")
				  ->where("uid",$uid)
				  ->where("type","food")
				  ->update($data2);
			}else{
				$data['uid'] = $uid;
				$data['sid'] = $sid;
				$data['type'] = 'food';
				$res = Db::table("user_store")
				  ->insert($data);
			}
			return null;
		}catch (Exception $e){
			return "修改默认店铺异常！";
		}

	}

	public function getSchoolBystore( $sid ){
		$res = Db::table("store_school")->alias("s")
			->join("school c","s.scid=c.id","RIGHT")
			->where("sid",$sid)
			->where("c.status",1)
			->select();
		return $res ? $res : null;
	}

	public function getTasteByFid( $id ){
		if( $id ){
			$res = Db::table("food_taste")->alias('f')
				->join("taste t","f.tid=t.id","right")
				->where("f.fid",$id)
				->select();
			if($res){
				return $res;
			}

		}
	}

	private function getTaateNmae( $id ){
		return Db::table("taste")
			->where("id",$id)
			->value("name");
	}

	public function getorderList( $userid ){
		$res = Db::table("order")
			->where("userid",$userid)
			->where("status","in","1,3,4")
			->field("id,orderid,amount,createtime")
			->order("id desc")
			->select();
		if( $res ){
			foreach ( $res as $k=>$v ){
				$res[$k]['cate'] = $this->getCateDesc($res[$k]['id']);
			}
			return $res;
		}else{
			return null;
		}
	}

	private function getCateDesc( $mainid ){
		$res = Db::table("order_detail")
			->where("mainid",$mainid)
			->select();
		if($res){
			$catearr = array();
			foreach ( $res as $k=>$v ){
				$catearr[] = $res[$k]['cid'];
			}
			$catearr = array_unique($catearr);
			$result = array();
			foreach ($catearr as $k=>$v ){
				$result[] = Db::table("category")
					->where("id",$v)
					->value("name");
			}
			return implode("，",$result);
		}else{
			return null;
		}
	}

	public function getOrderDetail( $orderid ){
		$res = Db::table("order")
			->where("orderid",$orderid)
			->find();
		if($res){
			$res['detail'] = $this->getdetail($res['id']);
			if($res['adrid'] == 0 ){
				$res['phone'] = $res['zqname'];
				$res['username'] = $res['zqphone'];
			}else{
				$res['phone'] = $this->getphone($res['adrid']);
				$res['username'] = $this->getusername($res['adrid']);
			}
			if($res['deyamount'] != 0 ){
				$yf = array();
				$yf['fname'] = "运费";
				$yf['num'] = "/";
				$yf['total'] = $res['deyamount'];
				$res['detail'][] = $yf;
			}
			if($res['isbreakfast'] == 0 ){
				$res['types'] = "小吃";
			}else{
				$res['types'] = "早餐";
			}
			return $res;
		}
	}

	private function getdetail( $mainid ){
		$res = Db::table("order_detail")
			->where("mainid",$mainid)
			->select();
		return $res ? $res :null;
	}

	private function getphone( $adrid ){
		$res = Db::table("user_address")
			->where("id",$adrid)
			->value("phone");
		return $res ? $res : null;
	}

	private function getusername( $adrid ){
		$res = Db::table("user_address")
		         ->where("id",$adrid)
		         ->value("contacter");
		return $res ? $res : null;
	}

	public function getzqinfo( $uid ){
		$res = Db::table("zqinfo")
			->where("uid",$uid)
			->find();
		return $res ? $res : null;
	}

	public function notify( $orderid, $sign ){
		if( $orderid  && $sign ){
			//验签
			$selfsign = md5($orderid.config("paystr"));
			if($selfsign == $sign ){
				//查询订单
				$orderdata = Db::table("order")
				               ->where("orderid",$orderid)
				               ->find();
				if($orderdata){
					//修改默认店铺
					$storedata = $this->updataDefaultStore($orderdata['userid'],$orderdata['sid']);
					//修改积分
					$integraldata = $this->updateIntegral( $orderdata['userid'],$orderdata['amount'] );
					//修改自取人
					$zqdata = $this->updatezqinfo( $orderdata['userid'],$orderdata['zqname'],$orderdata['zqphone'] );
					if($storedata){
						$this->addErrorMessage( $orderid, self::getResponse("403",null,$storedata));
						return false;
					}
					if($integraldata){
						$this->addErrorMessage( $orderid, self::getResponse("403",null,$integraldata));
						return false;
					}
					if($zqdata){
						$this->addErrorMessage( $orderid,self::getResponse("403",null,$zqdata));
						return false;
					}
				}else{
					$this->addErrorMessage( $orderid, self::getResponse("403",null,"订单不存在！"));
					return false;
				}
			}else{
				$this->addErrorMessage( $orderid,self::getResponse("403",null,"验签出错！"));
				return false;
			}
		}else{
			$this->addErrorMessage($orderid, self::getResponse("403",null,"缺少参数！"));
			return false;
		}
	}

	private function addErrorMessage( $orderid, $message ){
		$data['name'] = $orderid;
		$data['createtime'] = DateTimeTool::getDate();
		$data['log'] = $message;
		Db::table("errorlog")
			->insert($data);

	}

	public function getOpenidByUser( $uid ){
		if( $uid ){
			 $res =  Db::table("home_user")
				->where("id",$uid)
				->value("openid");
			return $res ? $res : null;
		}
	}
}