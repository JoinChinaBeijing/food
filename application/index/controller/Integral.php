<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/11
 * Time: 11:05
 */

namespace app\index\controller;


use app\index\model\IntegralModel;
use think\Controller;
use think\Request;

class Integral extends Base  {

	public function listintegral(){
		$integralModel = new IntegralModel();
		if($_POST){
			$search = Request::instance()->post();
			if($search){
				$this->assign("sidDesc",$search['name'] );
			}

			$integralResult = $integralModel->getIntegralList( $search['name'] );
		}else{
			$this->assign("sidDesc","");
			$integralResult = $integralModel->getIntegralList();
		}
		
		$listJson = json_encode( $integralResult );
		$this->assign( "listJson", $listJson);
		return $this->fetch();
	}

	public function addIntegral(){
		$data = Request::instance()->post();
		$integralModel = new IntegralModel();
		return $integralModel->saveIntegral( $data );
	}
}