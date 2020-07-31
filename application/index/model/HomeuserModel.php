<?php

namespace app\index\model;

use app\common\model\CommonModel;
use think\Db;

class HomeuserModel extends CommonModel
{

    protected $table    = 'home_user';

    /**
     * 获取前台用户信息
     * @param $code //城市代码
     *
     * @return string
     */
    public function getHomeuserIntegral($uid)
    {
        $userIntergral = Db::table('home_user')->alias('hu')
                         ->join('integral_total i', 'hu.id = i.uid', 'LEFT')
                         ->field('hu.id, hu.nickname, i.total_integral, i.exchange_integral,(i.total_integral-i.exchange_integral) as last')
                         ->where('hu.id', $uid)
                         ->select();

        foreach ($userIntergral as $key => $user)
        {
            $userIntergral[$key]['total_integral'] = $user['total_integral'] == '' ? 0 : $user['total_integral'];
            $userIntergral[$key]['exchange_integral'] = $user['exchange_integral'] == '' ? 0 : $user['exchange_integral'];
            $userIntergral[$key]['last'] = $user['last'] == '' ? 0 : $user['last'];
        }

        return $userIntergral;
    }
}