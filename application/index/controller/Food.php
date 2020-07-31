<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/9
 * Time: 10:22
 */

namespace app\index\controller;


use app\index\model\FoodModel;
use think\Controller;
use think\Request;

class Food extends Base  {

	/**
	 * 食品管理
	 * @return mixed
	 */
	public function listfood(){

		$foodModel = new FoodModel();
		$categoryList = $foodModel->getCategoryByStatus(1);
		$storeList = $foodModel->getStoreList(1);
		$tasteList = $foodModel->getTasteList(1);
		if($_POST){
			$search = Request::instance()->post();
			if($search){
				$this->assign("sidDesc",$search['name'] );
			}
			$foodResult = $foodModel->getFoodList( $search );
		}else{
			$this->assign("sidDesc","");
			$foodResult = $foodModel->getFoodList(null);
		}
		$this->assign("tasteList",$tasteList);
		// $this->assign("storeList",$storeList);
		$this->assign("categories",$categoryList);
		$listJson = json_encode( $foodResult );
		$this->assign( "listJson", $listJson);
		return $this->fetch();
	}

	/**
	 * 添加、编辑食品
	 * @return string
	 */
	public function addfood(){
		if($_POST){
			$foodData = Request::instance()->post();
			$cityModel = new FoodModel();
			echo $cityModel->saveFood($foodData);
		}
	}

}