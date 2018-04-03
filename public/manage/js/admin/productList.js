layui.config({
	base : "js/"
}).use(['form','layer','jquery','laypage'],function(){
	var form = layui.form(),
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		$ = layui.jquery;
    var laypage = layui.laypage
    //查询
    $(".search_btn").click(function() {
        if ($(".search_input").val() != '') {
            loadList()
        }else {
            layer.msg("请输入需要查询的内容");
        }
    })
    //重置查询
    $(".reload_btn").click(function() {
        $(".search_input").val('')
            loadList()
    })

	//添加商品
	$(".productAdd_btn").click(function(){
		var index = layui.layer.open({
			title : "添加商品",
			type : 2,
            content : [productAdd_URL,'on'],
			success : function(layero, index){
				layui.layer.tips('点击此处返回商品列表', '.layui-layer-setwin .layui-layer-close', {
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

    //操作
    $("body").on("click",".product_edit",function(){  //编辑
        var id=$(this).data('id');
        var index = layui.layer.open({
            title : "编辑商品",
            type : 2,
            content : [productEdit_URL+'?productid='+id,'on'],
            success : function(layero, index){
                layui.layer.tips('点击此处返回文章列表', '.layui-layer-setwin .layui-layer-close', {
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

    $("body").on("click",".product_del",function(){  //删除
        var _this = $(this);
        layer.confirm('确定删除此信息？',{icon:3, title:'提示信息'},function(index){
            $.ajax({
                url : productdel_URL,
                type : "post",
                data:{productid:_this.data('id')},
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

	//批量删除
	$(".batchDel").click(function(){
		var $checkbox = $('.product_list tbody input[type="checkbox"][name="checked"]');
		var $checked = $('.product_list tbody input[type="checkbox"][name="checked"]:checked');
		if($checkbox.is(":checked")){
			layer.confirm('确定删除选中的信息？',{icon:3, title:'提示信息'},function(index){
	            var data='';
                for(var j=0;j<$checked.length;j++){
                    data+=$($checked[j]).val()+','
                }
                data=data.substr(0, data.length - 1);
                $.ajax({
                    url : productdel_URL,
                    type : "post",
                    data:{productid:data},
                    dataType : "json",
                    success : function(data){
                        layer.close(index);
                        layer.msg(data.message);
                        $("#curr").val(1)
                        loadList()
                    }
                })

	        })
		}else{
			layer.msg("请选择需要删除的文章");
		}
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
            url : productList_URL,
            type : "post",
            data:{keyword:keyword,curr:curr},
            dataType : "json",
            success : function(data){
                if(data.code==200){
                    $("#curr").val(data.data.current_page);
                    var productData =data.data.data;
                    var html='';
                    if(productData.length>0){
                        for(var i=0;i<productData.length;i++){
                            html+='<tr>' +
                                '<td><input type="checkbox" name="checked" value="'+productData[i].productid+'" lay-skin="primary" lay-filter="choose"><div class="layui-unselect layui-form-checkbox" lay-skin="primary"><i class="layui-icon"></i></div></td>'+
                                ' <td align="left">'+productData[i].prodname+'</td>'+
                                ' <td>'+productData[i].catname+'</td>'+
                                '<td>'+productData[i].sort+'</td> ' +
                                '<td>'+productData[i].isshow+'</td> '+
                                '<td>'+productData[i].createtime+'</td> '+
                                '<td><a class="layui-btn layui-btn-mini product_edit" data-id="'+productData[i].productid+'"><i class="iconfont icon-edit"></i> 编辑</a><a class="layui-btn layui-btn-danger layui-btn-mini product_del" data-id="'+productData[i].productid+'"><i class="layui-icon"></i> 删除</a></td> '+
                                '</tr>';
                        }
                    }else{
                        html+='<tr><td colspan="7">暂无数据</td></tr>';
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
