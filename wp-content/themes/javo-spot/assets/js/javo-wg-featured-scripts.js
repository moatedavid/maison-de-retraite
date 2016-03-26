( function( $ ) {
	"use strict";
	var jvfrm_spot_wgfi = function( o ) {
		this.options = $.extend( true, {}, {
			rating:{
				container	: '.javo-rating-registed-score'
				, starOff	: '../images/star-off-s.png'
				, starOn	: '../images/star-on-s.png'
				, starHalf	: '../images/star-half-s.png'
			}
		}, o );
		if( ! window.__JAVO_WGFI__ ) {
			window.__JAVO_WGFI__ = true;
			this.init();
		}
	}
	jvfrm_spot_wgfi.prototype = {
		constructor		: jvfrm_spot_wgfi
		, init			: function()
		{
			// Set Rating
			this.setRating();
		}
		, setRating		: function()
		{
			var opt		= this.options.rating;
			$( opt.container ).each( function( k,  v)
			{
				$(this).raty({
					starOff		: opt.starOff
					, starOn	: opt.starOn
					, starHalf	: opt.starHalf
					, half		: true
					, readOnly	: true
					, score		: $(this).data('score')
				}).css('width', '');
			});
		}
	}
	window.jvfrm_spot_wgfi = jvfrm_spot_wgfi;
} )( jQuery );