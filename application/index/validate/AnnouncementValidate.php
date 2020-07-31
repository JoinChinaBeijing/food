<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/2/6
 * Time: 12:50
 */

namespace app\index\validate;


use think\Validate;

class AnnouncementValidate extends Validate {

	protected $rule = [
		'sid'  =>  'require|max:10',
		'status' => 'require|in:0,1',
		'content'=>'require|max:100'
	];

	protected $message  =   [
		'sid.require' => '店铺不能为空',
		'content.require'     => '公告不能为空',
		'content.max'     => '公告最多不能超过100个字符',
		'status.require'   => '状态信息不能为空',
		'status.in'   => '状态信息插入有误',

	];
}