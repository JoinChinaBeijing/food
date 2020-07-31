<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/3/13
 * Time: 17:30
 */

namespace app\index\model;


use app\common\model\CommonModel;
use app\common\model\DateTimeTool;
use app\index\validate\SchoolValidate;
use think\Db;
use think\Exception;

class SchoolModel extends CommonModel {

	public function getSchoolList(){

		$res = Db::table("school")->select();
		if( $res ){
			foreach ($res as $k=>$v ){
				if($res[$k]['status'] == 1){
					$res[$k]['statusdesc'] = "是";
				}else{
					$res[$k]['statusdesc'] = "否";
				}
			}
			return $res;
		}
	}

	public function saveSchool( $data ){
		$data['createtime'] = DateTimeTool::getDate();
		$schoolValidata = new SchoolValidate();
		$result = $schoolValidata->check( $data );
		if( false === $result ){
			// 验证失败 输出错误信息
			return self::getResponse( "403", null, $schoolValidata->getError());//数据有误
		}else{
			//写入数据
			if( $data["id"] ){ //更新操作
				$dbresult = Db::table("school")
				              ->where( "id", $data["id"] )
				              ->update( $data );
			}else{//新增操作
				try{
					$row = Db::table("school")
					         ->where("name",$data['name'])
					         ->find();
					if($row){
						$dbresult = "学校已经存在";
					}else{
						$dbresult = Db::table("school")
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