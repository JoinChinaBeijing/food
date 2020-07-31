<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/18
 * Time: 0:15
 */

namespace app\common\model;


use think\Cache;

class CacheTool {

	public static function setCache( $type = "file", $name, $value, $expire = null){
		return Cache::store( $type )->set( $name, $value, $expire );
	}

	public static function getCache($type = "file", $name ){
		return Cache::store( $type )->get( config("Cache.redis.prefix"."_") .$name );
	}

	public static function rmCache( $type = "file",$name ){
		return Cache::store( $type )->rm( config("Cache.redis.prefix"."_") .$name );
	}
}