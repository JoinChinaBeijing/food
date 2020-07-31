<?php

namespace app\wechat\model;
use think\Db;
use app\common\model\CacheTool;
use think\exception\HttpResponseException;


class Wechat {
    // APPID
    protected $appid = '';
    // 测试APPID
    // protected $appid = 'wx2670cfc3f815c86f';
    // appSecret
    protected $appSecret = '';
    // 测试APPsecret
    // protected $appSecret = '47f6330f9f613e3c1b21f837dff1701b';
    protected $redirect_uri = 'http://www.sixmeter.cn';
    // 状态吗
    protected $error = [];
    //access_token
    protected $access_token;

    public function __construct()
    {
        // 设置access_token
        // $this->access_token = $this->getAccessToken();
        $this->error = include '../application/wechat/code.php';
    }

    /**
     *    授权登录页面
     */
    public function authorize()
    {
        // $model      = strtolower(request()->module());
        // $controller = strtolower(request()->controller());
        // $action     = strtolower(request()->action());
        // $path = $model . '/' . $controller . '/' . $action;
        $path = $_SERVER['REQUEST_URI'];

        // 存储起来，跳转的启动
        session('path', $path);

        // 获取appid
        $appId = $this->appid;
        // 回调的url
		$redirect_uri = urlencode($this->redirect_uri .'/wechat/index/getUserInfo');
        //跳转微信回调到redirect_uri获取code
        $url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appId&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";

        throw new HttpResponseException(redirect($url));
    }

    /**
     *  获取用户信息
     */
    public function getUserInfo( $code )
    {
        // appId与appSecret 
        $appId = $this->appid;
        $appSecret = $this->appSecret;

        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appId&secret=$appSecret&code=$code&grant_type=authorization_code";
        $res = $this->sendRequest($url);

        // 验证返回数据
        $result = $this->validate($res);
        if (isset($result['errcode']))
        {
            return $result;exit;
        }

        $uid = Db::table('home_user')->where('openid', $res['openid'])->value('id');

        $access_token = $res["access_token"];
        $openId  = $res['openid'];
        $getUserInfo = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openId&lang=zh_CN";
        //得到用户信息
        $user_info = $this->sendRequest($getUserInfo);

        $nickname = preg_replace_callback(
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $user_info['nickname']);

        $data = [
            'nickname' => $nickname,
            'openid'   => $user_info['openid'],
            'sex'      => $user_info['sex'],
            'address'  => $user_info['country'] . $user_info['province'] . $user_info['city'],
            'wexinImg' => $user_info['headimgurl']
        ];

        if (empty($uid))
        {
            $data['create_time'] = date('Y-m-d H:i:s');
            Db::table('home_user')->insert($data);

            $uid = Db::table('home_user')->getLastInsID();
        }
        else
        {
            $data['create_time'] = date('Y-m-d H:i:s');
            // unset($data['openid']);
            Db::table('home_user')->where('id', $uid)->update($data);
        }

        if (!empty($uid))
        {
            $this->setToken($uid);
            return $uid;
        }
        else
        {
            return array('errcode' => '403', 'errmsg' => '授权失败');
        }
    }

    // 生成token
    public function setToken($uid)
    {
        $token = md5(time() . $uid);
        session('token', $token);
        CacheTool::setCache("redis", $token, $uid, 86400);
    }

    //发送请求
    public function sendRequest($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output, true);
    }

    //根据微信服务器返回信息，验证是否发生错误，如果有错误，根据errcode值返回具体错误内容
    public function validate( $data )
    {
        if (! is_array( $data ))
        {
            $data = json_decode( $data, true );
        }

        if ( ! is_array( $data ) || ! array_key_exists( 'errcode', $data ) ) {
            return $data;
        }

        if ( isset( $this->error[ $data['errcode'] ] ) ) {
            if ( $data['errcode'] == 0 ) {
                return true;
            }
            $errmsg = isset( $this->error[ $data['errcode'] ] ) ? $this->error[ $data['errcode'] ] : ( $data['errmsg'] ?: '未知错误' );

            return [ 'errcode' => $data['errcode'], 'errmsg' => $errmsg ];
        }

        return [ 'errcode' => '-2', 'errmsg' => '未知错误' ];
    }


    // 支付退款
    public function sixrefund($transaction_id, $orderid, $amount, $reamount, $refund_desc)
    {
        require_once EXTEND_PATH.'/wxpay/lib/WxPay.Api.php';
        require_once EXTEND_PATH.'/wxpay/example/log.php';
        require_once EXTEND_PATH.'/wxpay/example/WxPay.Config.php';

        $logHandler= new \CLogFileHandler(EXTEND_PATH."/wxpay/logs/".date('Y-m-d').'.log');
        $log = \Log::Init($logHandler, 15);

        if ($transaction_id != "")
        {
            try{
                $transaction_id = $transaction_id;
                $total_fee = $amount;
                $refund_fee = $reamount;
                $input = new \WxPayRefund();
                $input->SetTransaction_id($transaction_id);
                $input->SetTotal_fee($total_fee * 100);
                $input->SetRefund_fee($refund_fee * 100);
                $input->SetRefund_desc($refund_desc);

                $config = new \WxPayConfig();
                $input->SetOut_refund_no("TD".$orderid);
                $input->SetOp_user_id($config->GetMerchantId());
                $result = \WxPayApi::refund($config, $input);

                if ($result['return_code'] == 'SUCCESS')
                {
                    try
                    {
                        Db::table('wx_notify_back')->insert(['orderid' => "TD".$orderid, 'json' => json_encode($result)]);
                    }
                    catch(\Exception $e)
                    {
                        return array('code' => 202, 'msg' => '返回成功但插入数据失败' . $e->$e->getMessage());
                    }

                    return array('code' => 200, 'msg' => '退款成功');
                }
                else
                {
                    return array('code' => 403, 'msg' => $result['return_msg']);
                }
            }
            catch(Exception $e)
            {
                \Log::ERROR(json_encode($e));
                return array('code' => 403, 'msg' => '系统错误'. $e->getMessage());
            }
        }
    }

    //分享时候用
    public function getAccess_token(){
	    $appId = $this->appid;
	    $appSecret = $this->appSecret;
	    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appId&secret=$appSecret";
	    $response = $this->sendRequest($url);
	    return $response;
	}

	public function getJsapiTicket( $ccess_token ){
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$ccess_token&type=jsapi";
		$response = $this->sendRequest($url);
		return $response;
	}

	public function getSignature( $ticket ,$timestamp, $noncestr){
		$initStr = "";
		$signatureData['noncestr'] = $noncestr;
		$signatureData['timestamp'] = $timestamp;
		$signatureData['jsapi_ticket'] = $ticket;
		$signatureData['url'] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PATH_INFO'];
		ksort($signatureData);
		reset($signatureData);
		foreach ($signatureData as $key => $value) {
			$initStr .= '&' . $key . '=' . $value;
		}
		$initStr = substr($initStr,1);
		return sha1($initStr);
	}
}