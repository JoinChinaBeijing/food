<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/3/16
 * Time: 13:42
 */

namespace app\index\validate;


use think\Validate;

class StoreValidate extends Validate {

	protected $rule = [
		'cid'  =>  'require',
		'name'  =>  'require|max:64',
		'principal' =>  'require|max:32',
		'phone' =>  'require|max:11',
		'address' =>  'require|max:64',
		'status' => 'require|in:0,1'
	];

	protected $message  =   [
		'cid.require' => '所属城市不能为空',
		'name.require'     => '店铺名称不能为空',
		'name.max'     => '店铺名称最多不能超过64个字符',
		'principal.require'   => '负责人姓名不能为空',
		'principal.max'   => '负责人姓名不能超过32个字符',
		'phone.require'   => '负责人联系电话不能为空',
		'phone.max'   => '负责人联系电话长度最长为11位',
		'address.require'   => '联系地址不能为空',
		'address.max'   => '联系地址长度最长为64位',
		'status.require'   => '状态信息不能为空',
		'status.in'   => '状态信息插入有误',

	];
}