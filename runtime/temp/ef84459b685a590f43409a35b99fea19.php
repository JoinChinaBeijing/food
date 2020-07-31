<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:84:"C:\phpStudy\PHPTutorial\WWW\tp\public/../application/breakfast\view\order\index.html";i:1565256951;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="小吃，微信商城，外卖" />
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="" />
    <meta name = "format-detection" content = "telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>订单详情</title>
    <script src="../../easyui/jquery.min.js" type="text/javascript"></script>
    <script src="../../js/home/amazeui.min.js" type="text/javascript"></script>
    <link href="../../css/home/amazeui.min.css" type="text/css" rel="stylesheet" />
    <link href="../../css/home/style.css" type="text/css" rel="stylesheet" />
    <link href="../../css/order/index.css" type="text/css" rel="stylesheet" />
</head>
<body>
<div>
    <input type="hidden" name="lowspend" id="lowspend" value="<?php echo $lowspend; ?>">
    <input type="hidden" name="yunfei" id="yunfei" value="<?php echo $yunfei; ?>">
    <form action="<?php echo url('breakfast/order/addorder'); ?>" method="post" id="addorder">
        <input type="hidden" name="findyaddress" id="findyaddress" value="<?php echo $defaultAdr['id']; ?>">
        <input type="hidden" name="store" value="<?php echo $store; ?>">
        <input type="hidden" name="uid" value="<?php echo $uid; ?>">
    <div class="ziqubox" style="display: none;">
        <input type="hidden" name="typeinput" id="typeinput" value="0">
        <ul>
            <?php if($zqinfo == ''): ?>
            <li>
                <span class="formFontSize">输入姓名：</span>
                <input  class="addinput" type="text" required="required" name="zqname" id="zqname" placeholder="请输入姓名">
            </li>
            <li>
                <span class="formFontSize">联系电话：</span>
                <input class="addinput" type="number" required="required" name="zqphone" id="zqphone" placeholder="请输入电话">
            </li>
            <?php else: ?>
            <li>
                <span class="formFontSize">输入姓名：</span>
                <input  class="addinput" type="text" required="required" name="zqname" id="zqname" value="<?php echo $zqinfo['zqname']; ?>">
            </li>
            <li>
                <span class="formFontSize">联系电话：</span>
                <input class="addinput" type="number" required="required" name="zqphone" id="zqphone" value="<?php echo $zqinfo['zqphone']; ?>">
            </li>
            <?php endif; ?>
        </ul>
    </div>
    <div style="display: block;" class="myadrbox">
        <div id="addressBox">
            <div class="addresstop" id="default">
                <img class="adrimg" src="./../../images/address.png" alt="">
                <?php if($defaultAdr == '0'): ?>
                <div id="adrzwbox" style="text-align: center;line-height: 6vh;" onclick="address()">
                    <img  class="icon" style="margin-bottom: 2%;" src="./../../icon/tianjia_1.png" alt=""><span style="font-size: 1.5rem;line-height: 6vh;padding-left: 1vw;">添加地址</span>
                </div>
                <?php else: ?>
                <div id="adrbox">
                    <div id="adr" class="findlyadr<?php echo $defaultAdr['id']; ?>">
                        <div class="adrbox">
                            <div class="adrboxtop">
                                <img src="./../../icon/yonghu.png" class="icon" alt="">
                                <span class="fontmay iconfont" id="username"><?php echo $defaultAdr['contacter']; ?></span>
                            </div>
                            <div class="phonenum">
                                <img src="./../../icon/phone.png" class="iconbig" alt="">
                                <span class="fontmay">联系电话： <span id="userphone" class="fontmay"><?php echo $defaultAdr['phone']; ?></span></span>
                            </div>
                        </div>
                        <div class="adrbox">
                            <div class="adrdetailbox">
                                <img src="./../../icon/dizhi.png" class="iconbig" alt="">
                                <span class="fontmay">送餐地址： <span class="fontmay" id="useraddress"><?php echo $defaultAdr['school']; ?><?php echo $defaultAdr['usedatedesc']; ?><?php echo $defaultAdr['detail']; ?></span></span>
                            </div>
                        </div>
                    </div>
                    <div id="moreadropen">
                        <img src="./../../icon/gengduo.png" alt="" class="icon">
                    </div>
                </div>
                <?php endif; ?>
                <img src="./../../images/address.png" alt="" class="adrimg">
            </div>
            <div class="moreaddressBox">
                <ul>
                    <?php if(is_array($adrData) || $adrData instanceof \think\Collection || $adrData instanceof \think\Paginator): $i = 0; $__LIST__ = $adrData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <li class="moreaddress" id="<?php echo $vo['id']; ?>">
                        <input type="hidden" name="adrdefault" class="adrdefault" value="<?php echo $vo['default']; ?>">
                        <div class="mynewaddress" >
                            <input type="hidden" name="adrid" class="adrid" value="<?php echo $vo['id']; ?>">
                            <div class="addresscheck" ></div>
                            <div class="mycheckaddress">
                                <p class="newaddress fontmay">
                                    <span class="school"><?php echo $vo['school']; ?></span><span class="usedate"><?php echo $vo['usedatedes']; ?></span><span class="detail"><?php echo $vo['detail']; ?></span>
                                </p>
                                <div>
                                    <p><span class="newname fontmay"><?php echo $vo['contacter']; ?></span>&nbsp;&nbsp;<span class="newphone fontmay"><?php echo $vo['phone']; ?></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="check">
                            <?php if($vo['default'] == '1'): ?>
                            <i style="color: #A3A3A3;" class="fontmay">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i><i style="color: #A3A3A3;" class="fontmay editadr">编辑</i>
                            <?php else: ?>
                            <i style="color: #A3A3A3;" class="fontmay deleteadr">删除</i>&nbsp;&nbsp;|&nbsp;&nbsp;<i style="color: #A3A3A3;" class="fontmay editadr">编辑</i>
                            <?php endif; ?>
                        </div>
                    </li>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
                <div class="addborder">
                    <div class="" onclick="address()" style="text-align: right;">
                        <img src="./../../icon/tianjia_1.png" alt="" class="addiocon">
                        新增地址
                    </div>
                    <div class="" onclick="closemoreaddress()">
                        <img src="./../../icon/xiangxia.png" alt="" class="addiocon gengduo_1">
                        收起
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="orderBox" class="breakfastOrder" style="height: 30rem;">
        <div id="orderboxSon">
            <div>
                <ul>
                    <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <li class="foodlistll">
                            <div class="foodtotelBox">
                                <div class="foodtotel foodtotelLeft">
                                    <?php echo $vo['name']; ?>
                                </div>
                                <div class="foodtotel foodtotelRight">
                                    共计￥<span class="cateprice"><?php echo $vo['catetotal']; ?></span>元
                                </div>
                            </div>
                            <div class="foodDetailBox">
                                <?php if(is_array($vo['foods']) || $vo['foods'] instanceof \think\Collection || $vo['foods'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['foods'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?>
                                    <div class="foodlistbox">
                                    <div class="foodDetailSon">
                                        <div class="foodpicbox">
                                            <img src="./../../uploads/<?php echo $voo['pic']; ?>" alt="" style="width: 100%;height: 100%;">
                                        </div>
                                        <div class="fooddes">
                                            <div style="height: 40%;">
                                                <p class="foodname"><?php echo $voo['name']; if(is_array($voo['tastes']) || $voo['tastes'] instanceof \think\Collection || $voo['tastes'] instanceof \think\Paginator): $i = 0; $__LIST__ = $voo['tastes'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vooo): $mod = ($i % 2 );++$i;if($voo['deftaste'] == $vooo['id']): ?>
                                                    <span  class="tastebox bordercolor" style="cursor:pointer" value="<?php echo $vooo['id']; ?>"><?php echo $vooo['name']; ?></span>
                                                    <?php else: ?>
                                                    <span  class="tastebox" style="cursor:pointer" value="<?php echo $vooo['id']; ?>"><?php echo $vooo['name']; ?></span>
                                                    <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                                                </p>
                                            </div>
                                            <div style="height: 50%;">
                                                <input type="hidden" name="deliveryfoods[]" id="deliveryfoods" value="<?php echo $vo['id']; ?>-<?php echo $voo['id']; ?>-<?php echo $voo['deftaste']; ?>">
                                                <p class="fooddescdetail"><span class="unit">单价：￥</span><span class="unit"><span class="foodprice"><?php echo $voo['discountprice']; ?></span>（<?php echo $voo['unit']; ?>）</span></p>
                                                <p class="fooddescdetail" style="display: flex;justify-content: space-between;"><span style="display: inline-block;">数量：<input style="display: inline;border: none;" readonly="" id="numcheckstyle" class="text_box" name="fooddeliverynum[]" type="text" value="<?php echo $voo['num']; ?>"></span><span style="display: inline-block;">小计：<?php echo $voo['foodtotal']; ?></span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </div>
                        </li>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <div id="remarkBox">
        <textarea name="deliveryremark" id="deliveryremark" placeholder="备注信息：口味、忌口等..." style="width: 100%;height: 100%;"></textarea>
    </div>
    <div id="footer">
        <div class="footerLeft">
            <input type="hidden" name="" id="total" value="<?php echo $total; ?>">
            <span>应付：</span><span>￥</span><span id="orderprice"><?php echo $total; ?></span>
        </div>
        <div class="footerRight"><span>去支付</span></div>
    </div>
    </form>
</div>
<!--添加地址框遮罩-->
<div id="zhezhao"></div>
<div  id= "addbox">
    <div class="newAddressFormbreakfast">
        <form id="adrForm" action="<?php echo url('breakfast/Address/addAddress'); ?>" method="post">
            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="store" value="<?php echo $store; ?>">
            <ul>
                <li>
                    <span class="formFontSize">选择位置：</span>
                    <select name="school" class="form-control" id="school">
                        <option value="">&nbsp;&nbsp;请选择</option>
                        <?php if(is_array($school) || $school instanceof \think\Collection || $school instanceof \think\Paginator): $i = 0; $__LIST__ = $school;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $vo['name']; ?>"><?php echo $vo['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </li>
                <li>
                    <span class="formFontSize">取餐日期：</span>
                    <select name="usedate" class="form-control" id="usedate">
                        <option value="1">明天</option>
                    </select>
                </li>
                <li>
                    <span class="formFontSize">取餐时间：</span>
                    <select name="detail" class="form-control" id="detail">
                        <option value="">请选择</option>
                        <option value="7:30-8:30">7:00-8:00</option>
                    </select>
                </li>
                <li>
                    <span class="formFontSize">输入姓名：</span>
                    <input  class="addinput" type="text" name="contacter" id="contacter" placeholder="请输入姓名">
                </li>
                <li>
                    <span class="formFontSize">联系电话：</span>
                    <input class="addinput" type="number" name="phone" id="phone" placeholder="请输入电话">
                </li>
                <li style="border: none;">
                    <span class="formFontSize">默认地址：</span>
                    <div class="addinput_status">
                        是：<input type="radio" name="default" value="1" checked="checked">
                        &nbsp;&nbsp;&nbsp;否：<input type="radio" name="default" value="0">
                    </div>
                </li>
            </ul>
        </form>
    </div>
    <div id="dialog">
        <div onclick="closeAddressBox()" id="dialog_cancel" class="fontmay">取消</div>
        <div id="dialog_confirm" onclick="insertAddress()">确认</div>
    </div>
</div>
<div  id= "remarkinsertbox">
    <div class="newAddressForm2">
        <textarea name="" id="remark" placeholder="备注信息：口味、忌口等..." style="width: 100%;height: 100%;"></textarea>
    </div>
    <div id="dialogbox">
        <div onclick="closeremarkBox()" class="dialog_cancel fontmay">取消</div>
        <div class="dialog_confirm" onclick="insertremark()">确认</div>
    </div>
</div>
</body>
<script src="../../js/home/order.js" type="text/javascript"></script>
<script>
    total(0);
    function insertAddress() {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo url('breakfast/Address/addAddress'); ?>" ,
            data: $('#adrForm').serialize(),
            success: function (result) {
                if(result.code == 200){
                    $("#username").text(result.data.contacter);
                    $("#userphone").text(result.data.phone);
                    $("#useraddress").text(result.data.school+result.data.usedate+result.data.detail);
                    $("#findyaddress").val(result.data.id);
                    $("#adr").addClass("findlyadr"+result.data.id);
                    $("#zhezhao").fadeOut(300);
                    $("#addbox").fadeOut(300);
                    alert(result.message);
                    window.location.reload();
                }else {
                    alert(result.message);
                    return false;
                }
            }
        });
    }

    function updataAddress() {
        var adrid = $("#id").val();
        var school = $('#school').val();
        var datail = $("#detail").val();
        var contacter = $("#contacter").val();
        var phone = $("#phone").val();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo url('breakfast/Address/addAddress'); ?>" ,
            data: $('#adrForm').serialize(),
            success: function (result) {
                if(result.code == 200){
                    $("#"+adrid).find(".school").text(school);
                    $("#"+adrid).find(".detail").val(datail);
                    $("#"+adrid).find(".usedate").val(datail);
                    $("#"+adrid).find(".newname").text(contacter);
                    $("#"+adrid).find(".newphone").text(phone);
                    $("#zhezhao").fadeOut(300);
                    $("#addbox").fadeOut(300);
                    window.location.reload();
                    return false;
                }
                alert(result.message);
            }
        });
    }
    $(".footerRight").click(function () {
        var orderamount = $("#orderprice").html();
        var lowspend = $("#lowspend").val();
        if(parseFloat(orderamount) < parseFloat(lowspend)){
            alert("亲，最低消费"+parseFloat(lowspend)+"元哦");
            return false;
        }
        var value = $("#typeinput").val();
        if( value == 1 ){
            var username = $("#zqname").val();
            var zqphone = $("#zqphone").val();
            if($.trim(username).length !=0  && $.trim(zqphone).length != 0){
                if(isPhoneNo($.trim(zqphone))){
                    $("#addorder").submit();
                }else {
                    alert("手机号不合法");
                }
            }else {
                alert("姓名电话不能为空")
            }
        }else {
            if($("#adrzwbox").length <= 0) {
                $("#addorder").submit();
            }else {
                alert("请添加地址");
            }
        }
    });
    $(".deleteadr").click(function () {
        var id = $(this).parents(".moreaddress").attr("id");
        if(confirm("确认删除该地址？")){
            if( id ){
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?php echo url('breakfast/Address/delete'); ?>" ,
                    data: {id:id},
                    success: function (result) {
                        alert(result.message);
                        window.location.reload();
                    }
                });
            }
        }
    })
</script>
</html>