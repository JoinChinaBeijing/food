<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:84:"C:\phpStudy\PHPTutorial\WWW\tp\public/../application/index\view\store\liststore.html";i:1564999929;}*/ ?>
<link rel="stylesheet" type="text/css" href="/easyui/themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="/css/admin/wu.css" />
<link rel="stylesheet" type="text/css" href="/css/admin/icon.css" />
<link rel="stylesheet" type="text/css" href="/css/admin/city.css" />
<script type="text/javascript" src="/easyui/jquery.min.js"></script>
<script type="text/javascript" src="/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="/easyui/locale/easyui-lang-zh_CN.js"></script>
<script type="text/javascript" src="/js/admin/mybase.js"></script>

<div id="operatBox">
    <a href="javascript:void(0)" id="addCity" class="easyui-menubutton"
       data-options="iconCls:'icon-add'" onclick="openAddWindow()">添加</a>
    <a href="javascript:void(0)" id="editCity" class="easyui-menubutton"
       data-options="iconCls:'icon-edit'" onclick="openEditWindow()">编辑</a>
    <a href="javascript:void(0)" id="deleteCity" class="easyui-menubutton"
       data-options="iconCls:'icon-cut'" onclick="deleteBtn()">删除</a>
</div>

<table id="dg"></table>

<!--添加框-->

<div id="win" class="easyui-window" title="店铺"
     data-options="iconCls:'icon-save',modal:true,closed:true,draggable:true,resizable:true">
    <form name="addform" id="addform" action="" method="post"
          enctype="multipart/form-data">
        <input type="hidden" name="id" id="myid" value="">
        <table>
            <tr>
                <td> 店铺名称 :</td>
                <td><input class="easyui-textbox myinput" id="name" name="name" ></td>
                <td> 店铺地址:</td>
                <td><input class="easyui-textbox myinput" id="address" name="address"></td>
            </tr>
            <tr>
                <td> 联 系 人 :</td>
                <td><input class="easyui-textbox myinput" id="principal" name="principal" ></td>
                <td> 联系电话:</td>
                <td><input class="easyui-textbox myinput" id="phone" name="phone"></td>
            </tr>
            <tr>
                <td> 城市等级:</td>
                <td><select name="cid" class="easyui-combobox"
                            id="cid">
                    <option value="0">选择所属城市</option>
                    <?php if(is_array($cityList) || $cityList instanceof \think\Collection || $cityList instanceof \think\Paginator): $i = 0; $__LIST__ = $cityList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
                </td>
                <td>是否启用 </td>
                <td>
                    <input type="radio" name="status" value="1" checked>是
                    <input type="radio" name="status" value="0">否
                </td>
            </tr>

            <tr>
                <td> 指定学校:</td>
                <td colspan="3">
                    <?php if(is_array($schoolList) || $schoolList instanceof \think\Collection || $schoolList instanceof \think\Paginator): $i = 0; $__LIST__ = $schoolList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <span><?php echo $vo['name']; ?></span><input type="checkbox"  name="school[]" id="school<?php echo $vo['id']; ?>" value="<?php echo $vo['id']; ?>">&nbsp;&nbsp;&nbsp;
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </td>
            </tr>
            <tr>
                <td> 是否小区:</td>
                <td colspan="3">
                    <input type="radio" name="district" value="1" >是
                    <input type="radio" name="district" value="0" checked>否
                </td>
            </tr>
            <tr>
                <td> 开业时间:</td>
                <td>
                    <input id="opentime" class="easyui-timespinner" name="opentime" >
                </td>
                <td> 停业时间:</td>
                <td>
                    <input id="closetime" class="easyui-timespinner" name="closetime" >
                </td>
            </tr>
            <tr>
                <td>关店提示:</td>
                <td colspan="3">
                    <textarea name="tips" id="tips" style="width: 100%;" rows="5"></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <div style="float: right">
                        <a onclick="saveStore()" href="javascript:void(0)" class="easyui-linkbutton"
                           data-options="iconCls:'icon-ok'">确定</a>
                        <a onclick="closeAddWindow()" href="javascript:void(0)" class="easyui-linkbutton"
                           data-options="iconCls:'icon-no'">取消</a>
                    </div>
                </td>
            </tr>
        </table>
    </form>
    <div id= "mydialog" class="easyui-dialog" style="width:300px;height:150px;text-align:center;line-height: 65px;"
         data-options="title:'系统提示',buttons:'#bb',modal:true,closed:true">
    </div>
    <div id="bb">
        <a href="#" class="easyui-linkbutton" onclick="dodelete()">确认</a>
        <a href="#" class="easyui-linkbutton" onclick="cancelBtn()">取消</a>
    </div>
