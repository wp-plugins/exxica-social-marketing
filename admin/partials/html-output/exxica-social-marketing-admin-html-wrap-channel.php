<?php
/**
 * @link       http://exxica.com
 * @since      1.0.0
 *
 * @package    Exxica_Social_Marketing
 * @subpackage Exxica_Social_Marketing/partials/html-output
 */
 ?>
<label for="channelwrap-<?php echo $item['id']; ?>"><?php _e('Channel', $this->name); ?></label>
<div id="channelwrap-<?php echo $item['id']; ?>">
	<script type="text/javascript">
		(function ( $ ) {
			"use strict";
			$(function () {
				$(document).ready(function() {
					var $channel = $("#channel-<?php echo $item['id']; ?>");
					var $accountsFacebook = $("#accounts-facebook-<?php echo $item['id']; ?>");
					var $accountTwitter = $("#accounts-twitter-<?php echo $item['id']; ?>");
					var $accountsLinkedIn = $("#accounts-linkedin-<?php echo $item['id']; ?>");
					var $accountsGoogle = $("#accounts-google-<?php echo $item['id']; ?>");
					var $accountsInstagram = $("#accounts-instagram-<?php echo $item['id']; ?>");
					var $accountsFlickr = $("#accounts-flickr-<?php echo $item['id']; ?>");
					var $text_max_length = $("#text_max_chars-<?php echo $item['id']; ?>");
					
					function changeChannel( to ) {
						if( to == 'Facebook' ) {
							$accountsFacebook.show();
							$accountTwitter.hide();
							$accountsLinkedIn.hide();
							$accountsGoogle.hide();
							$accountsInstagram.hide();
							$accountsFlickr.hide();
						} else if( to == 'Twitter' ) {
							$accountsFacebook.hide();
							$accountTwitter.show();
							$accountsLinkedIn.hide();
							$accountsGoogle.hide();
							$accountsInstagram.hide();
							$accountsFlickr.hide();
						} else if( to == 'LinkedIn' ) {
							$accountsFacebook.hide();
							$accountTwitter.hide();
							$accountsLinkedIn.show();
							$accountsGoogle.hide();
							$accountsInstagram.hide();
							$accountsFlickr.hide();
						} else if( to == 'Google' ) {
							$accountsFacebook.hide();
							$accountTwitter.hide();
							$accountsLinkedIn.hide();
							$accountsGoogle.show();
							$accountsInstagram.hide();
							$accountsFlickr.hide();
						} else if( to == 'Instagram' ) {
							$accountsFacebook.hide();
							$accountTwitter.hide();
							$accountsLinkedIn.hide();
							$accountsGoogle.hide();
							$accountsInstagram.show();
							$accountsFlickr.hide();
						} else if( to == 'Flickr' ) {
							$accountsFacebook.hide();
							$accountTwitter.hide();
							$accountsLinkedIn.hide();
							$accountsGoogle.hide();
							$accountsInstagram.hide();
							$accountsFlickr.show();
						}
						if(to == "Twitter") {
							$text_max_length.html("140");
						} else {
							$text_max_length.html("4000");
						}
					}
					changeChannel($channel.find(":selected").val());
					$channel.change(function() {
						changeChannel($(this).find(":selected").val());
					});
				});
			});
		})(jQuery);
	</script>
	<select id="channel-<?php echo $item['id']; ?>" name="channel" size="6" style="width:100%;height:100%;">
		<?php for($j = 0; $j < count($channels); $j++) : ?>
			<?php if($show_chan[$channels[$j]]) : $selected = ($channels[$j] == $item['channel']) ? ' selected="selected"' : '' ; ?>
				<option value="<?php echo $channels[$j]; ?>"<?php echo $selected; ?>><?php echo $channels[$j]; ?></option>
			<?php endif; ?>
		<?php endfor; ?>
	</select>
</div>