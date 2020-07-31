<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/3/16
 * Time: 13:42
 */

namespace app\index\model;


use app\common\model\CommonModel;
use app\common\model\DateTimeTool;
use app\index\validate\StoreValidate;
use think\Db;

class StoreModel extends CommonModel {

	protected $table    = 'store';

	/**
	 * 获取店铺列表
	 * @return false|null|\PDOStatement|string|\think\Collection
	 */
	public function getStoreList(){
		$res = Db::table("store")->select();
		if( $res ){
			foreach ( $res as $k=>$v){
				if($res[$k]['status'] == 1){
					$res[$k]['statusDesc'] = '是';
				}else{
					$res[$k]['statusDesc'] = '否';
				}
				$res[$k]['cidDesc'] = $this->getCityNameByid($res[$k]['cid']);
				$res[$k]['school'] = $this->getSchoolByid($res[$k]['id']);
			}
//			dump($res);die;
			return $res;
		}else{
			return null;
		}
	}

	public function getSchoolByid( $id ){
		$res = Db::table("store_school")
			->where("sid",$id)
			->select();
		return $res ? $res : null;
	}

	/**
	 * 根据城市id 获取城市名称
	 * @param $id /城市id
	 *
	 * @return mixed|null
	 */
	private function getCityNameByid( $id ){
		if($id){
			$res = Db::table("city")
			         ->where('id',$id)
			         ->value("name");
			if($res){
				return $res;
			}else{
				return null;
			}
		}else{
			return null;
		}
	}

	/**
	 * 根据状态获取城市列表
	 * @param int $status 状态吗
	 *
	 * @return false|\PDOStatement|string|\think\Collection
	 */
	public function getCityList( $status = 1){
		$res = Db::table("city")
			->where("status",$status)
			->select();
		return $res;
	}

	/**
	 * 保存城市
	 * @param $data
	 *
	 * @return string
	 */
	public function savestore( $data ){
		if( $data ){
			$data['createtime'] = DateTimeTool::getDate();
			$storeValidate = new StoreValidate();
			$result = $storeValidate->check( $data );
			if(false === $result ){
				return self::getResponse( "403", null, $storeValidate->getError());//数据有误
			}else{
				if($data["id"]){
					$storedata['cid'] = $data['cid'];
					$storedata['name'] = $data['name'];
					$storedata['principal'] = $data['principal'];
					$storedata['phone'] = $data['phone'];
					$storedata['address'] = $data['address'];
					$storedata['status'] = $data['status'];
					$storedata['opentime'] = $data['opentime'];
					$storedata['closetime'] = $data['closetime'];
					$storedata['tips'] = $data['tips'];
					$storedata['createtime'] = $data['createtime'];
					$storedata['district'] = $data['district'];
					$update = Db::table("store")
						->where("id",$data["id"])
						->update($storedata);
					if($update){
						//添加关联学校
						if(count($data["school"]) > 0){
							Db::table("store_school")
							  ->where("sid",$data["id"])
							  ->delete();
							$schooldata['sid'] = $data["id"];
							foreach ( $data["school"] as $k=>$v){
								$schooldata['scid'] = $v;
								Db::table("store_school")
								  ->insert($schooldata);
							}
						}
						return self::getResponse("200",null,"修改成功");
					}else{
						return self::getResponse("403",null,"修改失败");
					}
				}else{
					$row = Db::table("store")
						->where("name",$data['name'])
						->find();
					if($row){
						return self::getResponse("403",null,"已经存在");
					}else{
						$storedata2['cid'] = $data['cid'];
						$storedata2['name'] = $data['name'];
						$storedata2['principal'] = $data['principal'];
						$storedata2['phone'] = $data['phone'];
						$storedata2['address'] = $data['address'];
						$storedata2['status'] = $data['status'];
						$storedata2['opentime'] = $data['opentime'];
						$storedata2['closetime'] = $data['closetime'];
						$storedata2['tips'] = $data['tips'];
						$storedata2['createtime'] = $data['createtime'];
						$storedata['district'] = $data['district'];
						$res = Db::table("store")
						         ->insertGetId($storedata2);
						if($res){
							if(count($data["school"]) > 0 ){
								//添加关联学校
								$schooldata['sid'] = $res;
								foreach ( $data["school"] as $k=>$v){
									$schooldata['scid'] = $v;
									Db::table("store_school")
									  ->insert($schooldata);
								}
							}
							return self::getResponse("200",null,"添加成功");
						}else{
							return self::getResponse("403",null,"数据有误");
						}
					}
				}
			}
		}else {
			return self::getResponse("403",null,"数据有误");
		}
	}


	/**
	 * 删除店铺
	 * @param $id
	 *
	 * @return string
	 */
	public function deleteStore( $id ){
		$dbresult = Db::table("store")
		              ->where("id", $id )
		              ->delete();
		if( $dbresult ) return self::getResponse( "200", null, "操作成功" );
		return self::getResponse( "403", null, $dbresult );//数据插入失败
	}


	public function getSchoolList( $status ){
		return Db::table("school")
			->where("status", $status)
			->select();
	}

}