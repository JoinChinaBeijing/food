<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/3/18
 * Time: 10:22
 */

namespace app\index\validate;


use think\Validate;

class TasteValidate extends Validate {

	protected $rule = [
		'name'  =>  'require|max:8',
		'status' => 'require|in:0,1'
	];

	protected $message  =   [
		'name.require'     => '口味名称不能为空',
		'name.max'     => '名称最多不能超过8个字符',
		'status.require'   => '状态信息不能为空',
		'status.in'   => '状态信息插入有误',
	];
}