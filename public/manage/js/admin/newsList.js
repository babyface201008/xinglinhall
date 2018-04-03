layui.config({
	base : "js/"
}).use(['form','layer','jquery','laypage'],function(){
	var form = layui.form(),
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		laypage = layui.laypage,
		$ = layui.jquery;

	//加载页面数据
	var newsData = '';


    //重置查询
    $(".reload_btn").click(function() {
        $(".search_input").val('')
        newsList()
    })

	//查询
	$(".search_btn").click(function(){
		var newArray = [];
		if($(".search_input").val() != ''){
            newsList();
		}else{
			layer.msg("请输入需要查询的内容");
		}
	});

	//添加文章
	$(".newsAdd_btn").click(function(){
		var index = layui.layer.open({
			title : "添加文章",
			type : 2,
			content : "newsAdd.html",
			success : function(layero, index){
				layui.layer.tips('点击此处返回文章列表', '.layui-layer-setwin .layui-layer-close', {
					tips: 3
				});
			}
		});
		//改变窗口大小时，重置弹窗的高度，防止超出可视区域（如F12调出debug的操作）
		$(window).resize(function(){
			layui.layer.full(index);
		});
		layui.layer.full(index);
	});

	//推荐文章
	$(".recommend").click(function(){
		var $checkbox = $(".news_list").find('tbody input[type="checkbox"]:not([name="show"])');
		if($checkbox.is(":checked")){
			var index = layer.msg('推荐中，请稍候',{icon: 16,time:false,shade:0.8});
            setTimeout(function(){
                layer.close(index);
				layer.msg("推荐成功");
            },2000);
		}else{
			layer.msg("请选择需要推荐的文章");
		}
	});


    //全选
    form.on('checkbox(allChoose)', function(data){
        var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="isshow"]):not([name="istuijian"])');
        child.each(function(index, item){
            item.checked = data.elem.checked;
        });
        form.render('checkbox');
    });


	//批量删除
	$(".batchDel").click(function(){
		var checkbox = $('.news_list tbody input[type="checkbox"][name="checked"]');
		var checked = $('.news_list tbody input[type="checkbox"][name="checked"]:checked');
		var data='';
		if(checkbox.is(":checked")){
			layer.confirm('确定删除选中的信息？',{icon:3, title:'提示信息'},function(index){
				var index = layer.msg('删除中，请稍候',{icon: 16,time:false,shade:0.8});

	            	//删除数据
                for(var j=0;j<checked.length;j++){
                    data+=$(checked[j]).attr('newsid')+','
                }
                data=data.substr(0, data.length - 1);

                $.ajax({
                    url : 'delNewsList',
                    type : "post",
                    dataType : "json",
                    data : { ids: data},
                    success : function(data){
                        if(data.statuscode == 200){
                            layer.close(index);
                            layer.msg(data.message);
                            newsList();
                        }else{
                            layer.msg(data.message);
                        }
                    },
                    error : function(){
                        layer.msg('系统出错，请联系管理员!');
                    }
                });

	        })
		}else{
			layer.msg("请选择需要删除的文章");
		}
	});


    //删除
    $("body").on("click",".news_del",function(){
        var _this = $(this);
        layer.confirm('确定删除此信息？',{icon:3, title:'提示信息'},function(index){
            //_this.parents("tr").remove();
            $.ajax({
                url : delNewsJSON,
                type : "post",
                dataType : "json",
                data : { id: _this.attr("data-id")},
                success : function(data){
                    if(data.statuscode == 200){
                        layer.close(index);
                        layer.msg(data.message);
                        window.location.reload();

                    }else{
                        layer.msg(data.message);
                    }
                },
                error : function(){
                    layer.msg('系统出错，请联系管理员!');
                }
            });
        });
    });


	//通过判断文章是否全部选中来确定全选按钮是否选中
	form.on("checkbox(choose)",function(data){
		var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="show"])');
		var childChecked = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="show"]):checked')
		if(childChecked.length == child.length){
			$(data.elem).parents('table').find('thead input#allChoose').get(0).checked = true;
		}else{
			$(data.elem).parents('table').find('thead input#allChoose').get(0).checked = false;
		}
		form.render('checkbox');
	});


