<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:82:"C:\phpStudy\PHPTutorial\WWW\tp\public/../application/index\view\food\listfood.html";i:1568633481;}*/ ?>
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
    <form action="<?php echo url('Index/Food/listfood'); ?>" id="search" method="post" style="display: inline-block;">
        <input type="text" name="name" id="searchContent" placeholder="菜品名称" value="<?php echo $sidDesc; ?>" style="height: 28px;">
        <a href="#" data-options="iconCls:'icon-search'" onclick="mysearch()" class="easyui-linkbutton">搜索</a>
    </form>
</div>


<table id="dg"></table>

<!--添加框-->

<div id="win" class="easyui-window" title="店铺"
     data-options="iconCls:'icon-save',modal:true,closed:true,draggable:true,resizable:true" style="top:30px;">
    <form name="addform" id="addform" action="" method="post"
          enctype="multipart/form-data">
        <input type="hidden" name="id" id="myid" value="">
        <table>
            <tr>
                <td> 名称 :</td>
                <td><input class="easyui-textbox myinput" id="name" name="name" ></td>
                <td> 单位:</td>
                <td><input class="easyui-textbox myinput" id="unit" name="unit"></td>
            </tr>
            <tr>
                <td> 单价 :</td>
                <td><input class="easyui-textbox myinput" id="price" name="price" ></td>

                <td>是否启用:</td>
                <td>
                    <input type="radio" name="status" value="1" checked>是
                    <input type="radio" name="status" value="0">否
                </td>
            </tr>
            <tr>
                <td> 是否打折 :</td>
                <td>
                    <select name="disstatus" class="easyui-combobox"
                            id="disstatus">
                        <option value="0">否</option>
                        <option value="1">是</option>
                    </select>
                </td>
                <td> 打折详情:</td>
                <td>
                    <select name="discount" class="easyui-combobox"
                            id="discount">
                        <option value="0">选择折扣</option>
                        <option value="0.1">1折</option>
                        <option value="0.2">2折</option>
                        <option value="0.3">3折</option>
                        <option value="0.4">4折</option>
                        <option value="0.5">5折</option>
                        <option value="0.6">6折</option>
                        <option value="0.7">7折</option>
                        <option value="0.8">8折</option>
                        <option value="0.9">9折</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>排序</td>
                <td>
                    <input class="easyui-textbox myinput" id="sort" name="sort">
                </td>
                <td> 所属分类 :</td>
                <td>
                    <select name="cid" class="easyui-combobox"
                            id="cid">
                        <option value="0">选择分类</option>
                        <?php if(is_array($categories) || $categories instanceof \think\Collection || $categories instanceof \think\Paginator): $i = 0; $__LIST__ = $categories;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </td>
            </tr>
            <tr style="height: 30px;">
                <td>口味包含:</td>
                <td colspan="3">
                    <?php if(is_array($tasteList) || $tasteList instanceof \think\Collection || $tasteList instanceof \think\Paginator): $i = 0; $__LIST__ = $tasteList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <label>
                            <span><?php echo $vo['name']; ?></span><input type="checkbox"  value="<?php echo $vo['id']; ?>" name="taste[]"><span>&nbsp;&nbsp;</span>
                        </label>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </td>
            </tr>
            <tr>
                <td>默认口味:</td>
                <td>
                    <select name="defaulttaste" id="defaulttaste"  class="easyui-combobox">
                        <option value="0">无</option>
                        <?php if(is_array($tasteList) || $tasteList instanceof \think\Collection || $tasteList instanceof \think\Paginator): $i = 0; $__LIST__ = $tasteList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </td>
                <td>是否必选:</td>
                <td>
                    <select name="ischose" id="ischose"  class="easyui-combobox">
                        <option value="0">否</option>
                        <option value="1">是</option>
                    </select>
                </td>
            </tr>
            <tr style="height: 30px;">
                <td> 图片:</td>
                <td colspan="3">
                  <input type="file" name="pic" >
                </td>
            </tr>
            <tr>
                <td>描述信息 </td>
                <td colspan="3">
                    <textarea name="desc" id="desc" style="width: 100%;height: 100%;" ></textarea>
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
            nowrap:true,
            columns: [
                [
                    {field: 'name', align: "center", title: "名称", width: 100},
                    {field: 'price', align: "center", title: "价格", width: 100},
                    {field: 'unit', align: "center", title: "单位", width: 100},
                    {field: 'cidDesc', align: "center", title: "所属分类", width: 100},
                    {field: 'discount', align: "center", title: "打折详情", width: 100},
                    {field: 'disstatusDesc', align: "center", title: "是否打折", width: 100},
                    {field: 'statusDesc', align: "center", title: "开启状态", width: 100},
                    {field: 'createtime', align: "center", title: "创建时间", width: 100}
                ]
            ],
            rowStyler:function(index,row) {
                if (row.status == 0) {
                    return 'background-color:pink;color:blue'
                }
            },

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

        $('#ss').searchbox({
            searcher:function(value,name){
                alert(value + "," + name)
            },
            menu:'#mm',
            prompt:'Please Input Value'
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
        saveData("<?php echo url('Index/Food/addfood'); ?>","#addform","#win");
    }
    /*打开编辑窗口*/
    function openEditWindow() {
        var row = $('#dg').datagrid('getSelected');
        if (row){
            /*给编辑窗口赋值*/
            $("#myid").val(row.id);
            $('#name').textbox('setValue', row.name);
            $('#unit').textbox('setValue', row.unit);
            $('#price').textbox('setValue', row.price);
            $('#sort').textbox('setValue', row.sort);
            $('#limit').combobox('setValue', row.limit);
            $('#disstatus').combobox('setValue', row.disstatus);
            $('#sid').combobox('setValue', row.sid);
            $('#discount').combobox('setValue', row.discount);
            $("#cid").combobox("setValue",row.cid);
            $("#ischose").combobox("setValue",row.ischose);
            $("#defaulttaste").combobox("setValue",row.deftaste);
            $("#desc").val(row.desc);
            //店铺不可切换更改
            $('#sid').combobox('readonly', true);
            if(row.status == 1){
                $("input[name='status'][value='1']").attr('checked',true);
            }else{
                $("input[name='status'][value='0']").attr('checked',true);
            }
            if(row.tastelist !=null){
                for (var i=0;i<row.tastelist.length;i++){
                    $("input[type=checkbox][value="+row.tastelist[i].tid+"]").attr("checked","checked");
                }
            }
            $("#win").window({closed:false});
        }
    }
    /*关闭对话框*/
    function cancelBtn() {
        closeWindow("#mydialog");
        window.location.reload(true);
    }
</script>
<script>
    function mysearch() {
        $("#search").submit();
    }
</script>


