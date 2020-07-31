<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/3/18
 * Time: 10:22
 */

namespace app\index\controller;


use app\index\model\TasteModel;
use think\Request;

class Taste extends Base {

	public function listtaste(){
		$tasteModel = new TasteModel();
		$tasteResult = $tasteModel->getTasteList();
		$listJson = json_encode( $tasteResult );
		$this->assign( "listJson", $listJson);
		return $this->fetch();
	}

	public function addTaste(){
		if($_POST){
			$data = Request::instance()->post();
			$cityModel = new TasteModel();
			return $cityModel->saveTaste( $data );
		}
	}
}