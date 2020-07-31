/**
 * Created by 乔冠宇 on 2019/7/31.
 */
$(function(){
    window.onload = function()
    {
        var $li = $('#tab li');
        $li.click(function(){
            var $this = $(this);
            $li.removeClass();
            $this.addClass('current');
            $this.children("ul").css("display",'block').parent().siblings("li").children("ul").css("display",'none');
        })
    }
});

$(".textbox").val(0);
$("#closeknow").click(function () {
    $("#zhezhao").hide();
    $("#closetipsBox").hide();
});
$("#closeknowa").click(function () {
    $("#zhezhaoa").hide();
    $("#closetipsBoxa").hide();
});
$("#select").change(function () {
    $("#storeForm").submit();
});
var storeid = $("input[name=store]").val();
$("option[value="+storeid+"]").attr("selected","selected");

$(function(){
    /*默认选中*/
    var $ul = $('.selfContent li ul');
    $ul.eq(0).css('display','block');
    $("#tab").children("li").first().addClass("current");
    var ele = $(".siblings");
    var totle = 0;
    ele.each(function(){
        var self = $(this);
        if(self.val() !=0 ){
            self.parents(".listcart").find(".textbox").css("display","block");
            self.parents(".listcart").find(".decrease_self").css("display","inline-block");
            //计算总价
            var a = eval(self.val());
            var b= eval(self.parents(".listcart").find(".pprice").val());
            totle += a * b;
        }
    });
    $("#num").html(toDecimal2(totle));
});
//购物数量加减
$(function(){
    var paynum = 0;
    $('.increase_self').click(function(){
        var inputtext = $(this).siblings('input');
        var current_num = parseInt(inputtext.val());
        current_num += 1;
        var limit = $(this).parents(".listcart").find(".iscid").val();
        if(limit == 1 || limit == 4 || limit == 5){
            inputtext.val(current_num);
            if(current_num > 0){
                $(this).siblings(".decrease_self").fadeIn();
                inputtext.fadeIn();
            }
            var price = $(this).parents(".self-right-right").find(".price").html();
            var num = priceTetol(price,"add");
            $("#num").html(num);
            paynum =  parseInt(paynum) + 1;
            $("#chosenum").val(paynum);
        }else if(limit == 2){
                if(paynum == 0 ){
                    alert("请您优先点水饺呀");
                }else {
                    if(paynum < current_num ){
                        alert("一份水饺限购一份蘸料，有特殊需求请备注说明呦。");
                        return false;
                    }else {
                        inputtext.val(current_num);
                        if(current_num > 0){
                            $(this).siblings(".decrease_self").fadeIn();
                            inputtext.fadeIn();
                        }
                        var price = $(this).parents(".self-right-right").find(".price").html();
                        var num = priceTetol(price,"add");
                        $("#num").html(num);
                    }
                }
        }else {
            inputtext.val(current_num);
            if(current_num > 0){
                $(this).siblings(".decrease_self").fadeIn();
                inputtext.fadeIn();
            }
            var price = $(this).parents(".self-right-right").find(".price").html();
            var num = priceTetol(price,"add");
            $("#num").html(num);
        }
    });
    $('.decrease_self').click(function(){
        var inputtext = $(this).siblings('input');
        var current_num = parseInt(inputtext.val());
        if(current_num > 0){
            current_num -= 1;
            var limit = $(this).parents(".listcart").find(".iscid").val();
            if(limit == 1 ){
                paynum =  parseInt(paynum) - 1;
            }
            inputtext.val(current_num);
            if(current_num < 1){
                $(this).fadeOut();
                inputtext.fadeOut();
            }
            var price = $(this).parents(".self-right-right").find(".price").html();
            var num = priceTetol(price,"desc");
            $("#num").html(num);
        }
    })
});

//总数计算
function priceTetol( price, type ) {
    var num = $("#num").html();
    num = eval(num);
    if(type == "add"){
        num += eval(price);
    }else {
        num-= eval(price);
    }
    return toDecimal2(num);
}

function closeStoretips() {
    $("#zhezhao").show();
    $("#closetipsBox").show();
}

function toDecimal2(x) {
    var f = Math.round(x * 100) / 100;
    var s = f.toString();
    var rs = s.indexOf('.');
    if (rs < 0) {
        rs = s.length;
        s += '.';
    }
    while (s.length <= rs + 2) {
        s += '0';
    }
    return s;
}
