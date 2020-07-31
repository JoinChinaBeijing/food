<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/7
 * Time: 10:55
 */

namespace app\index\controller;


use app\index\model\FoodModel;
use think\Controller;
use think\Request;

class Estimate extends Base  {

	/**
	 * 估清管理
	 * @return mixed
	 */
	public function index(){

		$foodModel = new FoodModel();
		if($_POST){
			$search = Request::instance()->post();
			if($search){
				$this->assign("sidDesc",$search['name'] );
			}
			$foodResult = $foodModel->getFoodStoreList( $search );
		}else{
			$this->assign("sidDesc","");
			$foodResult = $foodModel->getFoodStoreList();
		}
		$listJson = json_encode( $foodResult );
		$this->assign( "listJson", $listJson);
		return $this->fetch();
	}

	/**
	 * 添加、编辑食品
	 * @return string
	 */
	public function updatestatus(){
		if($_POST)
		{
			$data = Request::instance()->post();

			$foodModel = new FoodModel();
			$result = $foodModel->updateStatus($data['fid'], $data['sid'], $data['status']);

			echo json_encode($result);exit;
		}
	}

}