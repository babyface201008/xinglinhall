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

  /* Smoothscroll js
  -----------------------------------------------*/
    $(function() {
        $('.navbar-default a').bind('click', function(event) {
            var $anchor = $(this);
            $('html, body').stop().animate({
                scrollTop: $($anchor.attr('href')).offset().top - 49
            }, 1000);
            event.preventDefault();
        });
    });

  /* Team carousel
  -----------------------------------------------*/
  $(document).ready(function() {
      $("#team-carousel").owlCarousel({
          items : 3,
          itemsDesktop : [1199,3],
          itemsDesktopSmall : [979,3],
          slideSpeed: 300,
          itemsDesktop : [1199,2],
          itemsTablet: [768,1],
          itemsTabletSmall: [985,2],
          itemsMobile : [479,1],
      });
      $('#knowledge-carousel').owlCarousel({
          items:4,
          loop:true,
          nav:true,
          rtl: true,
          responsive:{
              480:{
                  items:3
              },
              960:{
                  items:5
              }
          }
      });
      $('#technology-carousel').owlCarousel({
          items:4,
          loop:true,
          nav:true,
          autoWidth: true,
          responsive:{
              480:{
                  items:3
              },
              960:{
                  items:5
              }
          }
      });
    });

    /* Back to Top
    -----------------------------------------------*/
    $(window).scroll(function() {
      if ($(this).scrollTop() > 200) {
          $('#communicate').fadeIn(200);
            } else {
                $('#communicate').fadeOut(200);
           }
        });   
          // Animate the scroll to top
      /* wow
      -------------------------------*/
      new WOW({ mobile: false }).init();

    /*--------------留言--------*/
    $("#msubmit").click(function () {
        if ($("#mname").val() =="" || $("#mphone-number").val() == "" || $("#mmessage").val() == "") {
            alert("请填写完整信息。");
            return false;
        }
        $.ajax({
            url:"/left_msg/",
            type:"POST",
            data:{"name":$("#mname").val(),"number":$("#mphone-number").val(), "msg":$("#mmessage").val()},
            success:function success() {
                alert("感谢留言， 我们会尽快与您联系。");
            }
        });
    });
});

