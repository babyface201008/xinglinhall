layui.config({
	base : "js/"
}).use(['form','layer','jquery','layedit','laydate','upload'],function(){
	var form = layui.form(),
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		laypage = layui.laypage,
		layedit = layui.layedit,
		laydate = layui.laydate,
		$ = layui.jquery;


    form.verify({
        image : function(value, item){
            if(value ==''){
                return "请上传新闻封面";
            }
        },
    });



 	var addSolutionArray = [],addNews;
 	form.on("submit(addSolution)",function(data){
 		//是否添加过信息

        data.field.type='add';//编辑属性
	 	//显示、审核状态
        data.field.isshow = data.field.show=="on" ? "1" : "2",

 		//弹出loading
 		// var index = top.layer.msg('数据提交中，请稍候',{icon: 16,time:false,shade:0.8});

        $.ajax({
            type : "post",
            url : saveSolutionJSON,
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
 	})

    form.on("submit(editSolution)",function(data){

        data.field.type='edit';//编辑属性
        //显示、审核状态
        data.field.isshow = data.field.show=="on" ? "1" : "2",

        //弹出loading
        // var index = top.layer.msg('数据提交中，请稍候',{icon: 16,time:false,shade:0.8});

        $.ajax({
            type : "post",
            url : saveSolutionJSON,
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

    $.get(getTypeListJsonURL, function(data){
        var val=$('.catid').data('id')
        var firsthtml=$('.catid').html()
        var  data=eval(data);
        selhtml=firsthtml;
        treeListsel(data,'',val)
        $('.catid').html(selhtml)
        form.render('select');
    });
    // 组装树
    function treeListsel(data,suffix,selid) {
        for(var i=0;i<data.length;i++){
            selhtml+='<option value="'+data[i].catid+'" ';
            if(selid==data[i].catid){
                selhtml+='selected';
            }
            selhtml+=' >'+suffix+data[i].catname+'</option>';
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

    layui.upload({
        url: 'uploadImg.html',
        success: function(res){
            var html ='<img src="'+imageURL+res.data+'" >';
            $('#image').html(html);
            $('#imageURL').val(res.data);
        }
    });
})
