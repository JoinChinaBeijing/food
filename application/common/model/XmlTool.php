<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/3/7
 * Time: 16:34
 */

namespace app\common\model;


class XmlTool {

	/**
	 * xml转json
	 * @param $xmlString
	 *
	 * @return string
	 */
	public static function xmlToJson( $xmlString ) {
		if(is_file( $xmlString )){

			$xml_array = simplexml_load_file( $xmlString );

		}else{

			$xml_array = simplexml_load_string( $xmlString );
		}

		$json = json_encode( $xml_array );

		return $json;
	}

	/**
	 * xml转arr
	 * @param $xmlString
	 *
	 * @return \SimpleXMLElement
	 */
	public static function xmlToArr( $xmlString ) {
		if( is_file( $xmlString ) ){

			$xml_array = simplexml_load_file( $xmlString );

		}else{

			$xml_array = simplexml_load_string( $xmlString );
		}

		return $xml_array;
	}
}