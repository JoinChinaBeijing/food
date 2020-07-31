<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/20
 * Time: 16:51
 */

namespace app\home\controller;


use app\home\model\AddressModel;
use think\Request;

class Address extends Base  {

	public function addAddress(){
		if( $_POST ){
			$postdata = Request::instance()->post();
			$postdata['uid'] = $this->uid;
			$addressModel = new AddressModel();
			$result = $addressModel->insertAddress($postdata);
			echo $result;
		}

	}

	public function delete(){
		$id = Request::instance()->post("id");
		$addressModel = new AddressModel();
		echo $addressModel->deleteAddress( $id );
	}
}