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
				window.item<?php echo $item['id']; ?>Toggled = false;
				var $spinning_wheel = $("#spinning-wheel-<?php echo $item['id']; ?>");
				
				$spinning_wheel.hide();

				$('.sm-item').each(function() {
					$(this).click(function() {
						var item_id = <?php echo $item['id']; ?>;
						var $edit_row = $("#sm-item-"+item_id+"-edit");
						var $chevron = $("#chevron-"+item_id);
						var $channel = $("#channel-name-"+item_id);
						var $len = $("#text_num_chars-"+item_id);
						var $max_len = $("#text_max_chars-"+item_id);
						console.log($edit_row.html());
						if(window.item<?php echo $item['id']; ?>Toggled) {
							$edit_row.fadeOut( 400, function() {
								$(this).removeClass('selected');
								$edit_row.removeClass('selected');
								$chevron.html('<div class="dashicons dashicons-arrow-right"></div>');
								$len.html("0");
								if($channel.val() == "Twitter") {
									$max_len.html("140");
								} else {
									$max_len.html("4000");
								}
							});
							window.item<?php echo $item['id']; ?>Toggled = false;
						} else {
							$(this).addClass('selected');
							$edit_row.addClass('selected');
							$edit_row.fadeIn( 400, function() {
								$chevron.html('<div class="dashicons dashicons-arrow-down"></div>');
								$len.html("<?php echo strlen($item['publish_description']); ?>");
								if($channel.val() == "Twitter") {
									$max_len.html("140");
								} else {
									$max_len.html("4000");
								}
							});
							window.item<?php echo $item['id']; ?>Toggled = true;
						}
					});
				});
			});
		});
	})(jQuery);
</script>
<tr id="sm-item-<?php echo $item['id']; ?>" class="sm-item<?php echo $row_color;?>">
	<td style="width:2%;text-align:center"><span id="chevron-<?php echo $item['id']; ?>"><div class="dashicons dashicons-arrow-right"></div></span></td>
	<td style="width:10%;text-align:left;"><span id="channel-name-<?php echo $item['id']; ?>"><?php echo $item['channel']; ?></span></td>
	<td style="width:43%;text-align:left;"><span id="publish-short-text-<?php echo $item['id']; ?>"><?php echo $text[0].'...'; ?></span></td>
	<td style="width:30%;text-align:right;"><span id="publish-date-<?php echo $item['id']; ?>"><?php echo $daynames[date('D', $item['publish_localtime'])].date( ' '.$date_format.' '.$time_format, $item['publish_localtime'] ); ?></span></td>
	<td style="width:15%;text-align:right;"><?php echo $this->generate_script_actions($post, $item); ?></td>
</tr>