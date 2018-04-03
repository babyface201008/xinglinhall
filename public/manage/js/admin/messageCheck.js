layui.config({
	base : "js/"
}).use(['form','layer','jquery','layedit','laydate'],function(){
	var form = layui.form(),
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		laypage = layui.laypage,
		layedit = layui.layedit,
		laydate = layui.laydate,
		$ = layui.jquery;


    form.on("submit(editNews)",function(data){

        data.field.status = data.field.status=="on" ? "1" : "0";

        //弹出loading
        // var index = top.layer.msg('数据提交中，请稍候',{icon: 16,time:false,shade:0.8});

        $.ajax({
            type : "post",
            url : 'save',
            dataType : "json",
            data : data.field,
            success : function(data){
                if(data.statuscode == 200){
                    // layer.close(index);
                    layer.msg(data.message);
                    setTimeout(function () {
                        parent.location.reload()
                    },2000)
                }else{
                    layer.msg(data.message);
                }
            },
            error : function(){
                layer.msg('系统出错，请联系管理员!');
            }
        });

        return false;
    });

})
