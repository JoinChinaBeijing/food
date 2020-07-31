<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/3/15
 * Time: 11:36
 */

namespace app\index\controller;


use app\index\model\StorecateModel;
use think\Controller;
use think\Request;


class Storecate extends  Base  {

	/**
	 * 店铺、分类关联
	 * @return mixed
	 */
	public function liststorecate(){
		$storecateModel = new StorecateModel();
		$storeResult  = $storecateModel->getStores(1);
		$cateResult = $storecateModel->getcategories(1);
		$result = $storecateModel->getStoreCateList();
		$listJson = json_encode( $result );
		$this->assign( "listJson", $listJson);
		$this->assign("stores",$storeResult);
		$this->assign("categories",$cateResult);
		return $this->fetch();
	}

	/**
	 * 添加、编辑店铺和分类关系
	 * @return string
	 */
	public function addStoreCate(){
		if( $_POST ){
			$data = Request::instance()->post();
			$cityModel = new StorecateModel();
			return $cityModel->saveStorecate( $data );
		}
	}

}