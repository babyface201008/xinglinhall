
var selhtml=null;
var suffix='';
layui.config({
	base : "js/"
}).use(['form','layer','jquery','laydate','tree'],function(){

	var form = layui.form(),
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		laydate = layui.laydate;
		$ = layui.jquery;

 	form.on("submit(addcategory)",function(data){
            // 提交请求
            $.ajax({
                type : 'post',
                url : categorySaveURL,
                data : data.field,
                dataType:"json",
                success: function(res){
                    if(res.statuscode==200){
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

    // 初始化父级分类

    $.get(getCategoryListURL, function(data){
        var val=$('.pid').data('id')
        var firsthtml=$('.pid').html()
        var  data=eval(data);
        selhtml=firsthtml;
        treeListsel(data,val)
        $('.pid').html(selhtml)
        form.render('select');
    });

    // 组装树
    function treeListsel(data,selid) {
        for(var i=0;i<data.length;i++){
            if(data[i].pid==0){
                suffix="";
            }
            selhtml+='<option value="'+data[i].catid+'" ';
            if(selid==data[i].catid){
                selhtml+='selected';
            }
            selhtml+=' ><b>'+suffix+data[i].catname+'</b></option>';
            // suffix+="|--";
            if(!isEmptyObject(data[i].children)){
                var child=data[i].children;
                treeListsel(child,selid)
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
