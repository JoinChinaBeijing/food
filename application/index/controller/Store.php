<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/3/16
 * Time: 13:42
 */

namespace app\index\controller;


use app\index\model\StoreModel;
use think\Controller;
use think\Request;

class Store extends Base  {

	/**店铺管理
	 * @return mixed
	 */
	public function liststore()
	{
		$storeModel = new StoreModel();
		$storeResult = $storeModel->getStoreList();
		$cityList = $storeModel->getCityList(1);
		$school = $storeModel->getSchoolList(1);
		$this->assign("schoolList",$school);
		$this->assign("cityList",$cityList);
		$listJson = json_encode( $storeResult );
		$this->assign( "listJson", $listJson);
		return $this->fetch();
	}

	/**
	 * 添加、编辑店铺
	 * @return string
	 */
	public function addstore(){
		if($_POST){
			$requestData = Request::instance()->post();
			$storeModel = new StoreModel();
			return $storeModel->savestore($requestData);
		}
	}

	/**
	 * 删除店铺
	 */
	public function deleteStore(){
		$id = input("id");
		$cityModel = new StoreModel();
		echo $cityModel->deleteStore( $id );
	}



}