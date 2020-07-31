<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/18
 * Time: 21:43
 */

namespace app\common\model;


use think\Model;

class CommonModel extends Model {

	//返回结果
	public static function getResponse( $code, $data = null, $message){
		$res  = array();
		$res['code'] = $code;
		$res['data'] = $data;
		$res['message'] = $message;
		return json_encode($res);//返回json中文
	}

	public static function getResponseObject( $code, $data = null, $message){
		$res  = array();
		$res['code'] = $code;
		$res['data'] = $data;
		$res['message'] = $message;
		return json_encode($res,true);//返回json中文
	}

	//表单数据的过滤
	public static function dataFilter( $data ){
		if(count($data) > 0 ){
			$array = array(" ","　","\t","\n","\r");
			foreach ($data as $k=>$v){
				if(is_array($data[$k])){
					foreach ($v as $kk=>$vv){
						if(is_array($data[$k][$kk])){
							$data[$k][$kk] = self::dataFilter($data[$k][$kk]);
						}else{
							$data[$k][$kk] = self::filter_Emoji(str_replace($array,"",nl2br(htmlspecialchars(trim($data[$k][$kk])))));
						}
					}
				}else{
					$data[$k] = self::filter_Emoji(str_replace($array,"",nl2br(htmlspecialchars(trim($data[$k])))));
				}
			}
			return $data;
		}
	}
	/**
	 * 过滤移动端emoji表情
	 * @param $str
	 *
	 * @return mixed
	 */
	 private static function filter_Emoji( $str ){
	    $str = preg_replace_callback(
	            '/./u',
	            function (array $match) {
		            return strlen($match[0]) >= 4 ? '' : $match[0];
	            },
	            $str);
	     return $str;
    }
}