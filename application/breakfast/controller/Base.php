<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/4/20
 * Time: 22:16
 */

namespace app\breakfast\controller;


use app\common\model\CacheTool;
use app\wechat\model\Wechat;
use think\Controller;

class Base extends Controller  {

	protected $uid;

	/**
	 * Base constructor.
	 * 用作授权
	 */
	public function __construct() {
		parent::__construct();
		$token = session('token');
		$this->uid = CacheTool::getCache("redis", $token);

		if (empty($this->uid))
		{
			$wechat = new Wechat();
			$wechat->authorize();
//			 $this->uid = 3;
		}
	}
}