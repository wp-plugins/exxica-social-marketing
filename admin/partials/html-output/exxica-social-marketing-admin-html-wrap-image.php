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
				var $rem_link = $("#remove-image-<?php echo $item['id']; ?>");
				var $add_link = $("#add-image-<?php echo $item['id']; ?>");
				var $image = $("img#pub-img-<?php echo $item['id']; ?>");
				var $image_path = $("#filepath-<?php echo $item['id']; ?>");
				var $new_changed = $("#new-changed-<?php echo $item['id']; ?>");
				<?php if( $img ) : ?>
				$image_path.attr('value', "<?php echo $img; ?>");
				$image.attr('src', "<?php echo $img; ?>");
				$add_link.hide();
				$rem_link.show();
				<?php else : ?>
				$image_path.attr('value', '');
				$image.attr('src', "");
				$add_link.show();
				$rem_link.hide();
				<?php endif; ?>
				$add_link.click(function(e) {
					e.preventDefault();
					$new_changed.attr("value", 1);
					// Call WordPress media library
					var upl;
					upl = wp.media.frames.file_frame = wp.media({
						title: 'Choose File',
						button: {
							text: 'Choose File'
						},
						multiple: false
					});
					upl.on('select', function() {
						var attachment = upl.state().get('selection').first().toJSON();
						// Update fields with values
						$image_path.attr('value', attachment.url);
						$image.attr('src', attachment.url);
					});
					upl.open(); 
					// Show actions
					$add_link.hide();
					$rem_link.show();
					return false;
				});
				$rem_link.click(function(e) {
					e.preventDefault();
					$new_changed.attr("value", 1);
					// Update fields with values
					$image_path.attr('value', '');
					$image.attr('src', "");
					// Show actions
					$rem_link.hide();
					$add_link.show();
					return false;
				});
			});
		});
	})(jQuery);
</script>
<style>
	#pub-img-<?php echo $item['id']; ?> {
		max-width: 300px;
		height:	auto;
	}
</style>
<label for="imagewrap-<?php echo $item['id']; ?>"><?php _e('Image', $this->name); ?></label>
<div id="imagewrap-<?php echo $item['id']; ?>">
	<?php if( ! is_null( $img ) ) : ?>
	<input type="hidden" id="filepath-<?php echo $item['id']; ?>" name="image_url" value="<?php echo $img; ?>">
	<div id="image-<?php echo $item['id']; ?>" style="overflow:hidden;">
		<img id="pub-img-<?php echo $item['id']; ?>" src="<?php echo $img; ?>">
	</div>
	<?php endif; ?>
	<a href="#" id="remove-image-<?php echo $item['id']; ?>"><?php _e('Remove image from publication', $this->name); ?></a>
	<a href="#" id="add-image-<?php echo $item['id']; ?>"><?php _e('Add image to publication', $this->name); ?></a>
</div>