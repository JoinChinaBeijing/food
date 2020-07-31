<?php
namespace app\index\controller;
use think\Db;
use think\Config;
use think\Request;
use app\common\model\MapLocation;
use app\common\model\XmlTool;
use app\home\model\IndexModel;
use app\common\model\CacheTool;

class Prints extends Base
{
    // 获取订单打印数据
    public function index()
    {
        $user = session('user');

        if ($user['name'] == 'lt&qgy')
        {
            echo '超级管理员不能进行打印';exit;
        }

        $this->assign( "date", date('Y-m-d'));
        return $this->fetch();
    }

    // 获取单据打印信息
    public function prints()
    {
        header("Access-Control-Allow-Origin:*");

        // 获取本店ID
        $user = session('user');
        $sid  = $user['sid'];

        $begin = date('Y-m-d 00:00:00');
        $end   = date('Y-m-d 23:59:59');
        $order = Db::table('order')->where('status = "1"')
                                   ->where('createtime', '>', $begin)
                                   ->where('createtime', '<', $end)
                                   ->where('printStatus', '0')
                                   ->where('sid', $sid)
                                   ->order('id asc')
                                   ->limit(1)
                                   ->find();

        if (!empty($order))
        {
            if ($order['adrid'] == 0)
            {
                $order['address']    = '自取';
                $order['contacter']  = $order['zqname'];
                $order['phone']      = $order['zqphone'];
            }
            else
            {
                // 获取联系人信息
                $address = Db::table('user_address')->where('id', $order['adrid'])
                                                    ->field('concat(school, detail) as address, contacter, phone')
                                                    ->find();
                $order['address']    = $address['address'];
                $order['contacter'] = $address['contacter'];
                $order['phone']     = $address['phone'];
            }

            // 获取门店信息
            $order['storeName'] = Db::table('store')->where('id', $order['sid'])
                                       ->value('name');

            // 获取单据详细信息
            $orderDetail = Db::table('order_detail')->alias('od')
                                                    ->join('category c', 'c.id = od.cid', 'LEFT')
                                                    ->where('od.mainid', $order['id'])
                                                    ->field('od.fname, od.price, od.num, od.total, c.prints')
                                                    ->select();


            $order['detail'] = $orderDetail;

            // 更改预打印状态
            Db::table('order')->where('id', $order['id'])->setField('printStatus', 1);

            // 获取未打印信息
            $order['unPrintNum'] = Db::table('order')
                                   ->where('status = "1"')
                                   ->where('createtime', '>', $begin)
                                   ->where('createtime', '<', $end)
                                   ->where('printStatus', '0')
                                   ->where('sid', $sid)
                                   ->count();

            // 是否分单
            $printSetting = CacheTool::getCache("redis", 'print');
            if (empty($printSetting))
            {
                $printSetting = Db::table('setting')->where('name', 'print')
                                                    ->value('status');

                CacheTool::setCache('redis', 'print', $printSetting);
            }

            $order['printSetting'] = $printSetting;
        }

        echo json_encode($order);
        exit;
    }

    // 更改单据打印状态
    public function updatePrints()
    {
        $id = Request()->post('id');

        Db::table('order')->where('id', $id)->setField('delivery', 1);
    }

    // 打印机管理页面
    public function manage()
    {
        $prints = Db::table('prints')->select();

        $this->assign( "prints", json_encode($prints));
        return $this->fetch();
    }

    // 添加打印机
    public function manageAddPrint()
    {
        $data = Request()->post();

        $data['createtime'] = date('Y-m-d H:i:s');

        try
        {
            if (isset($data['id']) && !empty($data['id']))
            {
                Db::table('prints')->update($data);
            }
            else
            {
                Db::table('prints')->insert($data);
            }
        }
        catch (\Exception $e)
        {
            echo json_encode(array('code' => 403, 'message' => $e->getMessage()));
            exit;
        }

        echo json_encode(array('code' => 200, 'message' => '提交成功'));exit;
    }
}
