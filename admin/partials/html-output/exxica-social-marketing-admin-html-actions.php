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
				var $item_remove = $("#sm-item-<?php echo $item['id']; ?>-action-remove");
				var $item_duplicate = $("#sm-item-<?php echo $item['id']; ?>-action-duplicate");
				var $item = $("#sm-item-<?php echo $item['id']; ?>");
				var $item_edit = $("#sm-item-<?php echo $item['id']; ?>-edit");
				var $chev = $("#chevron-<?php echo $item['id']; ?>");

				$item_remove.click(function(e) {
					e.preventDefault();
					var url = "<?php echo admin_url('admin-ajax.php?action=destroy_post_data'); ?>";
					var data = [ 
						{
							'name' : 'publish_unixtime',
							'value' : <?php echo $item['publish_unixtime']; ?>
						},{
							'name' : 'publish_localtime',
							'value' : <?php echo $item['publish_localtime']; ?>
						},{
							'name' : 'post_id',
							'value' : <?php echo $post->ID; ?>
						},{
							'name' : 'item_id',
							'value' : <?php echo $item['id']; ?>
						},{
							'name' : 'channel',
							'value' : "<?php echo $item['channel']; ?>"
						}
					];
					$.post(url, data, function(data, status, xhr) {
						var d = $.parseJSON(data);
						if(d.success) {
							$item_edit.remove();
							$item.remove();
						} else {
							console.log(d);
						}
					});

				});
			});
		});
	})(jQuery);
</script>
<?php if(strtotime('+30 minutes') <= $item['publish_localtime']) : ?>
	<a 
		id="sm-item-<?php echo $item['id']; ?>-action-remove" 
		href="#" 
		class="button button-secondary button-small" 
		title="<?php _e('Remove', $this->name); ?>">
		<div class="dashicons dashicons-no" style="color:red;padding-top:1px;"></div>
		<?php _e('Remove', $this->name); ?>
	</a>
<?php else : ?>
	<a 
		id="sm-item-<?php echo $item['id']; ?>-action-duplicate" 
		href="#" 
		class="button button-secondary button-small disabled" 
		title="<?php _e('Duplicate', $this->name); ?>">
		<div class="dashicons dashicons-tickets" style="color:blue;padding-top:1px;"></div>
		<?php _e('Duplicate', $this->name); ?>
	</a>
<?php endif; ?>