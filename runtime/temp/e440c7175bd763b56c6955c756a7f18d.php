<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:80:"C:\phpStudy\PHPTutorial\WWW\tp\public/../application/index\view\index\index.html";i:1560519117;s:68:"C:\phpStudy\PHPTutorial\WWW\tp\application\index\view\adminbase.html";i:1565090854;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>后台管理</title>

    <link rel="stylesheet" type="text/css" href="/easyui/themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="/css/admin/wu.css" />
    <link rel="stylesheet" type="text/css" href="/css/admin/icon.css" />

    <script type="text/javascript" src="/easyui/jquery.min.js"></script>
</head>
<body class="easyui-layout">
<!-- begin of header -->
<div class="wu-header" data-options="region:'north',border:false,split:true">
    <div class="wu-header-left">
        <h1>后台管理系统</h1>
    </div>
    <div class="wu-header-right">
        <p><strong class="easyui-tooltip" title="2条未读消息"><?php echo session('name')?></strong>，欢迎您！</p>
        <p><a href="#">网站首页</a> | <a href="<?php echo url('login/logout'); ?>">安全退出</a></p>
    </div>
</div>
<!-- end of header -->
<!-- begin of sidebar -->
<div class="wu-sidebar" data-options="region:'west',split:true,border:true,title:'导航菜单'">
    <div class="easyui-accordion" data-options="border:false,fit:true">
       <!-- 循环体开始-->
        <div title="小吃管理" data-options="iconCls:'icon-creditcards'" style="padding:5px;">
            <ul class="easyui-tree wu-side-tree">
                <li iconCls="icon-users"><a href="javascript:void(0)" data-icon="icon-users" data-link="<?php echo url('index/City/listCity'); ?>" iframe="1">城市管理</a></li>
                <li iconCls="icon-users"><a href="javascript:void(0)" data-icon="icon-users" data-link="<?php echo url('index/School/listSchool'); ?>" iframe="1">学校管理</a></li>
                <li iconCls="icon-build"><a href="javascript:void(0)" data-icon="icon-build" data-link="<?php echo url('index/Store/liststore'); ?>" iframe="1">店铺管理</a></li>
                <li iconCls="icon-build"><a href="javascript:void(0)" data-icon="icon-build" data-link="<?php echo url('index/Estimate/index'); ?>" iframe="1">估清管理</a></li>
                <li iconCls="icon-chart-organisation"><a href="javascript:void(0)" data-icon="icon-chart-organisation" data-link="<?php echo url('index/Category/listcategory'); ?>" iframe="1">分类管理</a></li>
                <li iconCls="icon-asterisk-yellow"><a href="javascript:void(0)" data-icon="icon-asterisk-yellow" data-link="<?php echo url('index/Storecate/listStoreCate'); ?>" iframe="1">类别分配</a></li>
                <li iconCls="icon-remove"><a href="javascript:void(0)" data-icon="icon-remove" data-link="<?php echo url('index/Food/listfood'); ?>" iframe="1">菜品管理</a></li>
                <li iconCls="icon-filter"><a href="javascript:void(0)" data-icon="icon-filter" data-link="<?php echo url('index/Taste/listtaste'); ?>" iframe="1">口味管理</a></li>
                <li iconCls="icon-filter"><a href="javascript:void(0)" data-icon="icon-filter" data-link="<?php echo url('index/Announcement/index'); ?>" iframe="1">公告管理</a></li>
                <li iconCls="icon-bookmark-edit"><a href="javascript:void(0)" data-icon="icon-bookmark-edit" data-link="<?php echo url('index/Integral/listintegral'); ?>" iframe="1">积分管理</a></li>
                <li iconCls="icon-sum"><a href="javascript:void(0)" data-icon="icon-sum" data-link="<?php echo url('index/exchange/index'); ?>" iframe="1">积分兑换</a></li>
                <li iconCls="icon-border-all"><a href="javascript:void(0)" data-icon="icon-border-all" data-link="<?php echo url('index/Order/listorder'); ?>" iframe="1">报表管理</a></li>
                <li iconCls="icon-border-all"><a href="javascript:void(0)" data-icon="icon-border-all" data-link="<?php echo url('index/Order/listrefund'); ?>" iframe="1">订单管理</a></li>
                <li iconCls="icon-bin-closed"><a href="javascript:void(0)" data-icon="icon-bin-closed" data-link="<?php echo url('user/index'); ?>" iframe="1">管理员管理</a></li>
                <li iconCls="icon-basket-go"><a href="javascript:void(0)" data-icon="icon-basket-go" data-link="<?php echo url('role/index'); ?>" iframe="1">角色管理</a></li>
                <li iconCls="icon-lock"><a href="javascript:void(0)" data-icon="icon-lock" data-link="<?php echo url('permission/index'); ?>" iframe="1">权限列表</a></li>
                <li iconCls="icon-print"><a href="javascript:void(0)" data-icon="icon-print" data-link="<?php echo url('Prints/index'); ?>" iframe="1">打印管理</a></li>
                <li iconCls="icon-tip"><a href="javascript:void(0)" data-icon="icon-tip" data-link="<?php echo url('Prints/manage'); ?>" iframe="1">打印机管理</a></li>
                <li iconCls="icon-help"><a href="javascript:void(0)" data-icon="icon-help" data-link="<?php echo url('Setting/index'); ?>" iframe="1">系统配置</a></li>
            </ul>
        </div>
        <!--循环体结束-->
    </div>
