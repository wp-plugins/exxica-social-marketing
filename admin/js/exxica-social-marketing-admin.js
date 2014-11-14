(function ( $ ) {
	"use strict";

	$(function () {
		String.prototype.trunc = String.prototype.trunc ||
		function(n){
			return this.length>n ? this.substr(0,n-1)+'&hellip;' : this;
		};
		$(document).ready(function() {	
			
		});
	});

}(jQuery));