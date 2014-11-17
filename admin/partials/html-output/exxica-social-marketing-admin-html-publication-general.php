<?php
/**
 * @link       http://exxica.com
 * @since      1.0.0
 *
 * @package    Exxica_Social_Marketing
 * @subpackage Exxica_Social_Marketing/partials/html-output
 */

 ?>
	<style>
		div .table-fullwidth {
			display: table;
			width: 100%;
		}
		div .table-row-fullwidth {
			display: table-row;
			width: 100%;
		}
		div .table-cell {
			display: table-cell;
			height: 130px;
			padding: 5px;
			vertical-align: top;
		}
		table .fullwidth {
			width: 100%;
			height: 130px;
		}
	</style>
<?php if( $accounts ) : ?>
	<tr id="sm-item-<?php echo $item['id']; ?>-edit" class="sm-item-edit" style="display:none">
		<td colspan="5">
			<script type="text/javascript">
				(function ( $ ) {
					"use strict";
					$(function () {
						var $item_id = "<?php echo $item['id']; ?>";
						$(document).ready(function() {	
							function setChanged()
							{
								$("input#new-changed-"+$item_id).attr('value', 1);
							}

							function getUTCStamp( date, offset ) 
							{
								var dt = new Date( date.getTime() + ( offset * 60 * 1000 ) );
								
								return dt;
							}

							var $original_data = <?php echo json_encode($original_data); ?>;

							$("#channel-"+$item_id).change(function(e, handler) { setChanged(); });
							$("#text-"+$item_id).change(function(e, handler) { setChanged(); });
							$("#one-time-date-"+$item_id).change(function(e, handler) { setChanged(); });
							$("#hour-"+$item_id).change(function(e, handler) { setChanged(); });
							$("#minute-"+$item_id).change(function(e, handler) { setChanged(); });
							$("#ampm-"+$item_id).change(function(e, handler) { setChanged(); });

							$("input[name=facebook-publish-"+$item_id+"]").each(function() { $(this).click(function(e, handler) { setChanged(); })});
							$("input[name=twitter-publish-"+$item_id+"]").each(function() { $(this).click(function(e, handler) { setChanged(); })});
							$("#filepath-"+$item_id).change(function(e, handler) { setChanged(); });
							$("#pattern-"+$item_id).change(function(e, handler) { setChanged(); });

						});
					});
				})(jQuery);
			</script>
			<input type="hidden" id="new-changed-<?php echo $item['id']; ?>" name="new-changed" value="0">
			<input type="hidden" id="post-id-<?php echo $item['id']; ?>" name="post-id" value="<?php echo $post->ID; ?>">
			<input type="hidden" id="item-id-<?php echo $item['id']; ?>" name="item-id" value="<?php echo $item['id']; ?>">
			<div class="table-fullwidth">
				<div class="table-row-fullwidth">
					<div class="table-cell" style="width:25%;text-align:left;">
						<table class="fullwidth">
							<tbody>
								<tr><td><?php echo $this->generate_script_channel_wrap($post, $channels, $item); ?></td></tr>
								<tr><td><?php echo $this->generate_script_account_wrap($post, $channels, $item); ?></td></tr>
							</tbody>
						</table>
					</div>
					<div class="table-cell" style="width:40%;text-align:center;border-left:thin solid #aaa;border-right:thin solid #aaa;">
						<table class="fullwidth">
							<tbody>
							<tr><td><?php echo $this->generate_script_text_wrap($post, $item); ?></td></tr>
							<tr><td><?php echo $this->generate_script_image_wrap($post, $item); ?></td></tr>
							</tbody>
						</table>
					</div>
					<div class="table-cell" style="width:35%;text-align:left;">
						<table class="fullwidth">
							<tbody>
								<tr><td><?php echo $this->generate_script_pattern_wrap($post, $item); ?></td></tr>
								<tr><td><?php echo $this->generate_script_time_wrap($post, $item); ?></td></tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<?php echo $this->generate_script_buttons($post, $item, $action); ?>
		</td>
	</tr>
<?php else : ?>
	<tr>
		<td><?php printf(__('No paired accounts found. You have to <a href="%s">set up</a> the plugin properly before publishing.', $this->name), admin_url('users.php?page=exxica-sm-settings')); ?></td>
	</tr>
<?php endif; ?>