var selhtml='';
layui.config({
	base : "js/"
}).use(['form','layer','jquery','layedit','laydate','upload'],function(){
	var form = layui.form(),
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		laypage = layui.laypage,
		layedit = layui.layedit,
		laydate = layui.laydate;
		$ = layui.jquery;
    form.verify({
        ad_src : function(value, item){
            if(value ==''){
                return "请上传页面广告图";
            }
        },
    });
 	form.on("submit(addnav)",function(data){
        // var index = layer.msg('提交中，请稍候',{icon: 16,time:false,shade:0.8});
            // 提交请求
            $.ajax({
                type : 'post',
                url : navSave_URL,
                data : data.field,
                dataType:"json",
                success: function(res){
                    if(res.statuscode==200){
                        // layer.close(index);
                        layer.msg(res.message);
                        setTimeout(function () {
                            parent.location.reload()
                        },2000)

                    }else{
                        layer.msg(res.message);
                    }


                },
                error : function(){
                    layer.msg('系统出错，请联系管理员!');
                }
            });
        return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
 	})
    var adsrc=$('.ad_src').val();
    layui.upload({
        url: UPLOAD_URL
        ,success: function(res){
            var checkname=$('.checkname').val();
            var navname=$('.navname').val();
            if(checkname=='首页' || navname=='首页'){
                var html ='<div class="float_img" data-url="'+res.data+'" id="'+res.data.slice(0,-4)+'"  onclick="delimg(this)"><img src="'+LOGO_URL+res.data+'" ><i class="layui-icon" style="font-size: 14px; color:red;">&#x1006;</i></div>';
                $('#nav_ad').append(html)
                adsrc+=','+res.data
                $('.ad_src').val(adsrc)
            }else{
                var html ='<img src="'+LOGO_URL+res.data+'" >';
                $('#nav_ad').html(html)
                $('.ad_src').val(res.data)
            }



        }
    });

    // 初始化父级分类

    $.get(getNavListJson, function(data){
        var val=$('.parentid').data('id')
        var firsthtml=$('.parentid').html()
        var  data=eval(data);
        selhtml=firsthtml;
        treeListsel(data,'',val)
        $('.parentid').html(selhtml)
        form.render('select');
    });

    // 组装树
    function treeListsel(data,suffix,selid) {
        for(var i=0;i<data.length;i++){
            selhtml+='<option value="'+data[i].navid+'" ';
            if(selid==data[i].navid){
                selhtml+='selected';
            }
            selhtml+=' >'+suffix+data[i].navname+'</option>';
            if(!isEmptyObject(data[i].children)){
                var child=data[i].children;
                treeListsel(child,"   |-- ",selid)
            }
        }
    }
    function isEmptyObject(e) {
        var t;
        for (t in e)
            return !1;
        return !0
    }


})
// 删除图片
function delimg(obj) {
    var url=$(obj).data('url');
    layer.confirm('确定删除此图片？',{icon:3, title:'提示信息'},function(index){
        layer.msg('删除成功！');
        var adsrc=$('.ad_src').val();
        adsrc=adsrc.replace(','+url,"");
        $('.ad_src').val(adsrc);
        $('#'+url.slice(0,-4)).remove()
        return false;

    });
}
