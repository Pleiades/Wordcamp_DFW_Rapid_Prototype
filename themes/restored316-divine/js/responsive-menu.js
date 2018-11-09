jQuery(function( $ ){

	$(".nav-primary .genesis-nav-menu, .nav-secondary .genesis-nav-menu, .nav-header .genesis-nav-menu").addClass("responsive-menu").before('<div class="responsive-menu-icon"></div>');

	$(".responsive-menu-icon").click(function(){
		$(this).next(".nav-primary .genesis-nav-menu, .nav-secondary .genesis-nav-menu, .nav-header .genesis-nav-menu").slideToggle();
	});

	$(window).resize(function(){
		if(window.innerWidth > 767) {
			$(".nav-primary .genesis-nav-menu, .nav-secondary .genesis-nav-menu, nav .sub-menu, .nav-header .genesis-nav-menu").removeAttr("style");
			$(".responsive-menu > .menu-item").removeClass("menu-open");
		}
	});

	$(".responsive-menu > .menu-item").click(function(event){
		if (event.target !== this)
		return;
			$(this).find(".sub-menu:first").slideToggle(function() {
			$(this).parent().toggleClass("menu-open");
		});
	});

})