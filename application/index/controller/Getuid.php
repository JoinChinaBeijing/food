<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use app\wechat\model\Wechat;
use app\common\model\CacheTool;

class Getuid extends Controller
{
    public function index($sid)
    {
        $token = session('token');
        $uid = CacheTool::getCache("redis", $token);

        if (empty($uid))
        {
            $wechat = new Wechat();
            $wechat->authorize();
        }

        CacheTool::setCache("redis", "uid{$sid}", $uid, 600);

        echo "<script>alert('请联系工作人员');document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() { WeixinJSBridge.call('hideOptionMenu');});setTimeout('WeixinJSBridge.call(".'"closeWindow"'.")', 1000);</script>";exit;
    }
}
