<?php

namespace app\index\validate;
use think\Validate;

class UserValidate extends Validate {

    protected $rule = [
        'name' => 'require|max:50',
        'mobile' => 'require|number',
        'sid' => 'require',
        'role_id' => 'require|number'
    ];

    protected $message = [
        'name.require' => '姓名不能为空',
        'name.max' => '姓名不能长于50个字符',
        'mobile.require' => '电话不能为空',
        'mobile.number' => '电话必须是数字',
        'role_id.require' => '角色必须选择',
        'role_id.number' => '角色必须是数字ID',
        'sid.require' => '店铺必须选择',
    ];
}