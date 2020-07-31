<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/6
 * Time: 16:42
 */

namespace app\index\controller;


use app\index\model\CategoryModel;
use think\Controller;
use think\Request;

class Category extends Base  {

	public function listcategory(){
		$categoryModel = new CategoryModel();
		$categoryResult = $categoryModel->getCategoryList();
		$listJson = json_encode( $categoryResult );
		$printsList = $categoryModel->getPrints();
		$this->assign( "listJson", $listJson);
		$this->assign( "printsList", $printsList);
		return $this->fetch();
	}

	public function addCategory(){
		if($_POST){
			$requestData = Request::instance()->post();
			$storeModel = new CategoryModel();
			return $storeModel->saveCategory($requestData);
		}
	}

}