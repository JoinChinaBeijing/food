<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/3/6
 * Time: 16:08
 */

namespace app\common\model;


class PaysignTool {

	public static function getPayParam($dataArr){
		$initStr = "";
		$params = '';
		ksort($dataArr);
		reset($dataArr);
		foreach ($dataArr as $key => $value) {
			$initStr .= '&' . $key . '=' . $value;
		}
		$initStr = substr($initStr,1);
		foreach ($dataArr as $key => $value) {
			 $params .= '&' . $value;
		}
		$params = substr($params,1);
		$sign = md5($params.config("paystr"));
		return $initStr . "&sign=$sign";
	}

	public static function getPaySign( $paramsArr ){
		$initStr = "";
		ksort($paramsArr);
		reset($paramsArr);
		foreach ($paramsArr as $key => $value) {
			$initStr .= '&' . $key . '=' . $value;
		}
		$initStr = substr($initStr,1);
		return md5($initStr.config("paystr"));
	}




}