var val=$('.images').val(),
    selhtml='',
    delimage=''
layui.config({
	base : "js/"
}).use(['form','layer','jquery','laydate','upload'],function(){
	var form = layui.form(),
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		$ = layui.jquery;

    form.verify({
        /*logo : function(value, item){
            if(value ==''){
                return "请上传产品主图";
            }
        },*/
    });
 	form.on("submit(addProduct)",function(data){

        // var index = layer.msg('提交中，请稍候',{icon: 16,time:false,shade:0.8});
            // 提交请求
            $.ajax({
                type : 'post',
                url : ProductSave_URL,
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

    layui.upload({
        url: UPLOAD_URL
        ,success: function(res){
            if(res.did=='sourceimg'){
                var html ='<div class="float_img" data-url="'+res.message+'" id="'+res.message.slice(0,-4)+'" data-msg="sourceimg" onclick="delimg(this)"><img src="'+LOGO_URL+res.message+'" ><i class="layui-icon" style="font-size: 14px; color:red;">&#x1006;</i></div>';
                $('#sourceimg').html(html)
                $('.sourceimg').val(res.message)
            }else if(res.did=='images'){
                var html ='<div class="float_img" data-url="'+res.message+'" id="'+res.message.slice(0,-4)+'" data-msg="images" onclick="delimg(this)"><img src="'+LOGO_URL+res.message+'" ><i class="layui-icon" style="font-size: 14px; color:red;">&#x1006;</i></div>';
                $('#images').append(html)
                 val+=','+res.message
                $('.images').val(val)
            }
        }
    });
    $.get(getCategoryListURL, function(data){
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
                treeListsel(child,"",selid)
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
    var type=$(obj).data('msg');
    var productid=$(obj).data('id');
    layer.confirm('确定删除此图片？',{icon:3, title:'提示信息'},function(index){
        if(productid !=null){
            layer.msg('删除成功！');
            val=val.replace(','+url,"");
            $('.images').val(val);
            delimage+=','+url;
            $('.delimg').val(delimage)
            $('#'+url.slice(0,-4)).remove()
            return false;
        }
        $.ajax({
            url : delimg_URL,
            type : "post",
            data:{imgsrc:url,productid:productid},
            dataType : "json",
            success : function(data){
                if(data.statuscode==200){
                    layer.msg(data.message);
                    if(type=='images'){
                        val=val.replace(','+url,"");
                        $('.images').val(val);
                    }else{
                        $('.sourceimg').val('');
                    }
                    $('#'+url.slice(0,-4)).remove()
                }else {
                    layer.msg(data.message);
                }
            }
        })
    });
}
