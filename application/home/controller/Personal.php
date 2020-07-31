<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/3/11
 * Time: 19:51
 */

namespace app\home\controller;


use app\home\model\PersonalModel;

class Personal extends Base {

	public function index(){
		$personalModel = new PersonalModel();
		$personalData = $personalModel->getPersonalData($this->uid);
		$this->assign("personalData",$personalData);
		return $this->fetch();
	}
}