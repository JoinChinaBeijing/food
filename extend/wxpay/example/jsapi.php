<?php
/**
*
* example目录下为简单的支付样例，仅能用于搭建快速体验微信支付使用
* 样例的作用仅限于指导如何使用sdk，在安全上面仅做了简单处理， 复制使用样例代码时请慎重
* 请勿直接直接使用样例对外提供服务
* 
**/

/**
 *  微信支付
 */
require_once __DIR__."/../lib/WxPay.Api.php";
require_once __DIR__."/WxPay.JsApiPay.php";
require_once __DIR__."/WxPay.Config.php";
require_once __DIR__.'/log.php';
class Wxpay
{
    function sixpay($data)
    {
        //初始化日志
        $logHandler= new CLogFileHandler(__DIR__."/../logs/".date('Y-m-d').'.log');
        $log = Log::Init($logHandler, 15);

        //①、获取用户openid
        try{

            $tools = new JsApiPay();
            // $openId = $tools->GetOpenid();
            $openId = $data['openid'];

            //②、统一下单
            $input = new WxPayUnifiedOrder();
            $input->SetBody("六米小吃支付产品");//商品描述信息
            // $input->SetAttach("");// 附加数据
            $input->SetOut_trade_no($data['orderid']);// 随机字符串
            $input->SetTotal_fee($data['amount'] * 100);//交易金额 微信默认金额单位为分，所有需要*100
            $input->SetTime_start(date("YmdHis"));// 订单生成时间
            $input->SetTime_expire(date("YmdHis", time() + 6000));// 订单失效时间
            // $input->SetGoods_tag("test");// 订单优惠标记
            $input->SetNotify_url("http://www.sixmeter.cn/wechat/index/sixnotify");// 异步接受微信支付结果通知的回调地址
            $input->SetTrade_type("JSAPI");//JSAPI 公众号支付
            $input->SetOpenid($openId);// 填写支付人的openID
            $config = new WxPayConfig();
            $order = WxPayApi::unifiedOrder($config, $input);
            $jsApiParameters = $tools->GetJsApiParameters($order);

            //获取共享收货地址js函数参数
            // $editAddress = $tools->GetEditAddressParameters();
            return $jsApiParameters;
        } catch(Exception $e) {
            // print_r($e);exit;
            Log::ERROR($e->getmessage());
            return -1;
        }
    }
}