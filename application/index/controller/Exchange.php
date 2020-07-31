<?php
namespace app\index\controller;
use think\Db;
use app\common\model\DateTimeTool;
use app\wechat\model\Wechat;
use app\common\model\CacheTool;
use app\index\model\HomeuserModel;
use think\Request;

class Exchange extends Base
{
    public function index($uid = '')
    {
        if ($uid)
        {
            $homeuserModel = new HomeuserModel();
            $users = $homeuserModel->getHomeuserIntegral($uid);

            $this->assign( "users", $users);
        }
        else
        {
            include_once('../vendor/phpqrcode/phpqrcode.php');
            $user = session('user');
            $qrcode = new \QRcode();
            $sid = $user['sid'];
            // 预先存留店ID,admin为0
            // try
            // {
            //     Db::table('exchange_qrcode')->insert(['sid' => $sid]);
            // }
            // catch (\Exception $e)
            // {
            //     echo $e->getMessage();
            //     exit;
            // }

            $url = "http://{$_SERVER['SERVER_NAME']}/index/Getuid/index?sid={$sid}";
            $value = $url;                  //二维码内容  
            $errorCorrectionLevel = 'H';    //容错级别  
            $matrixPointSize = 10;           //生成图片大小  

            ob_start();
            $qrcode::png($value,false , $errorCorrectionLevel, $matrixPointSize, 2);
            // $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);//这里就是把生成的图片流从缓冲区保存到内存对象上，使用base64_encode变成编码字符串，通过json返回给页面。
            $imageString = base64_encode(ob_get_contents()); //关闭缓冲区
            ob_end_clean(); //把生成的base64字符串返回给前端 

            $this->assign( "data", $imageString);
        }
        $this->assign( "uid", $uid);
        return $this->fetch();
    }

    public function getuser()
    {
        $user = session('user');
        $sid = $user['sid'];
        $uid = CacheTool::getCache('redis', "uid{$sid}");

        echo json_encode(['code' => 200, 'uid' => $uid]);exit;
    }

    // 兑换
    public function exchange()
    {
        $data = Request()->post();
        $data['create_time'] = date('Y-m-d H:i:s');

        if (empty($data['uid']))
        {
            echo json_encode(array('code' => 403, 'message' => '系统错误,请稍后兑换'));
            exit;
        }

        $user = Db::table('integral_total')->where('uid', $data['uid'])->find();

        $lastnum = $user['total_integral'] - $user['exchange_integral'] - $data['exchange_integral'];

        if ($lastnum < 0)
        {
            echo json_encode(array('code' => 403, 'message' => '剩余积分不足以兑换'));
            exit;
        }

        Db::startTrans();
        try
        {
            Db::table('exchange_integral_log')->insert($data);

            Db::table('integral_total')->where('uid', $user['uid'])
                     ->update(['exchange_integral' => $user['exchange_integral'] + $data['exchange_integral']]);

            // 提交事务
            Db::commit();
        }
        catch (\Exception $e)
        {
            Db::rollback();
            echo json_encode(array('code' => 403, 'message' => '数据库异常:' . $e->getMessage()));
            exit;
        }

        echo json_encode(array('code' => 200, 'message' => '兑换成功'));
        exit;
    }
}
