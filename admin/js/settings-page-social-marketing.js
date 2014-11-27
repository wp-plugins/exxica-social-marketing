(function ( $ ) {
	"use strict";

	$(function () {
		function daysDiff( d1, d2) {
			var date1 = new Date(d1);
			var date2 = new Date(d2);
			return Math.floor((Math.max(date1.getTime(),date2.getTime())-Math.min(date1.getTime(),date2.getTime()))/86400000);
		}

		// Generate login URLs for authorize buttons
		function updateFacebookLoginUrl() {
			$.post(facebookLoginAjax.ajaxurl, $('form#auth').serializeArray(), function(data, status, xhr) {
				var facebook = $.parseJSON(data);
				if(facebook.success) {
					$('#submit_auth_facebook').attr("href", decodeURI( facebook.loginUrl ) );
					$('#submit_auth_facebook').removeClass('disabled');
				} 
			});
		}
		function updateTwitterLoginUrl() {
			$.post(twitterLoginAjax.ajaxurl, $('form#auth').serializeArray(), function(data, status, xhr) {
				var twitter = $.parseJSON(data);
				if(twitter.success) {
					$('#submit_auth_twitter').attr("href", decodeURI( twitter.loginUrl ) );
					$('#submit_auth_twitter').removeClass('disabled');
				} 
			});
		}

		function sendData() {
			var data = $('form#update_info').serializeArray();
			$.post(exxicaVerifyAjax.ajaxurl, data, function( data, status, xhr ) {
				$.post( processAjax.ajaxurl , data, function( data, status, xhr ) {
					var exxica = $.parseJSON(data);
					if(exxica.success) {
						var expiry_date = new Date(exxica.license_expires*1000);
						var current_date = new Date();
			
						var expiry_text = '';
						if(expiry_date <= current_date) {
							expiry_text = daysDiff(expiry_date, current_date)+Language.days_ago;
						} else {
							expiry_text	= Language.expires_in+daysDiff(expiry_date, current_date)+Language.days;
						}

						$('input#info_username').val(exxica.username);
						$('input#info_usermail').val(exxica.usermail);
						if(exxica.account_type !== "Lifetime") {
							$('span#license_expiry').html(expiry_text);
						}
						$('input#info_api_key').val(exxica.api_key);
						$('input#info_api_secret').val(exxica.api_secret);
						$('input#info_api_key_created').val(exxica.api_key_created);

						$('input#auth_username').val(exxica.username);
						$('input#auth_api_key').val(exxica.api_key);
						$('input#auth_api_secret').val(exxica.api_secret);
						$('input#auth_api_key_created').val(exxica.api_key_created);
						
						updateFacebookLoginUrl();
						updateTwitterLoginUrl();
						if(expiry_date <= current_date) {
							$('.hide_when_locked').each(function() {
								$(this).hide();
							});
						} else {
							$('.hide_when_locked').each(function() {
								$(this).show();
							});
						}
					}
				});
			});
		}

		$(document).ready(function() {
			$('#refresh_paired').click(function(e) {
				e.preventDefault();
				$('#refresh_paired').addClass('disabled');
				$.post(exxicaSyncAjax.ajaxurl, $('form#auth').serializeArray(), function( data, status, xhr) {
					$.post( ChannelHandlerAjax_Insert.ajaxurl, data, function(data, status, xhr) {
						var channels = $.parseJSON(data);
						if(channels.success) {
							window.location.reload(true);
						} else {
							$('#refresh_paired').removeClass('disabled');
						}
					});
				});
			});

			$("input[name=standard]").each(function() {
				$(this).change(function(e) {
					var data = [
						{
							'name' : 'standard', 
							'value' : parseInt($(this).val())
						}
					];
					$.post(ChannelHandlerAjax_Update_Standard.ajaxurl, data, function( data, status, xhr ) {
						var d = $.parseJSON(data);
						if(d.success) {
							window.location.reload(true);
						} else {
							console.log(d);
						}
					});
				});
			});

			$('.auth_btn').each(function() {
				$(this).addClass('disabled');
			});
			sendData();
		});
	});

}(jQuery));