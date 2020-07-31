<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/18
 * Time: 2:22
 * 时间操作类
 */

namespace app\common\model;




class DateTimeTool extends CommonModel  {


	/*获取日期,0为今天*/
	public static function getDate( $format = null ,$day = 0 ){
		if($format) return date( $format,strtotime("$day day"));
		return date("Y-m-d H:i:s",strtotime("$day day"));
	}

	public static function getTime(){
		return time();
	}
}