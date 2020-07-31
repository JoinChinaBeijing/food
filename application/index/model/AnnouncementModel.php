<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/6
 * Time: 12:46
 */

namespace app\index\model;


use app\common\model\CommonModel;
use app\common\model\DateTimeTool;
use app\index\validate\AnnouncementValidate;
use think\Db;
use think\exception\ErrorException;

class AnnouncementModel extends CommonModel {

	public function getAnnouncementList(){
		$res = Db::table("announcement")->select();
		if( $res ){
			foreach ( $res as $k=>$v ){
				$store = $this->getSoreNameById($res[$k]['sid']);
				if($store != null ){
					$res[$k]['storename'] = $store['name'];
				}else{
					$res[$k]['storename'] = null;
				}
				if($res[$k]['status'] == 1 ){
					$res[$k]['statusdesc'] = "启用";
				}else{
					$res[$k]['statusdesc'] = "禁用";
				}
			}
			return $res;
		}else{
			return null;
		}
	}

	private function getSoreNameById( $id ){
		$res = Db::table("store")
			->where("id",$id)
			->find();
		return $res ? $res : null;
	}

	public function getStores(){
		$res =  Db::table("store")
			->select();
		return $res ? $res : null;
	}

	public function saveAnnouncement($data){
		$data['createtime'] = DateTimeTool::getDate();
		$announcementValidate = new AnnouncementValidate();
		$result = $announcementValidate->check( $data );
		if( false === $result ){
			// 验证失败 输出错误信息
			return self::getResponse( "403", null, $announcementValidate->getError());//数据有误
		}else{
			//写入数据
			if( $data["id"] ){ //更新操作
				$dbresult = Db::table("announcement")
				              ->where( "id", $data["id"] )
				              ->update( $data );
			}else{//新增操作
				try{
					$row = Db::table("announcement")
					         ->where("sid",$data['sid'])
					         ->find();
					if($row){
						$dbresult = "该店铺公告存在，请编辑";
					}else{
						$dbresult = Db::table("announcement")
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
}