<?php
/**
 * @link       http://exxica.com
 * @since      1.0.0
 *
 * @package    Exxica_Social_Marketing
 * @subpackage Exxica_Social_Marketing/partials/html-output
 */
 ?>
<script type="text/javascript">
	(function ( $ ) {
		"use strict";
		$(function () {
			$(document).ready(function() {
				var $textarea = $("#text-<?php echo $item['id']; ?>");
				var $text_length = $("#text_num_chars-<?php echo $item['id']; ?>");
				var $text_max_length = $("#text_max_chars-<?php echo $item['id']; ?>");
				var $submit = $("#submit-<?php echo $item['id']; ?>");

				$textarea.keydown(function(e) {
					$text_length.html( $textarea.val().length );
					if( parseInt($text_length.text()) > parseInt($text_max_length.text()) ) {
						$text_length.attr('style', 'color:red;');
						$submit.addClass("disabled");
					} else {
						$text_length.attr('style', 'color:black;');
						$submit.removeClass("disabled");
					}
				});	
			});
		});
	})(jQuery);
</script>
<div id="num-char-validation">
	<span id="text_num_chars-<?php echo $item['id']; ?>" class="normal_text">0</span> / <span id="text_max_chars-<?php echo $item['id']; ?>" class="bold_text">4000</span>
</div>
<label for="textwrap-<?php echo $item['id']; ?>"><?php _e('Text', $this->name); ?></label>
<div id="textwrap-<?php echo $item['id']; ?>">
	<textarea id="text-<?php echo $item['id']; ?>" name="text" style="width:98%;height:126px;overflow:scroll;"><?php echo $item['publish_description']; ?></textarea>
</div>