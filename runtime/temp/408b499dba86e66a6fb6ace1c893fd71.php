<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:87:"C:\phpStudy\PHPTutorial\WWW\tp\public/../application/index\view\announcement\index.html";i:1564495460;}*/ ?>
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
       data-options="iconCls:'icon-add'" onclick="openAddCityWindow()">添加</a>
    <a href="javascript:void(0)" id="editCity" class="easyui-menubutton"
       data-options="iconCls:'icon-edit'" onclick="openEditWindow()">编辑</a>
</div>

<table id="dg"></table>

<!--添加框-->

<div id="win" class="easyui-window" title="添加公告"
     data-options="iconCls:'icon-save',modal:true,closed:true,draggable:true,resizable:true">
    <form name="addform" id="addform" action="" method="post"
          enctype="multipart/form-data">
        <input type="hidden" name="id" id="myid" value="">
        <table>
            <tr>
                <td> 选择店铺:</td>
                <td><select name="sid" class="easyui-combobox"
                            id="grade">
                    <?php if(is_array($stores) || $stores instanceof \think\Collection || $stores instanceof \think\Paginator): $i = 0; $__LIST__ = $stores;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
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
                <td>公告内容 </td>
                <td colspan="3">
                    <textarea name="content" id="content" cols="50" rows="5"></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <div style="float: right">
                        <a onclick="saveCity()" href="javascript:void(0)" class="easyui-linkbutton"
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
        <a href="#" class="easyui-linkbutton" onclick="deleteCityBtn()">确认</a>
        <a href="#" class="easyui-linkbutton" onclick="cancelBtn()">取消</a>
    </div>
</div>
<script>
    /*转为对象，初始化数据*/
    citytlist = JSON.parse('<?php echo $listJson; ?>');
    if(citytlist == null){
        citytlist = "";
    }
    $(document).ready(function () {
        $('#dg').datagrid({
            pagination: true,
            rownumbers: true,
            fitColumns: true,
            singleSelect: true,
            data: citytlist.slice(0, 10),
            columns: [
                [
                    {field: 'storename', align: "center", title: "店铺名称", width: 100},
                    {field: 'content', align: "center", title: "公告内容", width: 100},
                    {field: 'statusdesc', align: "center", title: "启用状态", width: 100},
                    {field: 'createtime', align: "center", title: "创建时间", width: 100}
                ]
            ],
            rowStyler:function(index,row) {
                if (row.status == 0) {
                    return 'background-color:pink;color:blue'
                }
            }
        });

        var citypager = $("#dg").datagrid("getPager");
        citypager.pagination({
            total: citytlist.length,
            pageSize:10,
            onSelectPage: function (pageNo, pageSize) {
                var start = (pageNo - 1) * pageSize;
                var end = start + pageSize;
                $("#dg").datagrid("loadData", citytlist.slice(start, end));
                citypager.pagination('refresh', {
                    total: citytlist.length,
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
    function openAddCityWindow() {
        openWindow("#win");
    }
    /*保存数据*/
    function saveCity() {
        saveData("<?php echo url('Index/Announcement/addannouncement'); ?>","#addform","#win");
    }
    /*打开编辑窗口*/
    function openEditWindow() {
        var row = $('#dg').datagrid('getSelected');
        if (row){
            /*给编辑窗口赋值*/
            $("#myid").val(row.id);
            $('#sid').combobox('setValue', row.grade);
            $("#content").val(row.content);
            if(row.status=== 1){
                $("input[name='status'][value='1']").attr('checked',true);
            }else{
                $("input[name='status'][value='0']").attr('checked',true);
            }
            $("#win").window({closed:false});
        }
    }
    /*打开提示框*/
    function deleteCity() {
        var row = $('#dg').datagrid('getSelected');
        if (row){
            $("#mydialog").html("确认删除？");
            $("#mydialog").window({closed:false});
        }
    }
    /*执行删除*/
    function deleteCityBtn() {
        var row = $('#dg').datagrid('getSelected');
        if (row){
            deleteData("<?php echo url('Index/City/deleteCity'); ?>",row.id);
        }
    }
    /*关闭对话框*/
    function cancelBtn() {
        closeWindow("#mydialog");
        window.location.reload(true);
    }
</script>





