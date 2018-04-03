var html=null;


layui.config({
	base : "js/"
}).use(['form','layer','jquery','laypage','element'],function(){
	var form = layui.form(),
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		$ = layui.jquery;
    var laypage = layui.laypage
    var element = layui.element();
    element.on('collapse(test)', function(data){
        layer.msg('展开状态：'+ data.show);
    });
    //查询
    $(".search_btn").click(function() {
        if ($(".search_input").val() != '') {
            loadList()
        }else {
            layer.msg("请输入需要查询的内容");
        }
    })
    //重新加载数据
    $(".reload_btn").click(function() {
        $(".search_input").val('')
            loadList()
    })

	//添加友情链接
	$(".categoryAdd_btn").click(function(){
		var index = layui.layer.open({
			title : "添加分类",
			type : 2,
            content : [categoryAdd_URL,'on'],
			success : function(layero, index){
				layui.layer.tips('点击此处返回商品分类列表', '.layui-layer-setwin .layui-layer-close', {
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
    $("body").on("click",".category_edit",function(){  //编辑
        var id=$(this).data('id');
        var index = layui.layer.open({
            title : "编辑分类",
            type : 2,
            content : [categoryEdit_URL+'?catid='+id,'on'],
            success : function(layero, index){
                layui.layer.tips('点击此处返回商品分类列表', '.layui-layer-setwin .layui-layer-close', {
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

    $("body").on("click",".category_del",function(){  //删除
        var _this = $(this);
        layer.confirm('确定删除此信息？',{icon:3, title:'提示信息'},function(index){
            $.ajax({
                url : categorydel_URL,
                type : "post",
                data:{catid:_this.data('id')},
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
            url : categoryList_URL,
            type : "post",
            data:{keyword:keyword,curr:curr},
            dataType : "json",
            success : function(data){

                var categoryData =eval(data)
                html='';
                if(categoryData.code==200){
                    if(categoryData.data.length>0){
                        treeList(categoryData.data)
                    }else{
                        html+='<tr><td colspan="7">暂无数据</td></tr>';
                    }
                    $('.category_content').html(html);
                    $("#cat-table").treegrid({"initialState": 'collapsed',});
                }else {
                    layer.msg('数据出错请联系程序员！');
                }

            }
        })
    }
    loadList()
    // 组装树
    function treeList(categoryData) {
        for (var i = 0; i < categoryData.length; i++) {
            if(categoryData[i].pid==0){
                html+='<tr class="text-c treegrid-'+categoryData[i].catid+'">';
            }else {
                html+='<tr class="text-c treegrid-'+categoryData[i].catid+' treegrid-parent-'+categoryData[i].pid +'">';
            }
            html += ' <td align="left">'+ categoryData[i].catname + '</td>' +
                ' <td>'+ categoryData[i].pname+ '</a></td>' +
                '<td>' + categoryData[i].sort + '</td> ' +
                '<td>' + categoryData[i].isshow + '</td> ' +
                '<td>' + categoryData[i].createtime + '</td> ' +
                '<td><a class="layui-btn layui-btn-mini category_edit" data-id="' + categoryData[i].catid + '"><i class="iconfont icon-edit"></i> 编辑</a><a class="layui-btn layui-btn-danger layui-btn-mini category_del" data-id="' + categoryData[i].catid + '"><i class="layui-icon"></i> 删除</a></td> ' +
                '</tr>';
            if(!isEmptyObject(categoryData[i].children)){
                var catData=categoryData[i].children
                treeList(catData)
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
