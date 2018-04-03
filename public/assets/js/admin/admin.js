var table1 = null;
jQuery(function($) {
    //initiate dataTables plugin
   table1 =
        $('#dynamic-table').DataTable( {
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
            "sAjaxSource": getAdminList,
            columns: [
                // {
                //     "data": "adminid"
                // },
                {
                    "data": "adminname"
                },
                {
                    "data": "realname"
                },
                {
                    "data": "email"
                },
                {
                    "data": "phone"
                },
                {
                    "data": "last_login_ip"
                },
                {
                    "data": "createtime"
                },
                {
                    "data": "last_login_time"
                },
                {
                    "data": "islocked"
                },
                {"data":'adminid',
                    "render": function ( data) {
                        var edithtml ='<div class="hidden-sm hidden-xs action-buttons" width="200px"> <a class="green" href="javascript:void(0)" onclick="adminEdit('+data+')" ><i class="ace-icon fa fa-pencil bigger-130"></i></a> <a class="red" href="javascript:void(0)" onclick="adminDel('+data+')"> <i class="ace-icon fa fa-trash-o bigger-130"></i> </a> </div>';
                        return edithtml;
                    }
                }
            ],
            "dom": '<"toolbar">frtip',
            "autoFill": true,

            }
        );
    $("div.toolbar").html('<a  onclick="adminAdd()" ><button class="btn btn-info btn-sm" style="margin: 10px" type="button"> 添加管理员</button></a>');
    $.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';

    table1.buttons().container().appendTo( $('.tableTools-container') );

});

function  adminAdd() {
    $.ajax({
        type : 'post',
        url : getAdminadd,
        data : {menuid : 1},
        beforeSend : function(){
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
        },
        success : function(data){
            layer.closeAll()
            $('#adminAddModal').modal({keyboard: true})
            $('#adminAddContent').html(data);

        },
        error : function(){
            layer.closeAll()
            layer.msg('系统出错，请联系管理员！');
            $('#adminAddModal').modal('hide');
        }
    })
}

function  adminEdit(id) {
    $.ajax({
        type : 'post',
        url : getAdminEditData+'?adminid='+id,
        data : {ids : id},
        beforeSend : function(){
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
        },
        success : function(data){
            layer.closeAll()
            $('#adminEditModal').modal({keyboard: true})
            $('#adminEditContent').html(data);

        },
        error : function(){
            layer.closeAll()
            layer.msg('系统出错，请联系管理员！');
            $('#adminEditModal').modal('hide');
        }
    })
}

function  adminDel(id) {
    layer.confirm('确定要删除所选的记录？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        $.ajax({
            type : 'post',
            url : deleteAdmin,
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
                    table1.ajax.reload();
                    // setTimeout(function () {
                    //     window.location.reload()
                    // },1500)
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

function adminsave(obj) {

    var form = layui.form();
    form.on('submit(adminform)', function(data) {
    var email=$('.email').val();
    var phone=$('.phone').val();
    var realname=$('.realname').val();
    var adminname =$('.adminname').val()
    if(adminname && !verifyNickname(adminname) ){
        layer.tips('用户名应以字母开头，数字和字母组成的6~20字符串', $('.adminname'), {
            tips: [1, '#3595CC'],
            time: 4000
        });
        return false;
    }
    if(email && !verifyEmail(email) ){
        layer.tips('请输入正确的邮箱', $('.email'), {
            tips: [1, '#3595CC'],
            time: 4000
        });
        return false;
    }
    if(phone && !verifyPhone(phone) ){
        layer.tips('请输入正确的手机号', $('.phone'), {
            tips: [1, '#3595CC'],
            time: 4000
        });
        return false;
    }
    if(realname && !verifyNickname(realname) ){
        layer.tips('请输入正确的姓名', $('.realname'), {
            tips: [1, '#3595CC'],
            time: 4000
        });
        return false;
    }

    var  pram=$(obj).parent().parent().serialize();
    $.ajax({
        type : 'post',
        url : saveAdmin,
        data :pram ,
        success : function(data){
            var info = JSON.parse(data);
            if(info.statuscode == '200'){
                layer.msg(info.message);
                $('#adminAddModal').modal('hide');
                $('#adminEditModal').modal('hide')
                table1.ajax.reload();

            }else{
                layer.msg(info.message);
            }
        },
        error : function(){
            layer.msg('系统出错，请联系管理员！');
            $('#adminAddModal').modal('hide');
        }
    })
    });
}


//随机密码获取
function getpassword(){
    $('.addpassword').val(getRandPassword(8,16));
};

//创建一个随机密码
var getRandPassword = function(min,max){
    var source = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789',
        length = Math.ceil(Math.random()*(max-min)+min),
        password = '';
    for (var i = 0; i < length; i++) {
        password += source.charAt(Math.ceil(Math.random()*1000)%source.length);
    }
    return password;
};


