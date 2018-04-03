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
        logo : function(value, item){
            if(value ==''){
                return "请上传官方二维码";
            }
        },
    });
    form.on("submit(addsetting)",function(data){
        // var index = layer.msg('提交中，请稍候',{icon: 16,time:false,shade:0.8});
        // 提交请求
        $.ajax({
            type : 'post',
            url : basicSave_URL,
            data : data.field,
            dataType:"json",
            success: function(res){
                if(res.statuscode==200){
                    // layer.close(index);
                    layer.msg(res.message);
                    setTimeout(function () {
                        window.location.reload()
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
    layui.upload({
        url: UPLOAD_URL
        ,success: function(res){
            if(res.statuscode==200){
                var html ='<img src="'+LOGO_URL+res.message+'" >';
                $('#'+res.did).html(html)
                $('.'+res.did).val(res.message)
            }

        }
    });

})
