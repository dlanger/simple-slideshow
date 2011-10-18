simpleslider_prefs = {};

jQuery(document).ready(function($){
		
	$.each(simpleslider_prefs, function(k, v){
		t_show = $('#simpleslider_show_' + k);
		var opts = {
			timeout: 0, 
			speed: v['transition_speed'],
			next: '#simpleslider_show_' + k + '_next',
			prev: '#simpleslider_show_' + k + '_prev',
			after: update_counter(k)
		};
		
		if('fx' in v)
			opts['fx'] = v['fx'];
		
		if('auto_advance_speed' in v)
			opts['timeout'] = v['auto_advance_speed'];
		
		t_show.cycle(opts);
	});
	
	function update_counter(k){
		var count_display = $('#simpleslider_show_' + k + '_count');
		return function(c, n, opts){
			count_display.html(opts.currSlide+1);
		}
	}
});