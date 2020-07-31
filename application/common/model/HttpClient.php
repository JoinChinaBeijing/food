<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/29
 * Time: 21:28
 */

namespace app\common\model;


class HttpClient {

	public function httpGet($url)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl,CURLOPT_HEADER, false);
		$output = curl_exec($curl);
		$info = curl_getinfo($curl);
		curl_close($curl);
		return $output;
	}

	public function httpPost($url, $data, $header = null)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl,CURLOPT_HEADER,0);
		if (!empty($data)) {
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		if ($header != null) {
			curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		$info = curl_getinfo($curl);
		curl_close($curl);
		return $output;
	}

	public function httpPostJson($url, $data)
	{
		$header = array();
		$header[] = 'Content-type:application/json';
		return $this->httpPost($url, $data, $header);
	}
}