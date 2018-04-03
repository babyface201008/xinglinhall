var myTable =null
jQuery(function($) {
    //initiate dataTables plugin
    myTable =
        $('#admingroup-table').DataTable( {
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "aaSorting" : [[0, "asc"]], //默认的排序方式，第1列，升序排列
                "info": true,
                "autoWidth": false,
                "destroy":true,
                "processing":false,
                "scrollX": true,   //水平新增滚动轴
//          "serverSide":true,    //true代表后台处理分页，false代表前台处理分页
                "PaginationType" : "full_numbers", //详细分页组，可以支持直接跳转到某页
                //当处理大数据时，延迟渲染数据，有效提高Datatables处理能力
                "deferRender": true,
				/*是否开启主题*/
                "bJQueryUI": true,
                "oLanguage": {    // 语言设置
                    "sLengthMenu": "每页显示 _MENU_ 条记录",
                    "sZeroRecords": "抱歉， 没有找到",
                    "sInfo": "从 _START_ 到 _END_ /共 _TOTAL_ 条数据",
                    "sInfoEmpty": "没有数据",
                    "sInfoFiltered": "(从 _MAX_ 条数据中检索)",
                    "sZeroRecords": "没有检索到数据",
                    "sSearch": "检索:",
                    "oPaginate": {
                        "sFirst": "首页",
                        "sPrevious": "前一页",
                        "sNext": "后一页",
                        "sLast": "尾页"
                    }
                },

                // "bProcessing": true,
                // "bServerSide": true,
                "sAjaxSource": getGroupListJSON,
                // "ajax":{
                //     url:getGroupListJSON,
                //     dataSrc:
                //         function(data){
                //             if(data.callbackCount==null){
                //                 data.callbackCount=0;
                //             }
                //             //抛出异常
                //             if(data.sqlException){
                //                 alert(data.sqlException);
                //             }
                //             //查询结束取消按钮不可用
                //             $("#queryDataByParams").removeAttr("disabled");
                //             return data.dataList;             //自定义数据源，默认为data
                //         },     //dataSrc相当于success，在datatable里面不能用success方法，会覆盖datatable内部回调方法
                //     type:"post",
                //     data:queryData
                // },

                columns: [
                    {
                        "data": "groupname"
                    },
                    {
                        "data": "createtime"
                    },
                    {
                        "data": "desc"
                    },
                    {
                        "data": "status"
                    },
                    {"data":'groupid',
                        "render": function ( data) {
                            var edithtml ='<div class="hidden-sm hidden-xs action-buttons" width="200px"> <a class="blue" href="javascript:void(0)" onclick="groupMuneEdit('+data+')" > <i class="ace-icon fa fa-search-plus bigger-130"></i> </a><a class="green" href="javascript:void(0)" onclick="adminGroupEdit('+data+')" > <i class="ace-icon fa fa-pencil bigger-130"></i> </a> <a class="red" href="javascript:void(0)" onclick="adminGroupDel('+data+')"> <i class="ace-icon fa fa-trash-o bigger-130"></i> </a> </div>';
                            return edithtml;
                        }
                    }
                ],
                "dom": '<"toolbar">frtip',
                "autoFill": true,

            }
        );
    $("div.toolbar").html('<a href="javascript:void(0)" onclick="groupMuneAdd()" ><button class="btn btn-info btn-sm" style="margin: 10px" type="button">添加角色</button></a>');
    $.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';

    myTable.buttons().container().appendTo( $('.tableTools-container') );

    //style the message box


});
// 新增角色权限
function  groupMuneAdd() {
    $.ajax({
        type : 'post',
        url : GroupAddurl,
        data : {groupid : 1},
        beforeSend : function(){
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
        },
        success : function(data){
            layer.closeAll()
            $('#admingroupAddModal').modal({keyboard: true})
            $('#admingroupAddContent').html(data);
        },
        error : function(){
            layer.closeAll()
            layer.msg('系统出错，请联系管理员！');
        }
    })
}
// 编辑权限
function  groupMuneEdit(id) {
    $.ajax({
        type : 'post',
        url : getMenuListJSON,
        data : {groupid : id},
        beforeSend : function(){
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
        },
        success : function(data){
            layer.closeAll()
            $('#admingroupMuneModal').modal({keyboard: true})
            $('#admingroupMuneContent').html(data);

        },
        error : function(){
            layer.closeAll()
            layer.msg('系统出错，请联系管理员！');
            $('#admingroupMuneModal').modal('hide');
        }
    })
}
// 编辑角色

function  adminGroupEdit(id) {
    $.ajax({
        type : 'post',
        url : GroupEditurl,
        data : {groupid : id},
        beforeSend : function(){
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
        },
        success : function(data){
            layer.closeAll()
            $('#admingroupEditModal').modal({keyboard: true})
            $('#admingroupEditContent').html(data);

        },
        error : function(){
            layer.closeAll()
            layer.msg('系统出错，请联系管理员！');
            $('#admingroupEditModal').modal('hide');
        }
    })
}
// 删除角色
function  adminGroupDel(id) {
    layer.confirm('确定要删除所选的记录？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        $.ajax({
            type : 'post',
            url : GroupDelurl,
            data : {ids : id},
            beforeSend : function(){
                var index = layer.load(1, {
                    shade: [0.1,'#fff'] //0.1透明度的白色背景
                });
            },
            success : function(data){
                layer.closeAll()
                var info = JSON.parse(data);
                if(info.statuscode == '200'){
                    layer.msg('删除成功！');
                    myTable.ajax.reload();
                }else{
                    layer.msg(info.message);
                }
            },
            error : function(){
                layer.msg('系统出错，请联系管理员！');
            }
        })

    });
}
// 保存角色信息
function admingroupsave(obj) {
    var form = layui.form();
    form.on('submit(admingroupform)', function(data) {
        var pram = $(obj).parent().parent().serialize();

        if ($(obj).attr('url') != undefined) {
            var saveurl = $(obj).attr('url')
        }else {
            var saveurl =Groupsaveurl
        }
        $.ajax({
            type: 'post',
            url: saveurl,
            data: pram,
            success: function (data) {
                var info = JSON.parse(data);
                if (info.statuscode == '200') {
                    layer.msg(info.message);
                    $('#admingroupAddModal').modal('hide');
                    $('#admingroupMuneModal').modal('hide');
                    $('#admingroupEditModal').modal('hide')
                    myTable.ajax.reload();
                } else {
                    layer.msg(info.message);
                }
            },
            error: function () {
                layer.msg('系统出错，请联系管理员！');
            }
        })
    })
}