</div>
<!-- end of sidebar -->


<!-- begin of main -->
<div class="wu-main" data-options="region:'center'">
    <div id="wu-tabs" class="easyui-tabs" data-options="border:false,fit:true">
        <div title="首页" data-options="">
            
后台管理首页

        </div>
    </div>
</div>


<!-- end of main -->
<!-- begin of footer -->
<div class="wu-footer" data-options="region:'south',border:true,split:true">
    &copy; 2019 Wu All Rights Reserved
</div>
<!-- end of footer -->


<script type="text/javascript" src="/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="/easyui/locale/easyui-lang-zh_CN.js"></script>
<script type="text/javascript" src="/easyui/plugins/jquery.datagrid.js"></script>
<script>
    $(function(){
        $('.wu-side-tree a').bind("click",function(){
            var title = $(this).text();
            var url = $(this).attr('data-link');
            var iconCls = $(this).attr('data-icon');
            var iframe = $(this).attr('iframe')==1?true:false;
            addTab(title,url,iconCls,iframe);
        });
    });
    /**
     * Name 选项卡初始化
     */
    $('#wu-tabs').tabs({
        tools:[{
            iconCls:'icon-reload',
            border:false,
            handler:function(){
                $('#wu-datagrid').datagrid('reload');
            }
        }]
    });

    /**
     * Name 添加菜单选项
     * Param title 名称
     * Param href 链接
     * Param iconCls 图标样式
     * Param iframe 链接跳转方式（true为iframe，false为href）
     */
    function addTab(title, href, iconCls, iframe){
        var tabPanel = $('#wu-tabs');
        if(!tabPanel.tabs('exists',title)){
            var content = '<iframe scrolling="auto" frameborder="0"  src="'+ href +'" style="width:100%;height:100%;"></iframe>';
            if(iframe){
                tabPanel.tabs('add',{
                    title:title,
                    content:content,
                    iconCls:iconCls,
                    fit:true,
                    cls:'pd3',
                    closable:true
                });
            }
            else{
                tabPanel.tabs('add',{
                    title:title,
                    href:href,
                    iconCls:iconCls,
                    fit:true,
                    cls:'pd3',
                    closable:true
                });
            }
        }
        else
        {
            tabPanel.tabs('select',title);
        }
    }
    /**
     * Name 移除菜单选项
     */
    function removeTab(){
        var tabPanel = $('#wu-tabs');
        var tab = tabPanel.tabs('getSelected');
        if (tab){
            var index = tabPanel.tabs('getTabIndex', tab);
            tabPanel.tabs('close', index);
        }
    }
</script>

</body>
</html>

