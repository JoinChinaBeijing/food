<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:85:"C:\phpStudy\PHPTutorial\WWW\tp\public/../application/home\view\order\orderdetail.html";i:1565163246;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="小吃，微信商城，外卖" />
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name = "format-detection" content = "telephone=no">
    <title>订单详情</title>
    <script src="../../../../easyui/jquery.min.js" type="text/javascript"></script>
    <link href="../../../../css/order/orderdetail.css" type="text/css" rel="stylesheet" />
</head>
<style>

</style>
<body>
    <div class="myorderbox" >
        <div class="myorder_top">订单信息</div>
        <div class="myorder_bottom">
            <div class="bottom_main">
                <ul>
                    <li>
                        <div class="bottom_main_left">订单号：</div>
                        <div class="bottom_main_right"><?php echo $orderData['orderid']; ?></div>
                    </li>
                    <li>
                        <div class="bottom_main_left">联系电话：</div>
                        <div class="bottom_main_right"><?php echo $orderData['phone']; ?></div>
                    </li>
                    <li>
                        <div class="bottom_main_left">姓名：</div>
                        <div class="bottom_main_right"><?php echo $orderData['username']; ?></div>
                    </li>
                    <li>
                        <div class="bottom_main_left">付款时间：</div>
                        <div class="bottom_main_right"><?php echo $orderData['createtime']; ?></div>
                    </li>
                    <li>
                        <div class="bottom_main_left">总计：</div>
                        <div class="bottom_main_right">￥<?php echo $orderData['amount']; ?>元</div>
                    </li>
                    <li>
                        <div class="bottom_main_left">类型：</div>
                        <div class="bottom_main_right"><?php echo $orderData['types']; ?></div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="detailbox">
        <div class="detailbox_top">
            <div>名称</div>
            <div>数量</div>
            <div>小计</div>
        </div>
        <div class="detailbox_bottom">
            <?php if(is_array($orderData['detail']) || $orderData['detail'] instanceof \think\Collection || $orderData['detail'] instanceof \think\Paginator): $i = 0; $__LIST__ = $orderData['detail'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <div class="detailbox_bottom_son">
                    <div><?php echo $vo['fname']; ?></div>
                    <div><?php echo $vo['num']; ?></div>
                    <div><?php echo $vo['total']; ?>元</div>
                </div>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
    <div class="footer">
        <div class="footer_left">上一页</div>
        <?php if($orderData['isbreakfast'] == '0'): ?>
            <div class="footer_right"><a href="<?php echo url('home/index/index'); ?>">首页</a></div>
        {/else}
            <div class="footer_right"><a href="<?php echo url('breakfast/index/index'); ?>">首页</a></div>
        <?php endif; ?>
    </div>
</body>
<script>
    $(function () {
        $(".footer_left").click(function () {
            history.go(-1)
        })
    })
</script>
</html>