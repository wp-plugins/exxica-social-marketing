(function ( $ ) {
	"use strict";

	$(function () {

		$(document).ready(function() {
			$('#reinstall').click(function(e) {
				e.preventDefault();
				$('#reinstall').addClass('disabled');
				$.post(FactoryResetAjax.ajaxurl, [{"name":"_wpnonce","value":FactoryResetAjax.nonce}], function(d) { 
					var da = $.parseJSON(d); 
					if(da.success) 
						window.location.reload(true); 
				});
			});
		});
	});
}(jQuery));