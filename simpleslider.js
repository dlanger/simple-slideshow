//jQuery.noConflict();

simpleslider_prefs = {};

jQuery(document).ready(function($){
	$(".simpleslider_image_link").each(function(){
		$(this).children().removeAttr("title");
	});
		
	$.each(simpleslider_prefs, function(k,v){
		t_show = $("#simpleslider_show_"+k);
		t_prefs = simpleslider_prefs[k];
		t_show.cycle({
			timeout: 0,
			speed: t_prefs["transition_speed"],
			next: "#simpleslider_show_" + k + "_next",
			prev: "#simpleslider_show_" + k + "_prev",
		});
		t_show.children().css("display", "block");
	})	
	
});