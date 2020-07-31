<?php

namespace app\index\validate;
use think\Validate;

/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/6
 * Time: 19:45
 */
class CityValidate extends Validate {


	protected $rule = [
		'code'  =>  'require|max:10',
		'name'  =>  'require|max:32',
		'grade' =>  'require|in:1,2,3,4|max:1',
		'status' => 'require|in:0,1'
	];

	protected $message  =   [
		'code.require' => '城市编码不能为空',
		'code.max' => '城市编码最长为10',
		'name.require'     => '名称不能为空',
		'name.max'     => '名称最多不能超过25个字符',
		'grade.require'   => '城市等级不能为空',
		'grade.in'   => '城市代码不存在',
		'grade.max'   => '城市代码长度为1',
		'status.require'   => '状态信息不能为空',
		'status.in'   => '状态信息插入有误',

	];


}