/**
* Fullscreenr - lightweight full screen background jquery plugin
* By Jan Schneiders
* www.nanotux.com
*
* Modifications by Chris Van Patten
* http://www.vanpattenmedia.com
* Version 1.5
**/

(function($){

	$.fn.fullscreenr = function(options) {
		var defaults = { width: 1280, height: 1024 };
		var options = $.extend({}, defaults, options);
		
		return this.each(function () {
			$(this).fullscreenrResizer(options);
		})
	}
	
	$.fn.fullscreenrResizer = function(options) {
		// Set bg size
		var ratio = options.height / options.width;
		
		// Get browser window size
		var browserwidth = $(window).width() + 80;
		var browserheight = $(window).height();
		
	// Scale the image
		if ((browserheight/browserwidth) > ratio){
			$(this).height(browserheight);
			$(this).width(browserheight / ratio);
		} else {
			$(this).width(browserwidth);
			$(this).height(browserwidth * ratio);
		}
		
	// Center the image
	// $(this).css('left', (browserwidth - $(this).width())/2);
	// $(this).css('top', (browserheight - $(this).height())/2);
	return this;
	}
	
})( jQuery );