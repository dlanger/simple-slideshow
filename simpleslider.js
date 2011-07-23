jQuery.noConflict();
simpleslider_prefs = {};

jQuery(document).ready(function($){
	
	$('.simpleslider_image_link').each(function(){
		$(this).children().removeAttr('title');
	});
		
	$.each(simpleslider_prefs, function(k, v){
		t_show = $('#simpleslider_show_' + k);
		t_show.cycle({
			timeout: 0,
			speed: v['transition_speed'],
			next: '#simpleslider_show_' + k + '_next',
			prev: '#simpleslider_show_' + k + '_prev',
		});
		t_show.children().css('display', 'block');
		attach_counter(k, v['slides']);
	});
	
	function attach_counter(k, slides) {
		var curr_pic = 1;
		var count_display = $('#simpleslider_show_' + k + '_count');
		$('#simpleslider_show_' + k + '_next').click(
				function(){
					curr_pic = (curr_pic == slides) ? 1 : curr_pic + 1;
					count_display.html(curr_pic);
					return false;
				}
		);
		
		$('#simpleslider_show_' + k + '_prev').click(
				function(){
					curr_pic = (curr_pic == 1) ? slides : curr_pic - 1;
					count_display.html(curr_pic);
					return false;
				}
		);
	}
});