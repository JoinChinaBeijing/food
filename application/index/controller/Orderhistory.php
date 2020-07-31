<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Config;
use think\Request;

class Orderhistory extends Controller
{
    // 获取订单打印数据
    public function index()
    {
        
        Db::startTrans();
        // 上个月日期
        $date = date('Y-m-01 00:00:00');

        
        try
        {
            // 上个月的数据进行迁移
            $where = "SELECT * FROM `order` WHERE createtime <= '$date'";
            $result = Db::query("INSERT INTO `order_history` $where");

            // 删除order迁移后的数据
            $delResult = Db::query("DELETE FROM `order` WHERE createtime <= '$date'");

            // 提交事务
            Db::commit();
        }
        catch (\Exception $e)
        {
            Db::rollback();
            $sql = isset($e->getdata()['Database Status']['Error SQL']) ? $e->getdata()['Database Status']['Error SQL'] : '其他错误';
            $result = ['message' => $e->getMessage() . ' SQL: ' . $sql];
            $this->errorlog($result);
            
        }

        echo 'success';
    }

    public function errorlog($result)
    {
        $now = date('Y-m-d H:i:s');
        $data = ['name' => '迁移错误', 'log' => $result['message'], 'createtime' => $now];
        Db::table('errorlog')->insert($data);
    }

}
