<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/29
 * Time: 1:24
 */

namespace app\common\model;


class MapLocation {

	private $ipurl = "https://restapi.amap.com/v3/ip";

	private $reverse = "https://restapi.amap.com/v3/geocode/regeo";

	//IP定位
	public function getCity( $type, $userip = null){
		$key = config("mapkey");
		$http = new HttpClient();
		if($userip == null ){
			return $http->httpGet("$this->ipurl?output=$type&key=$key");
		}else{
			return $http->httpGet("$this->ipurl?ip=$userip&output=$type&key=$key");
		}
	}

	//逆向地理位置定位
	public function getReverse( $location ){
		$key = config("mapkey");
		$http = new HttpClient();
		return $http->httpGet("$this->reverse?location=$location&key=$key&extensions=all&output=XML");
	}

}