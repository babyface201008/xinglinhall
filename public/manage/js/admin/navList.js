var navtree=null
var html='';
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

	//添加菜单
	$(".navAdd_btn").click(function(){
		var index = layui.layer.open({
			title : "添加菜单",
			type : 2,
            content : [navAdd_URL,'on'],
			success : function(layero, index){
				layui.layer.tips('点击此处返回菜单列表', '.layui-layer-setwin .layui-layer-close', {
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
    $("body").on("click",".nav_edit",function(){  //编辑
        var id=$(this).data('id');
        var index = layui.layer.open({
            title : "编辑菜单",
            type : 2,
            content : [navEdit_URL+'?navid='+id,'on'],
            success : function(layero, index){
                layui.layer.tips('点击此处返回菜单列表', '.layui-layer-setwin .layui-layer-close', {
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

    $("body").on("click",".nav_del",function(){  //删除
        var _this = $(this);
        layer.confirm('确定删除此信息？',{icon:3, title:'提示信息'},function(index){
            $.ajax({
                url : navdel_URL,
                type : "post",
                data:{navid:_this.data('id')},
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
		var $checkbox = $('.nav_list tbody input[type="checkbox"][name="checked"]');
		var $checked = $('.nav_list tbody input[type="checkbox"][name="checked"]:checked');
		if($checkbox.is(":checked")){
			layer.confirm('确定删除选中的信息？',{icon:3, title:'提示信息'},function(index){
	            var data='';
                for(var j=0;j<$checked.length;j++){
                    data+=$($checked[j]).val()+','
                }
                data=data.substr(0, data.length - 1);
                $.ajax({
                    url : navdel_URL,
                    type : "post",
                    data:{navid:data},
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
			layer.msg("请选择需要删除的菜单");
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
            url : navList_URL,
            type : "post",
            data:{keyword:keyword,curr:curr},
            dataType : "json",
            success : function(data){
                if(data.code==200){
                    var navData =data.data;
                    html='';
                    if(navData.length>0){
                        treeList(navData)
                    }else{
                        html+='<tr><td colspan="7">暂无数据</td></tr>';
                    }
                    $('.nav_content').html(html);
                    // 初始化树
                    $("#nav-table").treegrid({"initialState": 'collapsed',});

                }else {
                    layer.msg("数据出错，请联系程序员！");
                }
            }
        })
    }
    loadList()

    function treeList(navData) {
        for(var i=0;i<navData.length;i++){
            if(navData[i].parentid==0){
                html+='<tr class="text-c treegrid-'+navData[i].navid+'">';
            }else {
                html+='<tr class="text-c treegrid-'+navData[i].navid+' treegrid-parent-'+navData[i].parentid +'">';
            }
            html+=
                ' <td align="left">'+navData[i].navname+'</td>'+
                ' <td align="left">'+navData[i].pname+'</td>'+
                '<td>'+navData[i].url+'</td> ' +
                '<td><img src="'+img_URL+'150/'+navData[i].ad_src+'" style="height:30px"></td>'+
                ' <td><a style="color:#1E9FFF;" target="_blank" href="'+navData[i].ad_url+'">'+navData[i].ad_url+'</a></td>'+
                '<td>'+navData[i].sort+'</td> ' +
                '<td>'+navData[i].isshow+'</td> '+
                '<td><a class="layui-btn layui-btn-mini nav_edit" data-id="'+navData[i].navid+'"><i class="iconfont icon-edit"></i> 编辑</a><a class="layui-btn layui-btn-danger layui-btn-mini nav_del" data-id="'+navData[i].navid+'"><i class="layui-icon"></i> 删除</a></td> '+
                '</tr>';
            if(!isEmptyObject(navData[i].children)){
                var chilData=navData[i].children
                treeList(chilData)
            }
        }

    }
// 判断是否为空
    function isEmptyObject(e) {
        var t;
        for (t in e)
            return !1;
        return !0
    }

})




