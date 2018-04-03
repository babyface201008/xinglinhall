layui.config({
    base : "js/"
}).use(['form','layer','jquery'],function(){
    var form = layui.form(),
        layer = parent.layer === undefined ? layui.layer : parent.layer,
        laypage = layui.laypage,
        $ = layui.jquery;

    //加载页面数据
    var newsData = '';
    newsList();


    //操作
    $("body").on("click",".message_edit",function(){  //编辑
        var index = layui.layer.open({
            title : "编辑关于我们",
            type : 2,
            content : "messageEdit.html?id="+$(this).attr('data-id'),
            success : function(layero, index){
                layui.layer.tips('点击此处返回关于我们列表', '.layui-layer-setwin .layui-layer-close', {
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

    function newsList(){
        //渲染数据
        var dataHtml = '';
        $.ajax({
            url : 'getList',
            type : "post",
            dataType : "json",
            success : function(data){
                if(data.code == 200){
                    var messagedata=data.data;
                    if(data.length != 0){
                        for(var i=0;i<messagedata.length;i++){
                            dataHtml += '<tr>'
                                +'<td align="left">'+messagedata[i].messageid+'</td>'
                                +'<td align="left">'+messagedata[i].message_name+'</td>'
                                +'<td align="left">'+messagedata[i].message_phone+'</td>'
                                +'<td align="left">'+messagedata[i].time+'</td>'
                                +'<td align="left">'+messagedata[i].status+'</td>'
                                +'<td>'
                                +'<a class="layui-btn layui-btn-mini message_edit"data-id="'+messagedata[i].messageid+'"><i class="iconfont icon-edit"></i> 编辑</a>'
                                +'<a class="layui-btn layui-btn-danger layui-btn-mini message_del" data-id="'+messagedata[i].messageid+'"><i class="layui-icon">&#xe640;</i> 删除</a>'
                                +'</td>'
                                +'</tr>';
                        }
                    }else{
                        dataHtml = '<tr><td colspan="8">暂无数据</td></tr>';
                    }
                    $(".message_content").html(dataHtml);
                    $('.message_list thead input[type="checkbox"]').prop("checked",false);
                    form.render();
                }else{
                    layer.msg(data.message);
                }
            },
            error : function(){
                layer.msg('系统出错，请联系管理员!');
            }
        });
    }


})
