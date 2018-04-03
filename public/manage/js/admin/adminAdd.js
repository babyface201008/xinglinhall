layui.config({
	base : "js/"
}).use(['form','layer','jquery','layedit'],function(){
	var form = layui.form(),
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		$ = layui.jquery;

	//添加验证规则
	form.verify({
		password : function(value, item){
			if(value.length < 6){
				return "密码长度不能小于6位";
			}
		}
	});
	form.on("submit(addAdmin)",function(data){
		// var index = layer.msg('提交中，请稍候',{icon: 16,time:false,shade:0.8});
		// 提交请求;
		$.ajax({
			type : 'post',
			url : saveAdminUrl,
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


});