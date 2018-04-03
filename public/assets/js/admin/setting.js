/**
 * Created by A01 on 2016/9/10.
 */
var system                  = $('#system'); //系统设置面板
var email                  = $('#email'); //系统设置面板
var sms                  = $('#sms'); //系统设置面板
var seo                  = $('#seo'); //系统设置面板
var systemscore                 = $('#system-score'); //系统设置面板
rechargeDetails            = $('#recharge-details');//详情面板

//系统设置表单对象
var expressBox          = $('.system-express'),
    orderTelBox          = $('.system-order-tel'),
    consultTelBox          = $('.system-consult-tel'),
    colophonBox          = $('.system-colophon'),
    conmentBox          = $('.system-conment'),
    systemnumBox          = $('.system-num'),
    titleBox          = $('.system-title'),
    keywordsBox   = $('.system-keywords'),
    descriptionBox          = $('.system-description'),
    systemSaveBtn           = $('.system-save');//保存系统设置
    seoSaveBtn           = $('.seo-save');//保存系统设置


//保存系统设置
systemSaveBtn.click(function(){
    var form = layui.form();
    form.on('submit(settingform)', function(data) {
        var saveurl = $(this).attr('url');
        var msg = $(this).data('msg');
        var param;
        if (msg == 'system') {
            param = system.serialize();
        } else if (msg == 'email') {
            param = email.serialize()

        } else if (msg == 'sms') {
            param = sms.serialize()

        } else if (msg == 'seo') {
            param = seo.serialize()
        } else if (msg == 'score') {
            param = systemscore.serialize()
        } else {
            return false;
        }
        $.ajax({
            type: 'post',
            url: saveurl,
            data: param,
            beforeSend: function () {
                var index = layer.load(1, {
                    shade: [0.1, '#fff'] //0.1透明度的白色背景
                });
            },
            success: function (data) {
                layer.closeAll()
                var info = JSON.parse(data);
                if (info.statuscode == '200') {
                    layer.msg(info.message);
                } else {
                    layer.msg(info.message);
                }
            },
            error: function () {
                layer.closeAll()
                layer.msg('系统出错，请联系管理员！');
            }
        });
    });
});

$('.is_express').change(function () {
    if($(this)['0'].checked){
        $('.express_val').attr('disabled',false)
    }else{
        $('.express_val').attr('disabled',true)
    }
})

//
// $('#tt').tabs({
//     onSelect: function(title){
//         if(title=='编辑充值设置'){
//             var tabs = $('#tt');
//             tabs.tabs('add',{
//                 title :'充值设置',
//                 closable : true,
//                 href : rechargeSetUrl
//             });
//
//             // var op = {
//             //         title:'充值设置',
//             //         href:rechargeSetUrl,
//             //         width:850,
//             //         height:500,
//             //         rel:'rechargeSetting'
//             //     };
//             //     addDialog(op);
//         }
//     }
// });
/**
 * 表单输入框
 */


jQuery(function($) {

    $('#myTab a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        //if($(e.target).attr('href') == "#home") doSomethingNow();
    })
});