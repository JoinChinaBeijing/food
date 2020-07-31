<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/11
 * Time: 11:05
 */
namespace app\index\model;


use app\common\model\CommonModel;
use app\index\validate\IntegralValivdate;
use think\Db;

class IntegralModel extends CommonModel {


	/**
	 * 查询积分列表
	 * @return false|null|\PDOStatement|string|\think\Collection
	 */
	public function getIntegralList($search = '')
	{
		if (empty($search))
		{
			$res = Db::table("integral")->select();
		}
		else
		{
			$res = Db::table("integral")->where('uid', $search)->select();
		}
		
		if( $res ){
			return $res;
		}else{
			return null;
		}
	}

	public function saveIntegral( $data ){
		if( $data ){
			$storeValidate = new IntegralValivdate();
			$result = $storeValidate->check( $data );
			if(false === $result ){
				return self::getResponse( "403", null, $storeValidate->getError());//数据有误
			}else{
				if($data["id"]){
					if($data["integral"] < 0 ){
						return self::getResponse("403",null,"积分不可以为负数");
					}
					$update = Db::table("integral")
					            ->where("id",$data["id"])
					            ->update($data);
					if($update){
						return self::getResponse("200",null,"修改成功");
					}else{
						return self::getResponse("403",null,"修改失败");
					}
				}else{
					return self::getResponse("403",null,"请求失败");
				}
			}
		}else {
			return self::getResponse("403",null,"数据有误");
		}
	}


}