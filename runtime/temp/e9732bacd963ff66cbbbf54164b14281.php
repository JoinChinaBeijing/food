<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:81:"C:\phpStudy\PHPTutorial\WWW\tp\public/../application/home\view\order\myorder.html";i:1568637598;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="小吃，微信商城，外卖，订单" />
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name = "format-detection" content = "telephone=no">
    <title>我的订单</title>
    <script src="../../easyui/jquery.min.js" type="text/javascript"></script>
    <link href="../../css/order/myorder.css" type="text/css" rel="stylesheet" />
</head>
<body>
    <div id="orderlistdtyle">订单列表</div>
    <?php if(empty($orderList) || (($orderList instanceof \think\Collection || $orderList instanceof \think\Paginator ) && $orderList->isEmpty())): ?>
    <h2 style="text-align: center;line-height: 30rem;">暂无订单</h2>
    <?php else: if(is_array($orderList) || $orderList instanceof \think\Collection || $orderList instanceof \think\Paginator): $i = 0; $__LIST__ = $orderList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
    <div class="orderlist">
        <div class="orderlist_top">
            <div class="top_left"><?php echo $vo['createtime']; ?></div>
            <div class="top_right"><?php echo $vo['orderid']; ?></div>
        </div>
        <div class="orderlist_bottom">
            <div class="bottombox">
                <div class="bottomboxson">
                    <div class="bottom_left"><img src="./../../images/sj.jpg" alt="" style="width: 100%;height: 100%"></div>
                    <div class="bottom_right">
                        <div class="foodcate"><?php echo $vo['cate']; ?></div>
                        <div class="foodamoune">
                            <div class="amount">￥<?php echo $vo['amount']; ?></div>
                            <div class="detailbox">
                                <a href="<?php echo url('home/order/orderdetail',['orderid'=>$vo['orderid']]); ?>">详情</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; endif; else: echo "" ;endif; endif; ?>


</body>
</html>