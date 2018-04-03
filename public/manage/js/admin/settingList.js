layui.config({
	base : "js/"
}).use(['form','layer','jquery','laypage'],function(){
	var form = layui.form(),
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		$ = layui.jquery;
    var laypage = layui.laypage
    //查询
    $(".search_btn").click(function() {
        if ($(".search_input").val() != '' ||  $('.code').val() !='') {
            loadList()
        }else {
            layer.msg("请输入需要查询的内容");
        }
    })
    //重置查询
    $(".reload_btn").click(function() {
        $(".search_input").val('')
        $(".code").val('')
            loadList()
    })

	//添加配置
	$(".settingAdd_btn").click(function(){
		var index = layui.layer.open({
			title : "添加配置",
			type : 2,
            content : [settingAdd_URL,'on'],
			success : function(layero, index){
				layui.layer.tips('点击此处返回配置列表', '.layui-layer-setwin .layui-layer-close', {
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
    $("body").on("click",".setting_edit",function(){  //编辑
        var id=$(this).data('id');
        var index = layui.layer.open({
            title : "编辑配置",
            type : 2,
            content : [settingEdit_URL+'?settingid='+id,'on'],
            success : function(layero, index){
                layui.layer.tips('点击此处返回配置列表', '.layui-layer-setwin .layui-layer-close', {
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

    $("body").on("click",".setting_del",function(){  //删除
        var _this = $(this);
        layer.confirm('确定删除此信息？',{icon:3, title:'提示信息'},function(index){
            $.ajax({
                url : settingdel_URL,
                type : "post",
                data:{settingid:_this.data('id')},
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
		var $checkbox = $('.setting_list tbody input[type="checkbox"][name="checked"]');
		var $checked = $('.setting_list tbody input[type="checkbox"][name="checked"]:checked');
		if($checkbox.is(":checked")){
			layer.confirm('确定删除选中的信息？',{icon:3, title:'提示信息'},function(index){
	            var data='';
                for(var j=0;j<$checked.length;j++){
                    data+=$($checked[j]).val()+','
                }
                data=data.substr(0, data.length - 1);
                $.ajax({
                    url : settingdel_URL,
                    type : "post",
                    data:{settingid:data},
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
        var code=$(".code").val();
        var curr=$("#curr").val();
        $.ajax({
            url : settingList_URL,
            type : "post",
            data:{keyword:keyword,curr:curr,code:code},
            dataType : "json",
            success : function(data){
                if(data.code==200){
                    $("#curr").val(data.data.current_page);
                    var settingData =data.data.data;
                    var html='';
                    if(settingData.length>0){
                        for(var i=0;i<settingData.length;i++){
                            html+='<tr>' +
                                '<td><input type="checkbox" name="checked" value="'+settingData[i].settingid+'" lay-skin="primary" lay-filter="choose"><div class="layui-unselect layui-form-checkbox" lay-skin="primary"><i class="layui-icon"></i></div></td>'+
                                ' <td align="left">'+settingData[i].code+'</td>'+
                                ' <td>'+settingData[i].desc+'</td>'+
                                '<td>'+settingData[i].variable+'</td> ' +
                                '<td>'+settingData[i].value+'</td> '+
                                '<td><a class="layui-btn layui-btn-mini setting_edit" data-id="'+settingData[i].settingid+'"><i class="iconfont icon-edit"></i> 编辑</a><a class="layui-btn layui-btn-danger layui-btn-mini setting_del" data-id="'+settingData[i].settingid+'"><i class="layui-icon"></i> 删除</a></td> '+
                                '</tr>';
                        }
                    }else{
                        html+='<tr><td colspan="7">暂无数据</td></tr>';
                    }
                    $('.setting_content').html(html);
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
