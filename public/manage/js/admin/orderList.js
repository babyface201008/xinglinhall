layui.config({
	base : "js/"
}).use(['form','layer','jquery','laypage'],function(){
	var form = layui.form(),
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		$ = layui.jquery;
    var laypage = layui.laypage;
    //查询
    $(".search_btn").click(function() {
        if ($(".search_input").val() != '') {
            loadList()
        }else {
            layer.msg("请输入需要查询的内容");
        }
    });
    //重置查询
    $(".reload_btn").click(function() {
        $(".search_input").val('')
            loadList()
    });

    //操作
    $("body").on("click",".order_detail",function(){  //编辑
        var id=$(this).data('id');
        var index = layui.layer.open({
            title : "查看订单明细",
            type : 2,
            content : [orderDetailURL+'?orderid='+id,'on'],
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

	//全选
	form.on('checkbox(allChoose)', function(data){
		var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="show"])');
		child.each(function(index, item){
			item.checked = data.elem.checked;
		});
		form.render('checkbox');
	});

	form.on("checkbox(choose)",function(data){
		var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="show"])');
		var childChecked = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="show"]):checked')
		data.elem.checked;
		if(childChecked.length == child.length){
			$(data.elem).parents('table').find('thead input#allChoose').get(0).checked = true;
		}else{
			$(data.elem).parents('table').find('thead input#allChoose').get(0).checked = false;
		}
		form.render('checkbox');
	})
    //加载数据
    function loadList() {
        var keyword=$(".search_input").val();
        var curr=$("#curr").val();
        $.ajax({
            url : orderListURL,
            type : "post",
            data:{keyword:keyword,curr:curr},
            dataType : "json",
            success : function(data){
                if(data.code==200){
                    $("#curr").val(data.data.current_page);
                    var orderData =data.data.data;
                    var html='';
                    if(orderData.length>0){
                        for(var i=0;i<orderData.length;i++){
                            html+='<tr>' +
                                '<td><input type="checkbox" name="checked" value="'+orderData[i].orderid+'" lay-skin="primary" lay-filter="choose"><div class="layui-unselect layui-form-checkbox" lay-skin="primary"><i class="layui-icon"></i></div></td>'+
                                ' <td align="left">'+orderData[i].orderno+'</td>'+
                                ' <td>'+orderData[i].totalamount+'</td>'+
                                '<td>'+orderData[i].name+'</td> '+
                                '<td>'+orderData[i].phone+'</td> '+
                                '<td>'+orderData[i].area+orderData[i].address+'</td> '+
                                '<td>'+orderData[i].payname+'</td> '+
                                '<td>'+orderData[i].createtime+'</td> '+
                                '<td>'+orderData[i].paytime+'</td> '+
                                '<td>'+orderData[i].statusname+'</td> '+
                                '<td><a class="layui-btn layui-btn-mini order_detail" data-id="'+orderData[i].orderid+'">  详情</a></td> '+
                                '</tr>';
                        }
                    }else{
                        html+='<tr><td colspan="11">暂无数据</td></tr>';
                    }
                    $('.product_content').html(html);
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
