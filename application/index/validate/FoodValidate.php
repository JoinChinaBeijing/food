<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/9
 * Time: 10:24
 */

namespace app\index\validate;


use think\Validate;

class FoodValidate extends Validate {

	protected $rule = [
		'name'  =>  'require|max:32',
		'unit'  =>  'require|max:32',
		'price' =>  'require',
		'disstatus'=> 'require',
		'cid' => 'require',
		'status' => 'require',
	];

	protected $message  =   [
		'name.require' => '名称不能为空',
		'name.max' => '名称最长为32位',
		'unit.require'   => '单位不能为空',
		'unit.max'   => '单位最长为32位',
		'price.require'   => '价格必须',
		'disstatus.require'   => '打折状态必须',
		'cid.require'   => '分类必须',
		'status.require'   => '状态必须',

	];
}