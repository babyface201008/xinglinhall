/* ----------- jQuery Pre loader ----------------*/
$(window).load(function(){
    $('.preloader').fadeOut(1000); // set duration in brackets    
});

$(document).ready(function() {
  /* Hide mobile menu after clicking on a link
    -----------------------------------------------*/
    $('.navbar-collapse a').click(function(){
        $(".navbar-collapse").collapse('hide');
    });

    /* -----------------------Smoothscroll js    ------------------------*/
    $(function() {
        $('.navbar-default a').bind('click', function(event) {
            var $anchor = $(this);
            $('html, body').stop().animate({
                scrollTop: $($anchor.attr('href')).offset().top - 49
            }, 1000);
            event.preventDefault();
        });
    });
    /*--------------留言--------*/
    $("#msubmit").click(function () {
        if ($("#mname").val() =="" || $("#mphone-number").val() == "" || $("#mmessage").val() == ""){
            alert("请填写完整信息。");
            return false;
        }
        $.ajax({
            url:"/left_msg/",
            type:"POST",
            data:{"name":$("#mname").val(),"number":$("#mphone-number").val(), "msg":$("#mmessage").val()},
            success:function success(data) {
                alert(data.msg);
            }
        });
    });
    /*--------------加入购物车----------------*/
    $("#add-car").click(function () {
        var pro_list = $("#pid").val() + ',' + $("#pcounts").val()+';';
        add_shop_car(pro_list, 'a', true);
    });
    function add_shop_car(pro_list, flag, is_tips) {
        $.ajax({
            url:addcart_url,
            type:"POST",
            data:{
                "pro_list": pro_list,
                'flag': flag,
            },
            success:function success() {
                if (is_tips) {
                    alert("购物车加入成功！");
                }

            }
        });
    }
    /*--------------立即购买----------------*/
    $("#buy-now").click(function () {
        var tot_amounts = Number($("#pprice").val())*Number($("#pcounts").val());
        var pro_list = $("#pid").val() + ',' + $("#pcounts").val() + ";";
        settle_car(tot_amounts, pro_list);
    });

    /*----------------打开购物车----------------------*/
    $("#open-shop-car").click(function () {
        open_shop_car();
    });

    /* --------------购物车之外点击隐藏购物车-------------------*/
    $("#details").click(function () {
        if ($("#side-bar").css("right") != "0px") {
            $("#side-bar").css("right", "0px");
            $("#shop-car").fadeOut();
            add_shop_car(shop_car_items(), 'c', false);
        }
    });
    $("#contact").click(function () {
        if ($("#side-bar").css("right") != "0px") {
            $("#side-bar").css("right", "0px");
            $("#shop-car").fadeOut();
            add_shop_car(shop_car_items(), 'c', false);
        }
    });
    $("#main-top").click(function () {
        if ($("#side-bar").css("right") != "0px") {
            $("#side-bar").css("right", "0px");
            $("#shop-car").fadeOut();
            add_shop_car(shop_car_items(), 'c', false);
        }
    });
    /* -----------------省市区三级联动---------------------*/
    $("#distpicker").distpicker();

    /* ----------------------下单-----------------------*/
    function settle_car(total_amounts, pro_list) {
        $("#settle-amounts").html(total_amounts);
        $("#settle-amounts").attr("for", total_amounts);
        $("#settle-list").val(pro_list);
        $("#total-settle").fadeIn();
        add_shop_car(pro_list, 'a', false);
    }
    /*-------------------提交订单-----------------*/
    $("#settle-submit").click(function () {
        var flag = false;
        if ($("#settle-name").val() == ""){
            alert("您如何称呼。");
            return;
        }
        if ($("#settle-number").val() == ""){
            alert("请填写您的电话，方便我们联系您。");
            return;
        }
        if ($("#settle-addr") == ""){
            alert("请填写您的地址，以便货物正常送达。");
            return;
        }
        $.ajax({
            url:place_order_url,
            type:"POST",
            data:{
                "pro_list": $("#settle-list").val(),
                "name": $("#settle-name").val(),
                "sex": $('.sex-radio input[name="sex"]:checked').val(),
                "number": $("#settle-number").val(),
                "district": $(".dist-select option:selected").text(),
                "addr":  $("#settle-addr").val(),
                "msg": $("#settle-msg").val(),
            },
            success:function success(data) {
                $("#total-settle").fadeOut();
                alert(data.msg);
            },
            error:function error() {
                alert("提交订单失败！\n请重试！");
            }
        });
    });
    /*-------------------修改订单-----------------*/
    $("#settle-modify").click(function () {
        $("#total-settle").fadeOut();
        open_shop_car();
    });
    /*-------------------关闭订单-----------------*/
    $("#settle-close").click(function () {
        $("#total-settle").fadeOut();
    });
    /* -----------------打开购物车---------------*/
    function open_shop_car() {
        if ($("#side-bar").css("right") == "0px") {
            $.ajax({
                url:shopcart_url,
                type:"get",
                success:function success(data) {
                    $("#shop-car").html(data);
                    $("#side-bar").css("right", "340px");
                    $("#shop-car").show();
                },
                error:function error() {
                    alert("购物车加载失败！\n请刷新重试或者联系我们。");
                }
            });
        }
        else{
            $("#side-bar").css("right", "0px");
            $("#shop-car").fadeOut();
            add_shop_car(shop_car_items(), 'c', false);
        }
    }

    /*-----------------------购物车数量获取---------------------------*/
    function shop_car_items() {
        var pro_list = ""
        var item_list = $(".car-content-items");
        for (var i=0;i<item_list.length;i++){
            pro_list = pro_list + $(item_list[i]).find("input[class='car-content-id']").val() + ',' + $(item_list[i]).find("input[class='car-content-counts']").val() + ";"
        }
        return pro_list;
    }
});
