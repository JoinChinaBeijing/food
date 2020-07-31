<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/3/13
 * Time: 17:27
 */

namespace app\index\controller;


use app\index\model\SchoolModel;
use think\Controller;
use think\Request;

class School extends Base  {

	public function listschool(){
		$schoolModel = new SchoolModel();
		$schoolData = $schoolModel->getSchoolList();
		$listJson = json_encode( $schoolData );
		$this->assign( "listJson", $listJson);
		return $this->fetch();
	}


	/**
	 * 添加学校
	 */
	public function addSchool(){
		if($_POST){
			$data = Request::instance()->post();
			$cityModel = new SchoolModel();
			return $cityModel->saveSchool( $data );
		}
	}
}