var selhtml=null;
var suffix='';
layui.config({
	base : "js/"
}).use(['form','layer','jquery','layedit','laydate'],function(){
	var form = layui.form(),
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		laypage = layui.laypage,
		layedit = layui.layedit,
		laydate = layui.laydate,
		$ = layui.jquery;


	//创建一个编辑器
 	var editIndex = layedit.build('news_contant');
 	var addNewsArray = [],addNews;
 	form.on("submit(addNewscategory)",function(data){
 		//是否添加过信息
	 	if(window.sessionStorage.getItem("addNews")){
	 		addNewsArray = JSON.parse(window.sessionStorage.getItem("addNews"));
	 	}

        data.field.type='add';//编辑属性
	 	//显示、审核状态
        data.field.isshow = data.field.show=="on" ? "1" : "2",

 		//弹出loading
 		// var index = top.layer.msg('数据提交中，请稍候',{icon: 16,time:false,shade:0.8});

        $.ajax({
            type : "post",
            url : saveNewscategoryJSON,
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

    form.on("submit(editNewscategory)",function(data){

        data.field.contant=layedit.getContent(editIndex);//获取编辑器内容
        data.field.type='edit';//编辑属性
        //显示、审核状态
        data.field.isshow = data.field.show=="on" ? "1" : "2",
            data.field.istuijian = data.field.tuijian=="on" ?  "1" : "2";

        //弹出loading
        // var index = top.layer.msg('数据提交中，请稍候',{icon: 16,time:false,shade:0.8});

        $.ajax({
            type : "post",
            url : saveNewscategoryJSON,
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
    // 初始化父级分类

    $.get(getNewscategoryListJson, function(data){
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
