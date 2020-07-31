/**
 * Created by 乔冠宇 on 2019/5/1.
 */
/***
 *关闭窗口
 * @param Object  选择器名称  #id、.class等
 */
function closeWindow( Object ) {
    $(Object).window({closed:true});

}

/***
 *打开窗口
 * @param Object  选择器名称  #id、.class等
 */
function openWindow( Object ) {
    $(Object).window({closed:false});
}

/**
 *保存数据
 * @param url //方法名称
 * @param form  //表单选择器名  #myform  .myform
 * @param win   //添加框选择器名 #mywindow .mywindow
 */
function saveData( url, form, win ) {
    $(form).form('submit', {
        url: url,
        onSubmit: function () {
        },
        success: function (data) {
            if(JSON.parse(data).code=='200'){
                $.messager.show({
                    title: '系统提示',
                    msg: JSON.parse(data).message,
                    showType: 'fade',
                    style: {
                        right: '',
                        bottom: ''
                    }
                });
                $(win).window({closed:true});
                /*刷新页面*/
                setTimeout(function(){window.location.reload(true)},3000);
            }else{
                $.messager.show({
                    title: '系统提示',
                    msg: JSON.parse(data).message,
                    showType: 'fade',
                    style: {
                        right: '30px;',
                        bottom: ''
                    }
                });
                $(win).window({closed:true});
                setTimeout(function(){window.location.reload(true)},3000);
            }
        }
    });
}

/**
 * 删除数据
 * @param url
 * @param id
 */
function deleteData( url, id) {
    $.ajax({
        url:url,
        type:"post",
        data:{id:id},
        success:function(data){
            if(JSON.parse(data).code=='200'){
                $.messager.show({
                    title: '系统提示',
                    msg: JSON.parse(data).message,
                    showType: 'fade',
                    style: {
                        right: '',
                        bottom: ''
                    }
                });
                setTimeout(function(){window.location.reload(true)},3000);
            }else {
                $.messager.show({
                    title: '系统提示',
                    msg: JSON.parse(data).message,
                    showType: 'fade',
                    style: {
                        right: '',
                        bottom: ''
                    }
                });
                setTimeout(function(){window.location.reload(true)},3000);
            }
        }
    });
}


