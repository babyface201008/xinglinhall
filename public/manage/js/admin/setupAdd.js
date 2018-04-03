layui.config({
	base : "js/"
}).use(['form','layer','jquery','laydate','element'],function(){
	var form = layui.form(),
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		layedit = layui.layedit,
        element = layui.element();
		$ = layui.jquery;

    var layid = location.hash.replace(/^#setup=/, '');
    element.tabChange('setup', layid);
    element.on('tab(setup)', function(elem){
        location.hash = 'setup='+ $(this).attr('lay-id');
    });
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

    form.on("submit(setup)",function(data){
        // var index = layer.msg('提交中，请稍候',{icon: 16,time:false,shade:0.8});
        console.log(data.field);
            // 提交请求
            $.ajax({
                type : 'post',
                url : setupSave_URL,
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
})

