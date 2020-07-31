<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/6
 * Time: 16:46
 */

namespace app\index\validate;


use think\Validate;

class CategoryValidate extends Validate {

	protected $rule = [
		'code'  =>  'require|max:10',
		'name'  =>  'require|max:32',
		'prints'  =>  'require',
		'status' => 'require|in:0,1'
	];

	protected $message  =   [
		'code.require' => '编码不能为空',
		'prints.require' => '打印机不能为空',
		'code.max' => '编码最长为10',
		'name.require'     => '分类描述不能为空',
		'name.max'     => '分类描述最多不能超过32个字符',
		'status.require'   => '状态信息不能为空',
		'status.in'   => '状态信息插入有误',
	];
}