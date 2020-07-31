<?php
namespace app\home\model;
use app\common\model\CommonModel;
use think\Db;

/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/17
 * Time: 12:44
 */
class IndexModel extends CommonModel {

	public function getIndexData( $store){
		//获取城市
		//获取城市下的店铺
		//获取每个店铺下的分类
		//获取每个分类下的菜品
		$stores = Db::table("store")
			->where("id",$store)
			->find();
		if($stores){
			$stores['categories'] = $this->getCategoriesBySid($stores['id']);
			$cids = array_keys($stores['categories']);
			if($stores['categories'] !=null){
				$foodsdata = $this->getFoodsBystore($stores['id']);
					foreach ($foodsdata as $kkk=>$vvv ){
						if(in_array($vvv['cid'],$cids)){
							$stores['categories'][$vvv['cid']]['foods'][] = $vvv;
						}
					}
				foreach ($stores['categories'] as $k=>$v ){
					if(!isset($v['foods'])){
						unset($stores['categories'][$k]);
					}else{
						$rows = count($v['foods']);
						$highenum = ($rows + 2 ) * 12;
						$stores['categories'][$k]['viewsize'] = $highenum . "vh";
					}
				}
			}
			return $stores;
		}else{
			return null;
		}
	}

	private function getCategoriesBySid( $id ){
		if( $id ){
			$res = Db::table("store_category")
			         ->where("sid",$id)
			         ->find();
			if($res){
				$cateArr = explode(",",$res['cids']);
				if(count($cateArr)> 0 ){
					$result = array();
					for ($i=0;$i<count($cateArr);$i++){
						$result[$cateArr[$i]] = $this->getCate($cateArr[$i]);
					}
					return $result;
				}else{
					return null;
				}
			}else{
				return null;
			}
		}

	}

	private function getCate( $id ){
		if( $id ){
			return Db::table("category")
				->where("id",$id)
				->find();
		}else{
			return null;
		}
	}

	private function getFoodsBystore($sid ){
		if( $sid ){
			$res = Db::table("food")->alias("f")
				->join("store_food s","f.id=s.fid",'LEFT')
				->where("s.sid",$sid)
				->order("sort desc")
				->where("f.status",1)
				->field("f.id,name,picdate,pic,price,discount,cid,s.sid,disstatus,unit,s.status")
				->select();
			if( $res ){
				foreach ($res as $k=>$v ){
					$res[$k]['pic'] = $res[$k]["picdate"]."/".$res[$k]["pic"];
					$res[$k]["disprice"] = $res[$k]["price"] * $res[$k]["discount"];
				}
				return $res;
			}else{
				return null;
			}
		}else{
			return null;
		}
	}

	public function getIntegralByUid( $uid ){
		if( $uid ){
			$res = Db::table("integral")
				->where("uid",$uid)
				->find();
			if($res){
				return $res['integral'];
			}else{
				return 0;
			}
		}
	}

	public function getCityByCode( $code ){
		$res = Db::table("city")
			->where("code" ,$code )
			->find();
		if( $res ){
			return $res;
		}else{
			return null;
		}
	}

	public function getDeaStore( $uid ){
		$res = Db::table("user_store")
			->where("type","food")
			->where("uid",$uid)
			->find();
		if($res){
			return $res['sid'];
		}else{
			return null;
		}
	}

	public function getStoredata( $id ){
		$res =  Db::table("store")
			->where("id",$id)
			->find();
		if($res){
			if($res['status'] == 0){
				$res['statusdesc'] = $res['tips'];
			}else{
				$time = date("H:i",time());
				if($res['opentime'] > $time || $res['closetime'] < $time){
					$res['status'] = 0;
					$res['statusdesc'] = "本店的营业时间为：".$res['opentime']."-".$res['closetime'].",感谢您的关注。";
				}else{
					$res['statusdesc'] = null;
				}
			}
			return $res;
		}
	}

	public function getAnnouncement( $store ){
		$res = Db::table("announcement")
			->where("status",1)
			->where("sid",$store)
			->find();
		return $res ? $res :null;
	}

	public function getMastChoseFoodsByCidSid( $cid, $sid){
		$foodresult = Db::table("food")->alias("f")
		                ->join("store_food s","f.id=s.fid",'LEFT')
		                ->where("ischose",1)
		                ->where("f.status",1)
		                ->where("s.status",1)
		                ->where('s.sid',$sid)
		                ->where("f.cid",$cid)
		                ->field("f.id,name")
						->select();
		return $foodresult ? $foodresult : null;
	}

}