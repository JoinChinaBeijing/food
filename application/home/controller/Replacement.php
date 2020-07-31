<?php
/**
 * Created by PhpStorm.
 * User: 乔冠宇
 * Date: 2020/3/17
 * Time: 19:55
 */

namespace app\home\controller;
use think\Db;
use think\Request;

include_once('../vendor/phpqrcode/phpqrcode.php');

class Replacement extends Base  {

    public function index()
    {
        if ($_POST)
        {
            $data = Request::instance()->post();
            $data['create_time'] = date('Y-m-d H:i:s');

            try
            {
                Db::table('d_integral')->insert($data);
                $integralID = Db::name('d_integral')->getLastInsID();
            }
            catch (\Exception $e)
            {
                echo json_encode(array('code' => 403, 'message' => $e->getMessage()));
                exit;
            }

            echo json_encode(array('code' => 200, 'message' => '成功', 'url' => "replacement/qrcode?id={$integralID}"));
            exit;
        }

        return $this->fetch();
    }


    public function qrcode()
    {
        $integralID = Request::instance()->get('id');
        $qrcode = new \QRcode();

        // $qrimage = new \QRimage();
        $url = "http://{$_SERVER['SERVER_NAME']}/home/replacement/scanQrcode?id={$integralID}";
        $value = $url;                  //二维码内容  
        $errorCorrectionLevel = 'H';    //容错级别  
        $matrixPointSize = 20;           //生成图片大小  

        ob_start();
        $qrcode::png($value,false , $errorCorrectionLevel, $matrixPointSize, 2);
        // $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);//这里就是把生成的图片流从缓冲区保存到内存对象上，使用base64_encode变成编码字符串，通过json返回给页面。
        $imageString = base64_encode(ob_get_contents()); //关闭缓冲区
        ob_end_clean(); //把生成的base64字符串返回给前端 

        return view('qrcode',['data' =>$imageString]);
    }

    // 扫描二维码页面
    public function scanQrcode($id)
    {
        $uid = $this->uid;
        // 跟据id获取带餐信息
        $integralInfo = Db::table('d_integral')->where('id', $id)->find();
        $integral_total = Db::table('integral_total')->where('uid', $uid)->find();
        if (empty($integralInfo))
        {
            $message = '信息出现错误!!!';
        }
        else
        {
            if (isset($integralInfo['uid']) && $integralInfo['uid'] == 0)
            {
                Db::startTrans();

                try
                {
                    // 添加单条带餐记录
                    Db::table('d_integral')->where('id', $id)->update(['uid' => $uid]);

                    // 不存在总记录添加信息
                    if (empty($integral_total))
                    {
                        Db::table('integral_total')->insert(['uid' => $uid, 'total_integral' => $integralInfo['integral']]);
                    }
                    else
                    {
                        $total = $integral_total['total_integral'] + $integralInfo['integral'];
                        Db::table('integral_total')->where('id', $integral_total['id'])->update(['total_integral' => $total]);
                    }

                    // 提交事务
                    Db::commit();
                }
                catch (\Exception $e)
                {
                    Db::rollback();
                    $message = '系统异常，请联系工作人员';
goto one;
                }

                $message = "带餐{$integralInfo['integral']}个成功";
            }
            else
            {
                $message = '信息已经被录入,请不要重复操作!!!';
            }
        }

one:
        $this->assign( "message", $message);
        return $this->fetch();
    }
}