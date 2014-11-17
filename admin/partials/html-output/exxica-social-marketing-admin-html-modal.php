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
			var exxica_functions = {
				d_ids : <?php echo json_encode($d_ids); ?>,
				table_data : <?php echo json_encode($data); ?>,
				prepareData : function()
				{
					var $changed = parseInt($("#new-changed-new").val());
					var $data_ids = exxica_functions.d_ids;
					var $post_data = [];

					var data = [];
					if( $changed == 1 ) {
						$data_ids.push("new");
					}

					for(var i = 0; i < $data_ids.length; i++) {
						var item_id = $data_ids[i];
						var ch = parseInt($("#new-changed-"+item_id).val());

						if(ch) {
							var $selected_weekdays = '';
							$("input[name=weekday-"+item_id+"]:checked").each( 
							    function() { 
							       $selected_weekdays += $(this).val()+';';
							   	}								
							);

							var action = "update";
							if(item_id == "new") {
								action = "create";
							}
							var hour = 0;
							var minute = parseInt($("#minute-"+item_id).val());
							var ampm = $("#ampm-"+item_id+" :selected").val();
							if(ampm == "pm") {
								hour = parseInt($("#hour-"+item_id).val());
								if(hour !== 12) {
									hour = hour+12;
								}
							} else if(ampm == "am") { 
								hour = parseInt($("#hour-"+item_id).val());
								if(hour == 12) {
									hour = hour-12;
								}
							} else {
								hour = parseInt($("#hour-"+item_id).val());
							}
							var one_date = $("#one-time-date-"+item_id).datepicker("getDate");
							var d = new Date(one_date);
							var d_local = new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate(), hour, minute, 0));
							var d_utc = new Date(d.getFullYear(), d.getMonth(), d.getDate(), hour, minute, 0);

							var local_json = d_local.toJSON();
							var local_time = Math.round(d_local.getTime() / 1000);
							var utc_json = d_utc.toJSON();
							var utc_time = Math.round(d_utc.getTime() / 1000);

							var $post_data = [
								{ 'name': "_wpnonce", 'value' : PostHandlerAjax.nonce },
								{ 'name' : "item_id", 'value' : item_id },
								{ 'name' : "post_id", 'value' : $("#post-id-"+item_id).val() },
								{ 'name' : "channel", 'value' : $("#channel-"+item_id).val() },
								{ 'name' : "text", 'value' : $("#text-"+item_id).val() },
								{ 'name' : "image_url", 'value' : $("#filepath-"+item_id).val() },
								{ 'name' : "local_time", 'value' : local_time, 'real' : local_json },
								{ 'name' : "one_time_utc_time", 'value' : utc_time, 'real' : utc_json },
								{ 'name' : "pattern", 'value' : $("#pattern-"+item_id).val() }
							];

							switch( $("#channel-"+item_id).val() ) {
								case 'Facebook' :
									$post_data.push({ 'name' : "publish_account", 'value' : $("input[name=facebook-publish-"+item_id+"]:checked").val() });
									break;
								case 'Twitter' :
									$post_data.push({ 'name' : "publish_account", 'value' : $("input[name=twitter-publish-"+item_id+"]:checked").val() });
									break;
								default :
									break;
							}

							var item = { 
								"doAction" : action, 
								"post_data" : $post_data
							};

							data.push(item);
						}
					}
					return data;
				}
			}

			$(document).ready(function() {

				$('a#esm-button-save-all-changes').click(function(e) {
					$('#save-changes-spinner').show();
					var data = exxica_functions.prepareData(); var i = 0;
					for( i = 0; i < data.length; i++) {
						var d = data[i];

						if(d.doAction == 'create') {
							$.post(PostHandlerAjax_Create.ajaxurl, d.post_data);
						} else if(d.doAction == 'destroy') {
							$.post(PostHandlerAjax_Destroy.ajaxurl, d.post_data);
						} else if(d.doAction == 'update') {
							$.post(PostHandlerAjax_Update.ajaxurl, d.post_data);
						} else {
							console.log(d);
						}
					}
					setTimeout(function() {
						window.location.reload(true);
					}, 6000)
				});
				$('a#esm-button-discard-all-changes').click(function(e) {
					window.location.reload(true);
				});
			});
		});
	})(jQuery);
</script>
<div id="esm-modal" class="wp-core-ui" style="display:none;">
	<div class="media-modal-content">
		<div class="esm-frame mode-select wp-core-ui">
			<div class="media-frame-menu">
				<div class="media-menu">
					<a href="#" class="media-menu-item active"><?php _e('Exxica Social Marketing', $this->name); ?></a>
					<div class="separator"></div>
					<a href="#" class="media-menu-item" id="sm-btn-add-new"><?php _e( 'Add New', $this->name ); ?></a>
					<div class="separator"></div>
					<a class="media-menu-item" id="sm-btn-overview" href="edit.php?page=exxica-sm-overview"><?php _e( 'Show overview', $this->name ); ?></a>
				</div>
			</div>
			<div class="esm-frame-title">
				<span style="font-size:1em;position:relative;float:right;">
					<?php _e('Publish Date:', $this->name); ?> <span style="font-weight:bold;"><?php echo date('d.m.Y \k\l\. H:i', strtotime($post->post_date)); ?></span>
				</span>
				<h1><?php _e('Title', $this->name); ?>: <?php echo esc_html($post->post_title); ?></h1>
				<p>
					<?php if($post->post_excerpt !== '') : ?>
						<?php _e('Excerpt', $this->name); ?>: <?php echo esc_html($post->post_excerpt); ?>
					<?php else : $text = str_split($post->post_content, 100); ?>
						<?php _e('Text', $this->name); ?>: <?php echo esc_html($text[0]); ?>...
					<?php endif; ?>
				</p>
			</div>
			<div class="esm-frame-content" data-columns="9">
				<?php echo $this->generate_script_new_publication($post); ?>
				<?php echo $this->generate_script_list($post); ?>
			</div>
			<div id="esm-frame-toolbar" class="media-frame-toolbar">
				<div class="media-toolbar">
					<div class="media-toolbar-primary">
						<a id="esm-button-discard-all-changes" href="#" class="button media-button button-secondary button-large"><?php _e('Discard all changes', $this->name); ?></a>
						<div id="save-changes-spinner" class="spinner">&nbsp;</div>
						<a id="esm-button-save-all-changes" href="#" class="button media-button button-primary button-large"><?php _e('Save all changes', $this->name); ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>