<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/4/23
 * Time: 16:51
 */

namespace app\breakfast\controller;



use app\breakfast\model\AddressModel;
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