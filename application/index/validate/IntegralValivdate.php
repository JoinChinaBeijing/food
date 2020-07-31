<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/11
 * Time: 11:06
 */

namespace app\index\validate;


use think\Validate;

class IntegralValivdate extends Validate {

	protected $rule = [
		'uid'  =>  'require',
		'integral'  =>  'require|number|min:0',

	];

	protected $message  =   [
		'uid.require' => '用户不能为空',
		'integral.require'     => '至少包含一个分类',
		'integral.number'   => '积分为整数',

	];
}