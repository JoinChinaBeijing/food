<?php
namespace app\index\controller;

use think\Db;

class Index extends Base
{
    public function index()
    {
	    return $this->fetch();
    }
}
