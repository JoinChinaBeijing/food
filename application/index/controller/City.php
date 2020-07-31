<?php
namespace app\index\controller;
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/6
 * Time: 19:44
 */

use app\index\model\CityModel;
use think\Controller;
use think\Db;
use think\Request;

class City extends Base  {

	/**
	 * @return mixed
	 * 城市首页
	 */
	public function listcity(){
		$cityModel = new CityModel();
		$cityResult = $cityModel->getCityList();
		$listJson = json_encode( $cityResult );
		$this->assign( "listJson", $listJson);
		return $this->fetch();
	}

	/**
	 * 添加城市
	 */
	public function addCity(){
		if($_POST){
			$data = Request::instance()->post();
			$cityModel = new CityModel();
			return $cityModel->saveCity( $data );
		}
	}

	/**
	 * 删除城市
	 */
	public function deleteCity(){
		$id = input("id");
		$cityModel = new CityModel();
		echo $cityModel->deleteCity( $id );
	}
}