<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:82:"C:\phpStudy\PHPTutorial\WWW\tp\public/../application/index\view\resume\liquor.html";i:1590143572;}*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="format-detection" content="telephone=yes">
    <meta name="description" content="晟铭酒坊,白酒">
    <meta name="Keywords" content="晟铭酒坊,白酒">
    <title>晟铭酒坊</title>
    <style type="text/css">
        .myhead{
            width: 100%;
            height: 5vh;
            background: red;
            margin: 0 auto;
            color: white;
            font-size: 3em;
            font-weight: 700;
            text-align: center;
            line-height: 5vh;
        }
        *{margin:0;padding:0;list-style-type:none;}
        a,img{border:0;}
        body{font:12px/180% Arial, Helvetica, sans-serif, "新宋体";}
        .addWrap{ position:relative; width:100%;height:25vh;background:#fff;margin:0; padding:0;}
        .addWrap .swipe{overflow: hidden;visibility: hidden;position:relative;}
        .addWrap .swipe-wrap{overflow:hidden;position:relative;}
        .addWrap .swipe-wrap > div {float:left;width: 100%;position:relative;}
        #position{ position:absolute; bottom:0; right:0; padding-right:8px; margin:0; background:#000; opacity: 0.4; width:100%; filter: alpha(opacity=50);text-align:right;}
        #position li{width:10px;height:10px;margin:0 2px;display:inline-block;-webkit-border-radius:5px;border-radius:5px;background-color:#AFAFAF;}
        #position li.cur{background-color:#FF0000;}
        .img-responsive { display: block; max-width:100%;height: 25vh;}
        .myproduct{
            width: 100%;
            height: 8vh;
            display: inline-block;
            padding-top: 3vh;
            padding-bottom: 3vh;
        }
        .myproduct p{

            text-align: center;
            color: black;
        }
        .chinese{
            font-size: 4em;
            font-weight: 700;
            line-height: 4vh;
        }
        .english{
            font-size: 2em;
            line-height: 3vh;
        }
        .myproducts{
            width: 100%;
            min-height: 40vh;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .myproductsson{
            width: 45%;
            height: 20vh;

        }
        .myproductsson_top{
            width: 100%;
            height: 17vh;
            position: relative;
        }
        .details{
            width: 100%;
            height: 3vh;
            background: black;
            opacity: 0.7;
            color: white;
            font-size: 2em;
            line-height: 3vh;
            text-align: right;
            position: absolute;
            bottom: 0;
        }
        .myproductsson_bottom{
            width: 100%;
            height: 3vh;
            text-align: center;
            line-height: 3vh;
            font-size: 2em;
        }
        img{width: 100%;height: 100%;}
        .myqualification{
            width: 100%;
            height: 30vh;
        }
        .machining{
            width: 100%;
            height: 45vh;
        }
        .contact{
            width: 100%;
            height: 10vh;
        }
    </style>
    <script src='/js/admin/hhSwipe.js' type="text/javascript"></script>
</head>

<body>
   <div class="myhead">晟铭酒坊</div>
   <div class="carousel">
       <div class="addWrap">
           <div class="swipe" id="mySwipe">
               <div class="swipe-wrap">
                   <div><a href="javascript:;"><img class="img-responsive"  src="/images/j1.jpg"/></a></div>
                   <div><a href="javascript:;"><img class="img-responsive"  src="/images/j2.jpg" /></a></div>
                   <!--<div><a href="javascript:;"><img class="img-responsive" src="images/banner2.jpg"/></a></div>-->
               </div>
           </div>

           <ul id="position">
               <li class="cur"></li>
               <li></li>
               <!--<li></li>-->
           </ul>
       </div><!--/addWrap-->
   </div>
   <div class="content">
       <div class="myproduct">
           <p class="chinese">主营产品</p>
           <p class="english">MAIN PRODUCTS</p>
       </div>
       <div class="myproducts">
           <div class="myproductsson">
               <div class="myproductsson_top">
                   <img src="/images/j1.jpg" style="width: 100%;height: 100%;" alt="">
                   <div class="details">我是详情介绍</div>
               </div>
               <div class="myproductsson_bottom">高粱白酒：43°/53°/65°</div>
           </div>
           <div class="myproductsson">
               <div class="myproductsson_top">
                   <img src="/images/j2.jpg" style="width: 100%;height: 100%;" alt="">
                   <div class="details">我是详情介绍</div>
               </div>
               <div class="myproductsson_bottom">玉米白酒：43°/53°/65°</div>
           </div>
           <div class="myproductsson">
               <div class="myproductsson_top">
                   <img src="/images/j1.jpg" style="width: 100%;height: 100%;" alt="">
                   <div class="details">我是详情介绍</div>
               </div>
               <div class="myproductsson_bottom">大米白酒：43°/53°/65°</div>
           </div>
           <div class="myproductsson">
               <div class="myproductsson_top">
                   <img src="/images/j2.jpg" style="width: 100%;height: 100%;" alt="">
                   <div class="details">我是详情介绍</div>
               </div>
               <div class="myproductsson_bottom">小米白酒：43°/53°/65°</div>
           </div>
       </div>
   </div>
   <div class="qualification">
       <div class="myproduct">
           <p class="chinese">经营许可</p>
           <p class="english">BUSINESS LICENSE</p>
       </div>
       <div class="myqualification">
           <img src="/images/j1.jpg" alt="">
       </div>
   </div>
   <div class="machining">
       <div class="myproduct">
           <p class="chinese">加工实拍</p>
           <p class="english">MACHIN VIDEO</p>
       </div>
       <div class="myqualification">
           <video  width="100%" height="100%" controls x5-playsinline  playsinline  webkit-playsinline="true">
               <source src="/images/jiuvideo.mp4" type="video/mp4">
           </video>
       </div>
   </div>
   <div class="contact">
       <div class="myproduct">
           <p class="chinese">联系我</p>
           <p class="english">CONTACT ME</p>
       </div>
       <div style="text-align: center"><img src="/images/myqr.jpg" alt="" style="width: 30%;height: 30%"></div>
       <div style="text-align: center;font-size: 2em;">扫码加好友或拨打电话：<a href="tel:13718881232">13718881232</a>订购。</div>
       <div style="width: 100%;height: 5vh"></div>
   </div>
</body>
<script type="text/javascript">
    var bullets = document.getElementById('position').getElementsByTagName('li');

    var banner = Swipe(document.getElementById('mySwipe'), {
        auto: 4000,
        continuous: true,
        disableScroll:false,
        callback: function(pos) {
            var i = bullets.length;
            while (i--) {
                bullets[i].className = ' ';
            }
            bullets[pos].className = 'cur';
        }
    })
</script>
</html>