<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/3/15
 * Time: 11:36
 */

namespace app\index\model;


use app\common\model\CacheTool;
use app\common\model\CommonModel;
use app\index\validate\StoreCateValidate;
use think\Db;

class StorecateModel extends CommonModel {

	/**
	 * 获取列表
	 * @return false|null|\PDOStatement|string|\think\Collection
	 */
	public function getStoreCateList(){
		$res = Db::table("store_category")->select();
		if( $res ){
			foreach ( $res as $k=>$v ){
				if( $res[$k]['status'] == 1 ){
					$res[$k]['statusDesc'] = '是';
				}else{
					$res[$k]['statusDesc'] = '否';
				}
				$res[$k]['sidDesc'] = $this->getStoreNameByid($res[$k]['sid']);
				$res[$k]['cidsDesc'] = explode(",",$res[$k]['cids']);

				if(count($res[$k]['cidsDesc']) > 0 ){
					$storeNameArr = array();
					foreach ($res[$k]['cidsDesc'] as $kk=>$vv ){
						$cateRes = $this->getCategoryNameByid( $vv );
						if(is_array($cateRes)){
							$storeNameArr[] = $cateRes['name'];
						}else{
							$storeNameArr[] = "";
						}

					}
					if(count($storeNameArr) > 0 ){
						$res[$k]['cidsDesc'] = implode(",",$storeNameArr);
					}
				}
			}
			return $res;
		}else{
			return null;
		}
	}

	/**
	 * 根据id查询name
	 * @param $id
	 *
	 * @return mixed
	 */
	private function getStoreNameByid( $id ){
		if( $id ){
			$res = Db::table("store")
				->where("id",$id)
				->value("name");
			return $res;
		}
	}


	/**
	 * 根据id获取分类
	 * @param $id
	 *
	 * @return array|false|null|\PDOStatement|string|\think\Model
	 */
	private function getCategoryNameByid( $id ){
		if( $id ){
			$res = Db::table("category")
				->where('id',$id)
				->find();
			if($res){
				return $res;
			}else{
				return "";
			}
		}else{
			return null;
		}
	}

	/**
	 * 添加编辑店铺和分类的关系
	 * @param $data
	 *
	 * @return string
	 */
	public function saveStorecate( $data){
		//一个店铺不可以重复添加类别
		if( $data ){
			$data['cids'] = implode(",",$data['cids']);
			$storeValidate = new StoreCateValidate();
			$result = $storeValidate->check( $data );
			if(false === $result ){
				return self::getResponse( "403", null, $storeValidate->getError());//数据有误
			}else{
				if($data["id"]){
					$update = Db::table("store_category")
						            ->where("id",$data["id"])
						            ->update($data);
					if($update){
						CacheTool::rmCache("redis","store"."-".$data["sid"]);
						return self::getResponse("200",null,"修改成功");
					}else{
						self::getResponse("403",null,"数据错误");
					}
				}else{
					$row = Db::table("store_category")
					         ->where("sid",$data['sid'])
					         ->find();
					if($row){
						return self::getResponse("403",null,"已经存在");
					}else{
						$res = Db::table("store_category")
						         ->insert($data);
						CacheTool::rmCache("redis","store"."-".$data["sid"]);
						if($res){
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
	 * 根据状态获取店铺
	 * @param $status
	 *
	 * @return false|null|\PDOStatement|string|\think\Collection
	 */
	public function getStores( $status ){
		$res = Db::table("store")
			->where('status',$status)
			->select();
			return $res ? $res :null;
	}

	/**
	 * 根据状态获取分类列表
	 * @param $status
	 *
	 * @return false|null|\PDOStatement|string|\think\Collection
	 */
	public function getcategories($status){
		$res = Db::table("category")
		         ->where('status',$status)
		         ->select();
		return $res ? $res :null;
	}

}