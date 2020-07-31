<?php
namespace app\wechat\controller;
use think\Controller;
use app\wechat\model\Wechat;
use app\common\model\CacheTool;
use think\Loader;
use think\Config;
use think\Cache;
use think\Db;

class Index extends Controller
{
    // 微信model
    protected $wechatModel;

    public function __construct()
    {
        parent::__construct();
        $this->wechatModel = new Wechat();
    }

    /**
     * Class Login   授权登录页面
     * @package app\wechat\controller
     */
    // public function index()
    // {
    //     $url = $this->wechatModel->authorize();
    //     $this->redirect($url);
    // }

    // 获取用户信息
    public function getUserInfo()
    {
        //获取code
        $code = $_GET["code"];

        $res = $this->wechatModel->getUserInfo($code);

        if (isset($res['errcode']))
        {
            echo $res['errmsg'];exit;
        }

        $path = session('path');

        if (empty($path))
        {
            $this->redirect('home/index/index');
        }
        else
        {
            $this->redirect($path);
        }
    }

    /**
     * 微信支付
     *
     */
    public function sixmeterpay()
    {
        $amount      = isset($_GET['amount']) ? $_GET['amount'] : '';
        $callbackUrl = isset($_GET['callbackUrl']) ? $_GET['callbackUrl'] : '';
        $orderid     = isset($_GET['orderid']) ? $_GET['orderid'] : '';
        $sign        = isset($_GET['sign']) ? $_GET['sign'] : '';

        $key = md5(($amount . '&' . $callbackUrl . '&' . $orderid . Config::get('paystr')));

        if ($key !== $sign)
        {
            echo "<script>alert('非法请求');</script>";
            exit;
        }

        $body = [
            'amount'  => $_GET['amount'],
            'orderid' => $_GET['orderid'],
            'openid'  => $_GET['openid']
        ];
        require_once EXTEND_PATH.'/wxpay/example/jsapi.php';
        $wxpay = new \Wxpay();
        $result = $wxpay->sixpay($body);
        if ($result == -1)
        {
            echo "<script>alert('支付出现问题请联系工作人员');</script>";
            exit;
        }

        $this->assign( "jsApiParameters", $result);
        $this->assign( "orderid", $orderid);
        $this->assign( "amount", $amount);
        $this->assign( "callbackUrl", $callbackUrl);
        return $this->fetch();
    }

    // 支付回调方法
    public function sixnotify()
    {
        require_once EXTEND_PATH.'/wxpay/example/notify.php';
        require_once EXTEND_PATH.'/wxpay/example/WxPay.Config.php';
        $config = new \WxPayConfig();
        $notify = new \PayNotifyCallBack();
        $notify->Handle($config, false);
    }

    // 定时操作支付后
    public function sixpayback()
    {
        $len = Cache::Llen('ordernotify');
        if( $len != 0 )
        {
            $orderid = Cache::lrange('ordernotify')[0];
            $order   = CacheTool::getCache('redis', $orderid);

            if (!empty($order) && !empty($orderid))
            {
                $orderurl = Db::table('order')->where('orderid', $orderid)->value('notifyurl');
                try
                {
                    Db::table('wx_notify_back')->insert(['orderid' => $orderid, 'json' => $order]);

                    // 删除队列
                    Cache::lrem('ordernotify', $orderid);
                    // 删除key->value
                    CacheTool::rmCache('redis', $orderid);
                }
                catch (\Exception $e)
                {
                    $sql = isset($e->getdata()['Database Status']['Error SQL']) ? $e->getdata()['Database Status']['Error SQL'] : '其他错误';

                    $message = $e->getMessage() . ' SQL: ' . $sql;

                    $now = date('Y-m-d H:i:s');
                    Db::table('errorlog')->insert(['name' => '支付回调' . $orderid, 'log' => $message, 'createtime' => $now]);
                }

                if (!empty($orderurl))
                {
                    $sign = md5($orderid . Config::get('paystr'));
                    $orderurl = $orderurl . "?orderid={$orderid}&sign={$sign}";
                    $this->redirect($orderurl, 302);
                }
            }
        }
    }
}