/*	//是否展示
	form.on('switch(isshow)', function(data){
        changeNews('isshow',$(this).attr('newsid'),$(this).attr('ischeck'),$(this));
	})


    //是否推荐
    form.on('switch(istuijian)', function(data){
    	        changeNews('istuijian',$(this).attr('newsid'),$(this).attr('ischeck'),$(this));
    });*/


	//操作
	$("body").on("click",".news_edit",function(){  //编辑
        var index = layui.layer.open({
            title : "编辑文章",
            type : 2,
            content : "newsEdit.html?id="+$(this).attr('data-id'),
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

	});

	function newsList(){
		//渲染数据
			var dataHtml = '';
            $.ajax({
                url : 'getNewsList',
                type : "post",
                dataType : "json",
                data : { keyword:  $(".search_input").val(),curr:$("#curr").val()},
                success : function(data){
                    if(data.code == 200){
                        $("#curr").val(data.data.current_page);
                        var newsdata=data.data;
                        if(data.length != 0){
                            for(var i=0;i<newsdata.length;i++){
                                dataHtml += '<tr>'
                                    +'<td><input type="checkbox" name="checked" lay-skin="primary" newsid="'+newsdata[i].newsid+'" lay-filter="choose"></td>'
                                    +'<td align="left">'+newsdata[i].newsid+'</td>'
                                    +'<td align="left">'+newsdata[i].title+'</td>'
                                    +'<td>'+newsdata[i].author+'</td>'
                                   /* +'<td align="left">'+newsdata[i].pid+'</td>';*/
                               /* dataHtml +='<td><input type="checkbox" name="istuijian" lay-skin="switch" lay-text="是|否" lay-filter="istuijian" newsid="'+newsdata[i].newsid+'" ischeck="'+newsdata[i].istuijian+'" '+newsdata[i].tuijian+'></td>'*/              dataHtml +='<td>'+newsdata[i].show+'</td>'
                                    +'<td>'+newsdata[i].tuijian+'</td>'
                                    + '<td>'+newsdata[i].createtime+'</td>'
                                    +'<td>'
                                    +  '<a class="layui-btn layui-btn-mini news_edit"data-id="'+newsdata[i].newsid+'"><i class="iconfont icon-edit"></i> 编辑</a>'
                                    +  '<a class="layui-btn layui-btn-danger layui-btn-mini news_del" data-id="'+newsdata[i].newsid+'"><i class="layui-icon">&#xe640;</i> 删除</a>'
                                    +'</td>'
                                    +'</tr>';
                            }
                        }else{
                            dataHtml = '<tr><td colspan="8">暂无数据</td></tr>';
                        }
                        $(".news_content").html(dataHtml);
                        $('.news_list thead input[type="checkbox"]').prop("checked",false);
                        form.render();
                        // 分页
                        if(data.current_page==1){
                            laypage({
                                cont: 'page'
                                ,pages: data.last_page//总页数
                                ,groups: 5, //连续显示分页数
                                jump: function(obj, first){
                                    //得到了当前页，用于向服务端请求对应数据
                                    var curr = obj.curr;
                                    $("#curr").val(curr);
                                    if(!first){
                                        newsList()
                                    }
                                },
                            });
                        }
                    }else{
                        layer.msg(data.message);
                    }
                },
                error : function(){
                    layer.msg('系统出错，请联系管理员!');
                }
            });
	}

    function changeNews(type,id,ischeck,_this){
        var index = layer.msg('修改中，请稍候',{icon: 16,time:false,shade:0.8});
        if(!type) layer.msg("数据不完整，刷新重试！");
        if(!id) layer.msg("数据不完整，刷新重试！");
        if(ischeck == '1'){
            var check=2;
        }else{
            var check=1;
        }
        $.ajax({
            url : changeNewsJSON,
            type : "post",
            dataType : "json",
            data : { type : type,id:id,ischeck:check},
            success : function(data){
            	if(data.statuscode == 200){
                    layer.close(index);
                    _this.attr('ischeck',''+data.did.checked+'');
				}else{

				}
                layer.msg(data.message);
            },
            error : function(){
                layer.msg('系统出错，请联系管理员!');
            }
        });
	}
    newsList();
})
