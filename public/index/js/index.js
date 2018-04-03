$(document).ready(function(){
	$(window).scroll(function(){
		var oHeight = document.documentElement.clientHeight || document.body.clientHeight;
		var sc = $(window).scrollTop();
		$(".section").each(function(){
			var sm = $(this).offset().top;
			if((sc+oHeight-100) >=sm){
				$(this).addClass("active");
			}
		})
	});
	
	  $(".closetanx").click(function(){
	  	$(this).parents(".moretan").hide();
	 });
	
	// $(".moretnbtn").click(function(){
	// 	$(".moretan").show();
	// });

	//绑定
	bindQQPanel();
	//bindMooits();
});


