<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:84:"C:\phpStudy\PHPTutorial\WWW\tp\public/../application/breakfast\view\index\index.html";i:1565272298;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <title>六米小吃</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <script src="../../easyui/jquery.min.js" type="text/javascript"></script>
    <script src="../../js/home/amazeui.min.js" type="text/javascript"></script>
    <link href="../../css/home/amazeui.min.css" type="text/css" rel="stylesheet" />
    <link href="../../css/home/style.css" type="text/css" rel="stylesheet" />
    <link href="../../css/home/index.css" type="text/css" rel="stylesheet" />
</head>
<body>
<header data-am-widget="header" class="am-header am-header-default sq-head ">
    <input type="hidden" id="lowspend" name="lowspend" value="<?php echo $lowspend; ?>">
    <form action="<?php echo url('breakfast/index/index'); ?>" method="post" id="storeForm">
        <label for="select" class="select" style="display: block;width: 40%;float: left;background: #ffffff;">
            <select name="store" id="select" style="background:none;">
                <?php if(is_array($stores) || $stores instanceof \think\Collection || $stores instanceof \think\Paginator): $i = 0; $__LIST__ = $stores;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </label>
    </form>
    <div id="ingralBox">带餐<a href="http://www.sixmeter.cn/home/Personal/index">积分</a>：<?php echo $integral; ?>，<span><a id="myorderfont" href="<?php echo url('home/order/myorder'); ?>"><u>我的订单</u></a></span></div>

</header>
<div class="list-left-my">
    <form id="orderform" action="<?php echo url('breakfast/order/index'); ?>" method="post">
        <input type="hidden" name="store" value="<?php echo $store; ?>">
        <div class="selfContent" id="tab">
            <?php if(is_array($data['categories']) || $data['categories'] instanceof \think\Collection || $data['categories'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['categories'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <li>
                <a ><?php echo $vo['name']; ?></a>
                <ul class="list-pro" style="height: <?php echo $vo['viewsize']; ?>" >
                    <?php if(is_array($vo['foods']) || $vo['foods'] instanceof \think\Collection || $vo['foods'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['foods'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?>
                    <li>
                        <div>
                            <div class="selfPic">
                                <a><img  src="../../uploads/<?php echo $voo['pic']; ?>" class="" /></a>
                            </div>
                            <div class="self-right-right">
                                <div>
                                    <p class="foodname"><?php echo $voo['name']; ?></p>
                                    <?php if($voo['disstatus'] == '1'): ?>
                                    <p class="myprice">￥<span class="price" ><?php echo $voo['disprice']; ?></span>&nbsp;<span class="" style="text-decoration: line-through;color: #cccccc;">(<?php echo $voo['price']; ?>)</span><span>/<?php echo $voo['unit']; ?></span></p>
                                    <?php else: ?>
                                    <p class="myprice">￥<span class="price"><?php echo $voo['price']; ?></span><span>/<?php echo $voo['unit']; ?></span></p>
                                    <?php endif; ?>
                                </div>
                                <div class="listcart">
                                    <div class="dstock ">
                                        <a class="decrease_self">-</a>
                                        <input  readonly="readonly" class="textbox" name="<?php echo $voo['sid']; ?>-<?php echo $voo['cid']; ?>-<?php echo $voo['id']; ?>" type="text" value="0">
                                        <a class="increase_self">+</a>
                                        <input type="hidden" value="" class="siblings">
                                    </div>
                                    <?php if($voo['disstatus'] == '1'): ?>
                                    <input type="hidden" class="pprice" value="<?php echo $voo['disprice']; ?>">
                                    <?php else: ?>
                                    <input type="hidden" class="pprice" value="<?php echo $voo['price']; ?>">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </ul >
            </li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </form>
</div>

<!--底部-->
<div id="footerBoxbox">
    <div id="footerBox">
        <a href="" class="footer-left">合计：<i id="num">0</i><i>元</i></a>
        <?php if($storedata['status'] == '0'): ?>
        <a  onclick="closeStoretips()" class="footer-right">选好了</a>
        <?php else: ?>
        <a  onclick="addorder()" class="footer-right">选好了</a>
        <?php endif; ?>
    </div>
    <div style="height: 2rem;width: 100%;background: #7d7d7d;margin-top: 1rem;margin-left: -0.5rem;">
        <div style="width: 100%;"><p style="color: white;text-align: center;line-height:20px;font-size: 12px;">© 2019 Sixmeter. All Rights Reserved,<a href="http://www.beian.miit.gov.cn" style="color: white;">冀ICP备19022506号-1</a></p></div>
    </div>
</div>

<?php if($storedata['status'] == '0'): ?>
<div id="zhezhao"></div>
<div id="closetipsBox">
    <div id="closetipsBox_top">
        <p>尊敬的客户：</p>
        <span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $storedata['statusdesc']; ?></span>
    </div>
    <div id="closeknow">我知道了</div>
</div>
<?php endif; if(\think\Session::get('times')): if(isset($announcement)): ?>
<div id="zhezhaoa"></div>
<div id="closetipsBoxa">
    <div id="closetipsBox_topa">
        <p>尊敬的客户：</p>
        <span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $announcement['content']; ?></span>
    </div>
    <div id="closeknowa">我知道了</div>
</div>
<?php endif; endif; ?>
</body>
<script src="../../js/home/index.js" type="text/javascript"></script>
<script>
    function addorder() {
        var num = $(".textbox:visible").length;
        var orderamount = $("#num").html();
        var lowspend = $("#lowspend").val();
        if(num == 0 ){
            alert("没有选择任何商品哦");
            return false;
        }
        if(parseFloat(orderamount) < parseFloat(lowspend)){
            alert("亲，最低消费"+lowspend+"元哦");
            return false;
        }
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo url('breakfast/Index/checkChose'); ?>" ,
            data: $("#orderform").serialize(),
            success: function (result) {
                if (result.code == 200) {
                    $("#orderform").submit();
                }else {
                    alert(result.msg);
                    return false;
                }
            }
        });
    }
</script>
</html>
