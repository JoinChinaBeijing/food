<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/3/15
 * Time: 11:37
 */

namespace app\index\validate;


use think\Validate;

class StoreCateValidate extends Validate {

	protected $rule = [
		'sid'  =>  'require',
		'cids'  =>  'require',
		'status' => 'require|in:0,1'
	];

	protected $message  =   [
		'sid.require' => '店铺不能为空',
		'cids.require'     => '至少包含一个分类',
		'status.require'   => '状态信息不能为空',
		'status.in'   => '状态信息插入有误',

	];
}