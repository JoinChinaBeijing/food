<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/29
 * Time: 21:49
 */

namespace app\common\model;



use think\Db;
use think\Log;

class Logger {

	public static function addLogger( $content , $leavel = "INFO" ){
		Log::record( $content, $leavel);
	}

	public static function addInterfaceLogger( $key = "default" , $content , $leavel = "INFO" ){
		Log::record( $content, $leavel);
		$logData['key'] = $key;
		$logData['content'] = $content;
		Db::table("interfacelog")
			->insert(json_encode($logData),true);
	}
}