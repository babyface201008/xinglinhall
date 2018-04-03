var table1 = null;
$(function () {
    table1 = initializeTable();
    $("#sel_menu2").select2({
        placeholder: "请选择菜单",
        width:300,
        maximumSelectionLength: 1 //最多能够选择的个数
    });
});
/*初始化table*/
function initializeTable() {
    var table = $("#adminmune-table").DataTable({
        /****************************************表格数据加载****************************************************/
        // "serverSide": true,
        "sAjaxSource": getMenuListJSON,
        "columns": [//列绑定
            { "data": "menuname" },
            { "data": "parentid" },
            { "data": "sort" },
            { "data": "url" },
            { "data": "icon" },
            { "data": "isshow" },
            { "data": "menuid" },
        ],
        "columnDefs": [//列定义
            {
                "targets": [4],
                "data": "icon",
                "render": function (data, type, full) {
                    if (data == null || data.trim() == "") { return "/"; }
                    else {
                        var iconhtml ='<div class="hidden-sm hidden-xs action-buttons" width="200px">  <img src="'+MenuIconUrl+'icons/'+data.substr(5)+'.png" alt=""> </div>';
                        return iconhtml;
                    }
                }
            },
            {
                "targets": [5],
                "data": "isshow",
                "render": function (data, type, full) {
                    if (data == 1 ) {
                        return "显示";
                    }else{
                        return "不显示";

                    }

                }
            },
            {
                "targets": [6],
                "data": "menuid",
                "render": function (data, type, full) {//全部列值可以通过full.列名获取,一般单个列值用data PS:这里的render是有多少列就执行多少次方法。。。不知道为啥
                    if (data == null || data.trim() == "") { return "/"; }
                    else {
                        var edithtml ='<div class="hidden-sm hidden-xs action-buttons" width="200px"> <a class="green" href="javascript:void(0)" onclick="adminMuneEdit('+data+')" > <i class="ace-icon fa fa-pencil bigger-130"></i> </a> <a class="red" href="javascript:void(0)" onclick="adminMuneDel('+data+')"> <i class="ace-icon fa fa-trash-o bigger-130"></i> </a> </div>';
                        return edithtml;
                    }
                }
            },
        ],
        "rowCallback": function (row, data, displayIndex) {//行定义
            if (data.parentid != "0") {
                $(row).attr("class", "text-c treegrid-" + data.menuid + " treegrid-parent-" + data.parentid);
            } else {
                $(row).attr("class", "text-c treegrid-" + data.menuid);
            }
        },
        "initComplete": function (settings, json) { //表格初始化完成后调用
            $("#adminmune-table").treegrid({
                "initialState": 'collapsed',
            });
        },
        /****************************************表格数据加载****************************************************/
        /****************************************表格样式控制****************************************************/
        "processing": false,//等待加载效果
        "language": {//语言国际化
            "lengthMenu": "每页 _MENU_ 条",
            "zeroRecords": "没有找到记录",
            "info": "当前显示 _START_ 到 _END_ 条，共 _TOTAL_条",
            "infoEmpty": "无记录",
            "paginate":
                {
                    "first": "首页",
                    "previous": "前一页",
                    "next": "后一页",
                    "last": "末页"
                },
            // "processing": false,
            "loadingRecords": "加载记录中...",//注意该参数在从服务器加载的时候无效，只有Ajax和客户端处理的时候有效
        },
        "searching": true,
        "dom": '<"toolbar">frtip',
        "paging": false,//分页功能
        "ordering": false,//排序功能
        "autoWidth": false,//自动宽度（这里关闭后，可以随着左侧的隐藏而扩展页面一起100%宽度）
        /****************************************表格样式控制****************************************************/
    });
    $("div.toolbar").html('<a  href="javascript:void(0)" onclick="adminMuneAdd()" ><button class="btn btn-info btn-sm" style="margin: 10px" type="button"> 添加菜单</button></a>');
    return table;
}

function  adminMuneAdd(id) {
    $.ajax({
        type : 'post',
        url : MenuAddurl,
        data : {menuid : 1},
        beforeSend : function(){
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
        },
        success : function(data){
            layer.closeAll()
            $('#adminMuneAddModal').modal({keyboard: true})
            $('#adminMuneAddContent').html(data);

        },
        error : function(){
            layer.closeAll()
            layer.msg('系统出错，请联系管理员！');
            $('#adminMuneEditModal').modal('hide');
        }
    })
}

function  adminMuneEdit(id) {
    $.ajax({
        type : 'post',
        url : MenuEditurl,
        data : {menuid : id},
        beforeSend : function(){
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
        },
        success : function(data){
            layer.closeAll()
            $('#adminMuneEditModal').modal({keyboard: true})
            $('#adminMuneEditContent').html(data);

        },
        error : function(){
            layer.closeAll()
            layer.msg('系统出错，请联系管理员！');
            $('#adminMuneEditModal').modal('hide');
        }
    })
}

function  adminMuneDel(id) {
    layer.confirm('确定要删除所选的记录？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        $.ajax({
            type : 'post',
            url : MenuDelurl,
            data : {menuid : id},
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
                    setTimeout(function () {
                        window.location.reload()
                    },1500)
                }else{
                    layer.msg(info.message);
                }
            },
            error : function(){
                layer.msg('系统出错，请联系管理员！');
                $('#adminEditModal').modal('hide');
            }
        })

    });
}

function adminMunesave(obj) {
        var form = layui.form();
        form.on('submit(adminMunform)', function(data) {
            if($('.parentid').val()==null){
                $('.select2-search__field').focus()
                layer.msg('请选择父级id', {icon: 5,shift:6});
                return false;
            }
            var pram = $(obj).parent().parent().serialize();
            $.ajax({
                type: 'post',
                url: Menusaveurl,
                data: pram,
                success: function (data) {
                    var info = JSON.parse(data);
                    if (info.statuscode == '200') {
                        layer.msg(info.message);
                        $('#adminMuneAddModal').modal('hide');
                        $('#adminMuneEditModal').modal('hide');
                        setTimeout(function () {
                            window.location.reload()
                        }, 1500)
                    } else {
                        layer.msg(info.message);
                    }
                },
                error: function () {
                    layer.msg('系统出错，请联系管理员！');
                    $('#adminMuneAddModal').modal('hide');
                }
            })
        });
}


