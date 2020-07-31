<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/3/18
 * Time: 10:22
 */

namespace app\index\model;


use app\common\model\CommonModel;
use app\common\model\DateTimeTool;
use app\index\validate\TasteValidate;
use think\Db;
use think\Exception;

class TasteModel extends CommonModel {

	public function getTasteList(){
		$res = Db::table("taste")
			->select();
		if($res){
			foreach ($res as $k=>$v ){
				if($res[$k]['status'] == 1){
					$res[$k]['statusDesc'] = "是";
				}else{
					$res[$k]['statusDesc'] = "否";
				}
			}

			return $res;
		}else{
			return null;
		}
	}


	public function saveTaste( $data ){
		$data['createtime'] = DateTimeTool::getDate("Y-m-d H:i:s");
		$tasteValidate = new TasteValidate();
		$result = $tasteValidate->check( $data );
		if( false === $result ){
			// 验证失败 输出错误信息
			return self::getResponse( "403", null, $tasteValidate->getError());//数据有误
		}else{
			//写入数据
			if( $data["id"] ){ //更新操作
				$dbresult = Db::table("taste")
				              ->where( "id", $data["id"] )
				              ->update( $data );
			}else{//新增操作
				try{
					$row = Db::table("taste")
					         ->where("name",$data['name'])
					         ->find();
					if($row){
						$dbresult = "口味已经存在";
					}else{
						$dbresult = Db::table("taste")
						              ->insert( $data );
					}
				}catch (Exception $e){
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
}