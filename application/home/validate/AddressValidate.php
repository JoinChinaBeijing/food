<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/20
 * Time: 17:10
 */

namespace app\home\validate;


use think\Validate;

class AddressValidate extends Validate {

	protected $rule = [
		'school'  =>  'require|max:64',
		'detail'  =>  'require|max:50',
		'contacter' =>  'require|max:6',
		'phone' => 'require|max:11'
	];

	protected $message  =   [
		'school.require' => '学校名称不能为空',
		'school.max' => '学校名称最长为64',
		'detail.require'     => '详细地址不能为空',
		'detail.max'     => '详细地址不能超过50个字符',
		'contacter.require'   => '联系人不能为空',
		'contacter.max'   => '联系人名称做多为6个字符',
		'phone.require'   => '联系电话不能为空',
		'phone.max'   => '联系电话最长为11位',

	];

}