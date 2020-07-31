<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/20
 * Time: 16:58
 */

namespace app\home\model;


use app\common\model\CommonModel;
use app\home\validate\AddressValidate;
use think\Db;

class AddressModel extends CommonModel {

	public function insertAddress( $data ){
		if( $data ){
			$addressValidate = new AddressValidate();
			$checkres = $addressValidate->check( $data );
			$data = self::dataFilter($data);
			if( $checkres === false ){
				return self::getResponse( "403", null, $addressValidate->getError() );
			}else{
				if($data['id']){//编辑
					$rows = Db::table("user_address")
					         ->where("uid",$data["uid"])
					         ->where("type",0)
					         ->where("default",1)
					         ->find();
					if($rows){
						if($data['default'] == 1 ){
							$defaults["default"] = 0;
							Db::table("user_address")
							  ->where("uid",$data["uid"])
							  ->where("type",0)
							  ->where("default",1)
							  ->update($defaults);
						}
					}
					//把sort为1的先转为0
					$this->updateAddressSort($data["uid"],$data["store"]);
					$data['sort'] = 1;
					Db::table("user_address")
						->where("id",$data['id'])
						->update($data);
					return self::getResponse( "200", $data, "操作成功" );
				}else{
					$isexist = Db::table("user_address")
					             ->where("uid",$data["uid"])
								->where("type",0)
					             ->select();
					if($isexist){
						//把原来的排序改为0
						$sort['sort'] = 0;
						$sortdata = Db::table("user_address")
							->where("uid",$data["uid"])
							->where("store",$data['store'])
							->where("type",0)
							->where("sort",1)
							->find();
						if($sortdata){
							Db::table("user_address")
								->where("uid",$data["uid"])
								->where("store",$data['store'])
								->where("type",0)
								->where("sort",1)
								->update($sort);
						}
						$data['sort'] = 1;
						//是否默认
						if($data['default'] == 1){
							//修改默认地址
							$row = Db::table("user_address")
							         ->where("uid",$data["uid"])
									->where("type",0)
							         ->where("default",1)
							         ->find();
							if($row){
								$default["default"] = 0;
								Db::table("user_address")
								  ->where("uid",$data["uid"])
								  ->where("type",0)
								  ->where("default",1)
								  ->update($default);
							}
							$res = Db::table("user_address")
							         ->insertGetId($data);
						}else{
							$res = Db::table("user_address")
							         ->insertGetId($data);
						}
					}else{
						$res = Db::table("user_address")
						         ->insertGetId($data);
					}
				}
				if($res){
					$data["id"] = $res;
					return self::getResponse( "200", $data, "操作成功" );
				}else{
					return self::getResponse( "403", null, "添加失败，请重试" );
				}
			}
		}
	}

	private function updateAddressSort($uid, $store){
		Db::table("user_address")
			->where("uid",$uid)
			->where("store",$store)
			->where("type",0)
			->update(['sort' => 0]);
	}

	public function getAddressDataByUser( $uid,$store ){
		$res = Db::table("user_address")
			->where("uid", $uid)
			->where("store",$store)
			->where("type",0)
			->where('deleted', 0)
			->order("id desc")
			->select();
		return $res;
	}

	public function getDefaultAddressByUser( $uid , $store){
		//先查排序为1的
		$sortdata = Db::table("user_address")
		         ->where("uid", $uid)
		         ->where("store",$store)
		         ->where("sort",1)
				 ->where("type",0)
				 ->where('deleted', 0)
		         ->order("id desc")
				 ->limit(1)
		         ->find();
		if($sortdata){
			$res = $sortdata;
		}else{
			//然后的默认的
			$defaultdata = Db::table("user_address")
			         ->where("uid", $uid)
			         ->where("store",$store)
			         ->where("default",1)
			         ->where("type",0)
			         ->find();
			if($defaultdata){
				$res = $defaultdata;
			}else{
				//以上都没有就查最新的
				$limitdata = Db::table("user_address")
				         ->where("uid", $uid)
				         ->where("store",$store)
				         ->where("type",0)
				         ->order("id desc")
				         ->limit(1)
				         ->find();
				if($limitdata){
					$res = $limitdata;
				}else{
					$res = 0;
				}
			}
		}
			return $res;
	}

	public function updateSort( $uid, $store ){
		$data['sort'] = 0;
		Db::table("user_address")
			->where("uid",$uid)
			->where("store",$store)
			->where("type",0)
			->update($data);
	}

	public function deleteAddress( $id ){
		$row = Db::table("user_address")
			->where("id",$id)
			->update(['deleted' => 1]);
		if( $row ){
			return self::getResponse( "200", null, "删除成功" );
		}else{
			return self::getResponse( "403", null, "删除失败，请重试" );
		}
	}
}