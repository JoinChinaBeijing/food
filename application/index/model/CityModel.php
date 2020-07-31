<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/6
 * Time: 19:45
 */

namespace app\index\model;


use app\common\model\CommonModel;
use app\common\model\DateTimeTool;
use app\index\validate\CityValidate;
use think\Db;
use think\exception\ErrorException;

class CityModel extends CommonModel   {

	protected $table    = 'city';

	public function getCityList(){
		$res = Db::table("city")->select();
		if( $res ){
			foreach ( $res as $k=>$v ){
				if( $res[$k]['status'] == 1 ){
					$res[$k]['statusDesc'] = '是';
				}else {
					$res[$k]['statusDesc'] = '否';
				}
				switch ($res[$k]['grade']){
					case 1:
						$res[$k]['gradeDesc'] = '省级';
						break;
					case 2:
						$res[$k]['gradeDesc'] = '市级';
						break;
					case 3:
						$res[$k]['gradeDesc'] = '县级';
						break;
					case 4:
						$res[$k]['gradeDesc'] = '镇级';
						break;
					default:
						$res[$k]['gradeDesc'] = '其他';
						break;
				}
			}
			return $res;
		}else{
			return null;
		}
	}


	/**
	 * @param $data
	 *
	 * @return string
	 */
	public function saveCity( $data ){
		$data['createtime'] = DateTimeTool::getDate();
		$city = new CityValidate();
		$result = $city->check( $data );
		if( false === $result ){
			// 验证失败 输出错误信息
			return self::getResponse( "403", null, $city->getError());//数据有误
		}else{
			//写入数据
			if( $data["id"] ){ //更新操作
				$dbresult = Db::table("city")
					->where( "id", $data["id"] )
				              ->update( $data );
			}else{//新增操作
				try{
					$row = Db::table("city")
						->where("code",$data['code'])
						->find();
					if($row){
						$dbresult = "编码已经存在";
					}else{
						$dbresult = Db::table("city")
						              ->insert( $data );
					}
				}catch (ErrorException $e){
					$dbresult = $e->getMessage();
				}
			}
			if( $dbresult === 1 ){
				return self::getResponse( "200", null, "操作成功" );
			}else{
				return self::getResponse( "403", null, $dbresult );//数据插入失败
			}
		}
	}

	/**
	 * @param $id
	 *
	 * @return string
	 */
	public function deleteCity( $id ){
		$dbresult = Db::table("city")
			->where("id", $id )
			->delete();
		if( $dbresult ) return self::getResponse( "200", null, "操作成功" );
		return self::getResponse( "403", null, $dbresult );//数据插入失败
	}



}