</div>
<script>
    /*转为对象，初始化数据*/
    datatlist = JSON.parse('<?php echo $listJson; ?>');
    if(datatlist == null){
        datatlist = "";
    }
        $(document).ready(function () {
            $('#dg').datagrid({
                pagination: true,
                rownumbers: true,
                fitColumns: true,
                singleSelect: true,
                data: datatlist.slice(0, 10),
                columns: [
                    [
                        {field: 'cidDesc', align: "center", title: "所属城市", width: 100},
                        {field: 'name', align: "center", title: "店铺名称", width: 100},
                        {field: 'principal', align: "center", title: "负责人", width: 100},
                        {field: 'phone', align: "center", title: "联系电话", width: 100},
                        {field: 'address', align: "center", title: "联系地址", width: 100},
                        {field: 'statusDesc', align: "center", title: "开启状态", width: 100},
                        {field: 'createtime', align: "center", title: "创建时间", width: 100}
                    ]
                ],
                rowStyler:function(index,row) {
                    if (row.status == 0) {
                        return 'background-color:pink;color:blue'
                    }
                }
            });
            var datapager = $("#dg").datagrid("getPager");
            datapager.pagination({
                total: datatlist.length,
                pageSize:10,
                onSelectPage: function (pageNo, pageSize) {
                    var start = (pageNo - 1) * pageSize;
                    var end = start + pageSize;
                    $("#dg").datagrid("loadData", datatlist.slice(start, end));
                    datapager.pagination('refresh', {
                        total: datatlist.length,
                        pageNumber: pageNo
                    });
                }
            });
            $('#win').window({
                onClose: function () {
                    window.location.reload(true);
                }
            });
        });

    /*关闭添加窗口*/
    function closeAddWindow() {
        closeWindow("#win");
        window.location.reload(true);
    }

    /*打开添加窗口*/
    function openAddWindow() {
        openWindow("#win");
    }
    /*保存数据*/
    function saveStore() {
        saveData("<?php echo url('Index/Store/addstore'); ?>","#addform","#win");
    }
    /*打开编辑窗口*/
    function openEditWindow() {
        var row = $('#dg').datagrid('getSelected');
        if (row){
            /*给编辑窗口赋值*/
            $("#myid").val(row.id);
            $('#name').textbox('setValue', row.name);
            $('#address').textbox('setValue', row.address);
            $('#principal').textbox('setValue', row.principal);
            $('#phone').textbox('setValue', row.phone);
            $("#cid").combobox("setValue",row.cid);
            $("#opentime").textbox('setValue', row.opentime);
            $("#closetime").textbox('setValue', row.closetime);
            $("#tips").val(row.tips);
            if(row.status == 1){
                $("input[name='status'][value='1']").attr('checked',true);
            }else{
                $("input[name='status'][value='0']").attr('checked',true);
            }
            if(row.district == 1){
                $("input[name='district'][value='1']").attr('checked',true);
            }else{
                $("input[name='district'][value='0']").attr('checked',true);
            }
            if(row.school){
                for(var i = 0;i<row.school.length; i++){
                    $("#school"+row.school[i].scid).prop("checked","checked");
                }
            }
            $("#win").window({closed:false});
        }
    }
    /*打开提示框*/
    function deleteBtn() {
        var row = $('#dg').datagrid('getSelected');
        if (row){
            $("#mydialog").html("确认删除？");
            $("#mydialog").window({closed:false});
        }
    }
    /*执行删除*/
    function dodelete() {
        var row = $('#dg').datagrid('getSelected');
        if (row){
            deleteData("<?php echo url('Index/Store/deleteStore'); ?>",row.id);
        }
    }
    /*关闭对话框*/
    function cancelBtn() {
        closeWindow("#mydialog");
        window.location.reload(true);
    }
</script>




