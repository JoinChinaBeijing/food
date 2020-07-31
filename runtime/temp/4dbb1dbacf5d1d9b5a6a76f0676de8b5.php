<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:84:"C:\phpStudy\PHPTutorial\WWW\tp\public/../application/index\view\order\listorder.html";i:1575730071;}*/ ?>
<link rel="stylesheet" type="text/css" href="/easyui/themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="/css/admin/wu.css" />
<link rel="stylesheet" type="text/css" href="/css/admin/icon.css" />
<link rel="stylesheet" type="text/css" href="/css/admin/city.css" />
<script type="text/javascript" src="/easyui/jquery.min.js"></script>
<script type="text/javascript" src="/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="/easyui/locale/easyui-lang-zh_CN.js"></script>
<script type="text/javascript" src="/js/admin/mybase.js"></script>



<table id="tt" class="easyui-datagrid" style="width:100%;height:650px"
       url = "<?php echo url('index/Order/getOrders'); ?>"
       title="订单报表" iconCls="icon-save"
       rownumbers="true" singleSelect="true" pagination="true" data-options="pageSize:20" toolbar="#tb">
    <thead>
    <tr>
        <th field="orderid" width="15%" align="center" >订单号</th>
        <th field="amount" width="10%" align="center" >订单金额</th>
        <th field="type" width="10%" align="center">方式</th>
        <th field="deyamount" width="10%" align="center">运费</th>
        <th field="remark" width="20%" align="center">备注</th>
        <th field="status" width="10%" align="center">状态</th>
        <th field="breakfast" width="10%" align="center">订单类型</th>
        <th field="createtime" width="15%" align="center">创建时间</th>
    </tr>
    </thead>
</table>
<div id="tb" style="padding:5px;height: 50px;">
    <form action="<?php echo url('index/Order/report'); ?>" method="post" id="report">
        <span>开始时间:</span>
        <input id="start" name="start" type="text" value="<?php echo $start; ?>" class="easyui-datetimebox" required="required">
        <span>结束时间:</span>
        <input id="end" name="end" type="text" value="<?php echo $end; ?>" class="easyui-datetimebox" required="required">
        <span>订单状态:</span>
        <select name="orderstatus" id="orderstatus"  class="easyui-combobox">
            <option value="999">所有</option>
            <option value="1">已支付</option>
            <option value="9">未支付</option>
            <option value="4">已退款</option>
        </select>
        <a href="#" class="easyui-linkbutton" plain="true" onclick="doSearch()">搜索</a>
        <a href="#" class="easyui-linkbutton" plain="true" onclick="repot()">下载报表</a>
        <span>今日盈收：<?php echo $sell; ?></span>
    </form>
</div>
<script>

    function doSearch() {
        var start = $("#start").datetimebox('getValue');
        var end = $("#end").datetimebox('getValue');
        var status = $("#orderstatus").combobox("getValue");
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo url('index/Order/paramsset'); ?>" ,
            data: {start:start,end:end,status:status},
            success: function (result) {
                $('#tt').datagrid('reload');
            }
        });
    }

    function repot() {
        $("#report").submit();
    }
</script>

