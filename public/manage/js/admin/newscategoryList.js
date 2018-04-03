layui.config({
	base : "js/"
}).use(['form','layer','jquery','laypage','element'],function(){
	var form = layui.form(),
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		laypage = layui.laypage,
     element = layui.element(),
		$ = layui.jquery;

	//加载页面数据
	var newsData = '';
    var dataHtml = '';
	$.get("newscategoryList", function(data){
        newscategoryList(data);//执行加载数据的方法
	})

	//查询
	$(".search_btn").click(function(){
		var newArray = [];
		if($(".search_input").val() != ''){
			var index = layer.msg('查询中，请稍候',{icon: 16,time:false,shade:0.8});

            	$.ajax({
					url : "newscategoryList",
					type : "post",
					dataType : "json",
					data:{key:  $(".search_input").val()},
					success : function(data){
                        if(data.code == 200){
                            newscategoryList(data);
                        }else{
                            layer.msg(data.message);
                        }
					}, error : function(){
                        layer.msg('系统出错，请联系管理员!');
                    }
				});
                layer.close(index);
		}else{
			layer.msg("请输入需要查询的内容");
		}
	})

	//添加分类
	$(".newscategoryAdd_btn").click(function(){
		var index = layui.layer.open({
			title : "添加分类",
			type : 2,
			content : "newscategoryAdd",
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

	//全选
	form.on('checkbox(allChoose)', function(data){
		var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="show"])');
		child.each(function(index, item){
			item.checked = data.elem.checked;
		});
		form.render('checkbox');
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
	})

	//是否展示
	form.on('switch(isShow)', function(data){
		var index = layer.msg('修改中，请稍候',{icon: 16,time:false,shade:0.8});
        setTimeout(function(){
            layer.close(index);
			layer.msg("展示状态修改成功！");
        },2000);
	})
 
	//操作
	$("body").on("click",".newscategory_edit",function(){  //编辑
        var index = layui.layer.open({
            title : "编辑文章",
            type : 2,
            content : "newscategoryEdit.html?id="+$(this).attr('data-id'),
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


	$("body").on("click",".newscategory_del",function(){  //删除
		var _this = $(this);
        layer.confirm('确定删除此信息？',{icon:3, title:'提示信息'},function(index){
            //_this.parents("tr").remove();
            $.ajax({
                url : 'delNewscategory',
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

	function newscategoryList(that){
		//渲染数据
		function renderDate(data){
			if(data.length != 0){
				for(var i=0;i<data.length;i++){

					if(data[i].pid == 0){
                    dataHtml += '<tr class="text-c treegrid-'+data[i].catid+'">';
					}else {
                        dataHtml += '<tr  class="text-c treegrid-' + data[i].catid + ' treegrid-parent-' + data[i].pid + '" >';
                    }

                    dataHtml += '<td align="left">'+data[i].catname+'</td>'
					+'<td>'+data[i].sort+'</td>'
			    	//+'<td>'+data[i].pid+'</td>';
			    	dataHtml += '<td>'+data[i].show+'</td>'
			    	+'<td>'
					+  '<a class="layui-btn layui-btn-mini newscategory_edit" data-id="'+data[i].catid+'"><i class="iconfont icon-edit"></i> 编辑</a>'
					+  '<a class="layui-btn layui-btn-danger layui-btn-mini newscategory_del" data-id="'+data[i].catid+'"><i class="layui-icon">&#xe640;</i> 删除</a>'
			        +'</td>'
			    	+'</tr>';
                    if(!isEmptyObject(data[i].children)){
                        renderDate(data[i].children);
					}

				}
			}else{
				dataHtml = '<tr><td colspan="8">暂无数据</td></tr>';
			}
		    return dataHtml;
		}

		if(that){
			newsData = that;
		}
		$(".newscategory_content").html(renderDate(newsData));
		$('.newscategory_list thead input[type="checkbox"]').prop("checked",false);
		form.render();

        $("#newscategory-table").treegrid({"initialState": 'collapsed',});//初始化
	}
// 判断是否为空
    function isEmptyObject(e) {
        var t;
        for (t in e)
            return !1;
        return !0
    }

});
