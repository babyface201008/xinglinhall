layui.use('form', function(){
    var form = layui.form();

    //添加验证规则
    form.verify({
        newPwd : function(value, item){
            if(value.length < 6){
                return "密码长度不能小于6位";
            }
        },
        confirmPwd : function(value, item){
            if(!new RegExp($("#oldPwd").val()).test(value)){
                return "两次输入密码不一致，请重新输入！";
            }
        }
    });

    //修改密码
    form.on("submit(changePwd)",function(data){
        var index = layer.msg('提交中，请稍候',{icon: 16,time:false,shade:0.8});
        setTimeout(function(){
            // 提交请求
            $.ajax({
                type : 'post',
                url : editPassword,
                data : data.field,
                dataType:"json",
                success: function(res){
                    if(res.statuscode==200){
                        layer.close(index);
                        layer.msg(res.message);
                        $(".pwd").val('');
                    }else{
                        layer.msg(res.message);
                    }


                },
                error : function(){
                    layer.msg('系统出错，请联系管理员!');
                }
            });


        },500);
        return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
    });

});

