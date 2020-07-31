/**
 * Created by 乔冠宇 on 2019/7/31.
 */
total($("#deliveryamount").val());
$("#deliveryremark").click(function () {
    var oldremark = $("#deliveryremark").val();
    if(oldremark){
        $("#remark").val(oldremark);
    }
    $(this).blur();
    $("#zhezhao").fadeIn();
    $("#remarkinsertbox").fadeIn();
    $("#remark").focus();
});

$("#yunfeibox").html($("#yunfei").val());
$(".typebtn").click(function () {
    var self = $(this);
    self.addClass("typecolor").siblings(".typebtn").removeClass("typecolor");
});
var numm = 0;
$(".peisong").click(function () {
    // alert("暂未开放，敬请期待。");return false;
    $("#typeinput").val(0);
    $(".myadrbox").show();
    $(".ziqubox").hide();
    var yunfei = $("#yunfei").val();
    if(numm == 1){
        total(yunfei);
        numm = 0;
    }
});
$(".ziqu").click(function () {
    $("#typeinput").val(1);
    $(".ziqubox").show();
    $(".myadrbox").hide();
    var yunfei =  0;
    if(numm == 0 ){
        total(yunfei);
        numm = 1;
    }
});
$(".tastebox").click(function () {
    var self = $(this);
    self.addClass("bordercolor").siblings().removeClass("bordercolor");
    var taste = self.attr("value");
    var foodval = self.parents(".fooddes").find("#deliveryfoods").val();
    var foodarr = foodval.split('-');
    foodval = foodarr[0]+"-"+foodarr[1]+"-" + taste;
    self.parents(".fooddes").find("#deliveryfoods").val(foodval);
});
$(function(){
    $(".moreaddressBox").on('click',".mynewaddress",function () {
        var content = $(this).find(".addresscheck").html();
        if(!content){
            var str =  '<img src="./../../icon/chenggong_2.png" style="width: 5vw;height: 5vw;" alt="">';
            $(this).parents('.moreaddress').siblings(".moreaddress").find(".addresscheck").html("");
            $(this).find(".addresscheck").append(str);
            var newphone = $(this).find(".newphone").text();
            var newaddress = $(this).find(".newaddress").html();
            var newname= $(this).find(".newname").html();
            var adrid = $(this).find(".adrid").val();
            $("#findyaddress").val(adrid);
            $("#username").html(newname);
            $("#useraddress").html(newaddress);
            $("#userphone").html(newphone);
            $(".moreaddressBox").hide();
            num = 0;
        }
    });

    $("#moreadropen").click(function () {
        $(".moreaddressBox").toggle(200);
    });

    $('.increase').click(function(){
        var self = $(this);
        var current_num = parseInt(self.siblings('input').val());
        current_num += 1;
        if(current_num > 0){
            self.siblings(".decrease").fadeIn();
            self.siblings(".text_box").fadeIn();
        }
        self.siblings('input').val(current_num);
        var price = self.parents(".foodDetailSon").find(".foodprice").html();
        var foodprice = current_num * eval(price);
        self.parents(".foodDetailSon").find(".pricetotal").html(foodprice.toFixed(1));
        var categoryprice = self.parents(".foodlistll").find(".cateprice").html();
        categoryprice = eval(categoryprice) + eval(price);
        self.parents(".foodlistll").find(".cateprice").html(categoryprice.toFixed(1));
        var totalprice = $("#orderprice").html();
        totalprice = eval(totalprice) + eval(price);
        $("#orderprice").html(totalprice.toFixed(1));
        var total = $("#total").val();
        total = eval(total) + eval(price);
        $("#total").val(total);
    });
    $('.decrease').click(function(){
        var self = $(this);
        var current_num = parseInt(self.siblings('input').val());
        if(current_num > 1){
            current_num -= 1;
            if(current_num < 1){
                self.fadeOut();
                self.siblings(".text_box").fadeOut();
            }
            self.siblings('input').val(current_num);
            var price = self.parents(".foodDetailSon").find(".foodprice").html();
            var foodprice = current_num * eval(price);
            self.parents(".foodDetailSon").find(".pricetotal").html(foodprice.toFixed(1));
            var categoryprice = self.parents(".foodlistll").find(".cateprice").html();
            categoryprice = eval(categoryprice) - eval(price);
            self.parents(".foodlistll").find(".cateprice").html(categoryprice.toFixed(1));
            var totalprice = $("#orderprice").html();
            totalprice = eval(totalprice) - eval(price);
            $("#orderprice").html(totalprice.toFixed(1));
            var total = $("#total").val();
            total = eval(total) - eval(price);
            $("#total").val(total)
        }else {
            if(current_num == 1 ){
                var res = confirm("确认删除此产品？");
                if(res == true ){
                    var price = self.parents(".foodDetailSon").find(".foodprice").html();
                    var categoryprice = self.parents(".foodlistll").find(".cateprice").html();
                    categoryprice = categoryprice - price;
                    self.parents(".foodlistll").find(".cateprice").html(categoryprice.toFixed(1));
                    var totalprice = $("#orderprice").html();
                    totalprice = eval(totalprice) - eval(price);
                    $("#orderprice").html(totalprice.toFixed(1));
                    self.parents(".foodlistbox").remove();
                }
            }
        }
    });
    linum = 1;
    checknum = 0;
    $(".xiangxia").click(function () {
        var self = $(this);
        self.parents(".foodlistll").find(".foodDetailBox").toggle(200);
        if(linum == 1){
            checknum = parseInt(checknum) + parseInt(1);
            deg = checknum * parseInt(180);
            self.parents(".foodlistll").find(".xiangxia").css("transform","rotate("+deg+"deg)");
            linum = -1;
        }else {
            checknum = parseInt(checknum) + parseInt(1);
            deg = checknum * parseInt(180);
            self.parents(".foodlistll").find(".xiangxia").css("transform","rotate("+deg+"deg)");
            linum = 1;
        }
    });

    $(".moreaddressBox").on('click',".editadr",function () {
        var self = $(this);
        var parent = self.parents(".moreaddress");
        var detail = parent.find(".detail").html();
        var school = parent.find(".school").html();
        var name = parent.find(".newname").html();
        var phone = parent.find(".newphone").text();
        $("#detail").val(detail);
        $("#contacter").val(name);
        $("#phone").prop("value",phone);
        $("#school").val(school);
        var isdefault = parent.find(".adrdefault").val();
        $("input[name=default][value="+isdefault+"]").prop("checked",true);
        $("#id").val(parent.find(".adrid").val());
        $("#dialog_confirm").attr("onclick","updataAddress()");
        $("#zhezhao").fadeIn(300);
        $("#addbox").fadeIn(300);
    });

});
function isPhoneNo(phone) {
    var pattern = /^1[3456789]\d{9}$/;
    return pattern.test(phone);
}

function total(yunfei) {
    var delivery = $("#total").val();
    var realDelivery = eval(delivery) + eval(yunfei);
    $("#orderprice").html(realDelivery.toFixed(2));
}
function address() {
    $("#detail").val("");
    $("#contacter").val("");
    $("#phone").val("");
    $("#school").val("");
    $("#id").val("");
    $("#dialog_confirm").attr("onclick","insertAddress()");
    $("#zhezhao").fadeIn(300);
    $("#addbox").fadeIn(300);
}
function closeAddressBox() {
    $("#zhezhao").fadeOut(300);
    $("#addbox").fadeOut(300);
}

function closeremarkBox() {
    var oldremark = $("#deliveryremark").val();
    if(oldremark){
        $("#remark").val(oldremark);
    }
    $("#zhezhao").fadeOut(300);
    $("#remarkinsertbox").fadeOut(300);
}
function insertremark() {
    var remark = $("#remark").val();
    $("#deliveryremark").val(remark);
    $("#remarkBox").fadeIn();
    $("#zhezhao").fadeOut();
    $("#remarkinsertbox").fadeOut();
}


function closemoreaddress() {
    $(".moreaddressBox").hide();
}