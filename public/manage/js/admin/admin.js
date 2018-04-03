/**
 * Created by A01 on 2017/7/1.
 */
layui.config({
    base : "js/"
}).use(['form','layer','jquery','laypage'],function(){
    var form = layui.form(),
        layer = parent.layer === undefined ? layui.layer : parent.layer,
        $ = layui.jquery;
    var laypage = layui.laypage

    //查询
    $(".search_btn").click(function() {
        loadList();
    });

    //重置查询
    $(".reload_btn").click(function() {
        $(".search_input").val('')
        loadList()
    })
    //添加
    $(".usersAdd_btn").click(function(){
        var index = layui.layer.open({
            title : "添加管理员",
            type : 2,
            content : [addAdminUrl,'on'],
            success : function(layero, index){
                layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                    tips: 3
                });
            }
        })
        //改变窗口大小时，重置弹窗的高度，防止超出可视区域（如F12调出debug的操作）
        $(window).resize(function(){
            layui.layer.full(index);
        })
        layui.layer.full(index);
    })

    //编辑
    $("body").on("click",".admin_edit",function(){  //编辑
        var id=$(this).data('id');
        var index = layui.layer.open({
            title : "编辑管理员",
            type : 2,
            content : [editAdminUrl+'?adminid='+id,'on'],
            success : function(layero, index){
                layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                    tips: 3
                });
            }
        })
        //改变窗口大小时，重置弹窗的高度，防止超出可视区域（如F12调出debug的操作）
        $(window).resize(function(){
            layui.layer.full(index);
        })
        layui.layer.full(index);
    });

    //删除
    $("body").on("click",".admin_del",function(){
        var _this = $(this);
        layer.confirm('确定删除此信息？',{icon:3, title:'提示信息'},function(index){
            $.ajax({
                url : delAdminUrl,
                type : "post",
                data:{adminid:_this.data('id')},
                dataType : "json",
                success : function(data){
                    layer.close(index);
                    layer.msg(data.message);
                    $("#curr").val(1)
                    loadList()
                }
            })

        });
    })


    //加载数据
    function loadList() {
        var keyword=$(".search_input").val();
        var curr=$("#curr").val();
        $.ajax({
            url : listAdminUrl,
            type : "post",
            data:{keyword:keyword,curr:curr},
            dataType : "json",
            success : function(data){
                if(data.code==200){
                    $("#curr").val(data.data.current_page);
                    var adminData =data.data.data;
                    var html='';
                    if(adminData.length>0){
                        for(var i=0;i<adminData.length;i++){
                            html+='<tr>' +
                                ' <td align="left">'+adminData[i].adminname+'</td>'+
                                ' <td>'+adminData[i].realname+'</td>'+
                                '<td>'+adminData[i].email+'</td> ' +
                                '<td>'+adminData[i].phone+'</td>'+
                                '<td>'+adminData[i].last_login_ip+'</td> '+
                                '<td>'+adminData[i].last_login_time+'</td> '+
                                '<td>'+adminData[i].createtime+'</td> '+
                                '<td>'+adminData[i].islocked+'</td> '+
                                '<td><a class="layui-btn layui-btn-mini admin_edit" data-id="'+adminData[i].adminid+'"><i class="iconfont icon-edit"></i> 编辑</a><a class="layui-btn layui-btn-danger layui-btn-mini admin_del" data-id="'+adminData[i].adminid+'"><i class="layui-icon"></i> 删除</a></td> '+
                                '</tr>';
                        }
                    }else{
                        html+='<tr><td colspan="7">暂无数据</td></tr>';
                    }
                    $('.admin_content').html(html);
                    form.render('checkbox');
                    // 分页
                    if(data.data.current_page==1){
                        laypage({
                            cont: 'page'
                            ,pages: data.data.last_page//总页数
                            ,groups: 5, //连续显示分页数
                            jump: function(obj, first){
                                //得到了当前页，用于向服务端请求对应数据
                                var curr = obj.curr;
                                $("#curr").val(curr);
                                if(!first){
                                    loadList()
                                }
                            },
                        });
                    }
                }else {
                    layer.msg("数据出错，请联系程序员！");
                }
            }
        })
    }
    loadList()

})
