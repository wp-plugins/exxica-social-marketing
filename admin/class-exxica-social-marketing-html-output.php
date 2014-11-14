<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://exxica.com
 * @since      1.0.0
 *
 * @package    Exxica_Social_Marketing
 * @subpackage Exxica_Social_Marketing/includes
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Exxica_Social_Marketing
 * @subpackage Exxica_Social_Marketing_Admin_Html_Output/admin
 * @author     Gaute Rønningen <gaute@exxica.com>
 */
class Exxica_Social_Marketing_Admin_Html_Output 
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name    The ID of this plugin.
	 */
	private $name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $name, $version ) 
	{
		$this->name = $name;
		$this->version = $version;
	}

	public function generate_script_new_publication($post) 
	{
		global $wpdb, $current_user;
		get_currentuserinfo();
		
    	$accTable = $wpdb->prefix.'exxica_social_marketing_accounts';
		$channels = array( 'Facebook', 'Twitter', 'LinkedIn', 'Google', 'Instagram', 'Flickr' );
    	$accountsFacebook = $wpdb->get_results("SELECT * FROM $accTable WHERE channel = '".$channels[0]."' LIMIT 1", ARRAY_A);
    	$accountsTwitter = $wpdb->get_results("SELECT * FROM $accTable WHERE channel = '".$channels[1]."' LIMIT 1", ARRAY_A);
    	$accountsLinkedIn = $wpdb->get_results("SELECT * FROM $accTable WHERE channel = '".$channels[2]."' LIMIT 1", ARRAY_A);
    	$accountsGoogle = $wpdb->get_results("SELECT * FROM $accTable WHERE channel = '".$channels[3]."' LIMIT 1", ARRAY_A);
    	$accountsInstagram = $wpdb->get_results("SELECT * FROM $accTable WHERE channel = '".$channels[4]."' LIMIT 1", ARRAY_A);
    	$accountsFlickr = $wpdb->get_results("SELECT * FROM $accTable WHERE channel = '".$channels[5]."' LIMIT 1", ARRAY_A);
    	$chan = $channels[0];
		$chanAcc = '';
    	switch($chan) {
    		case 'Facebook' :
    			if( ! is_null( $accountsFacebook ) )
    				$chanAcc = $accountsFacebook[0]['fb_page_id'];
    			break;
    		case 'Twitter' :
    			if( ! is_null( $accountsTwitter ) )
    				$chanAcc = $accountsTwitter[0]['fb_page_id'];
    			break;
    		case 'LinkedIn' :
    			if( ! is_null( $accountsLinkedIn ) )
    				$chanAcc = $accountsLinkedIn[0]['fb_page_id'];
    			break;
    		case 'Google' :
    			if( ! is_null( $accountsGoogle ) )
    				$chanAcc = $accountsGoogle[0]['fb_page_id'];
    			break;
    		case 'Instagram' :
    			if( ! is_null( $accountsInstagram ) )
    				$chanAcc = $accountsInstagram[0]['fb_page_id'];
    			break;
    		case 'Flickr' :
    			if( ! is_null( $accountsFlickr ) )
    				$chanAcc = $accountsFlickr[0]['fb_page_id'];
    			break;
    		default :
    			break;
    	}
		$item = array(
			'id' => "new",
			'post_id' => $post->ID,
			'exx_account'  => get_option('exxica_social_marketing_account_'.$current_user->user_login),
			'publish_unixtime' => strtotime('+30 minutes'),
			'publish_localtime' => strtotime('+30 minutes'),
			'publish_title' => $post->post_title,
			'publish_description' => wp_trim_words( $text = $post->post_content, $num_words = 20, $more = '&hellip;' ),
			'channel' => $chan,
			'channel_account' => $chanAcc
		);
		ob_start();
		?>
		<script type="text/javascript">
			(function ( $ ) {
				"use strict";
				$(function () {
					$(document).ready(function() {
						$('#sm-btn-add-new').click(function(e) {
							e.preventDefault();

							// Get current date
							var old = new Date();
							var cur = new Date(old.getTime() + 30 * 60 * 1000);
							var to = new Date();
							to.setDate( cur.getDate() + 60 );

							// Generate parts
							var day = cur.getDate();
							var month = cur.getMonth()+1;
							var year = cur.getFullYear();
							var hour = cur.getHours();
							var minute = cur.getMinutes();
							var _day = to.getDate();
							var _month = to.getMonth()+1;
							var _year = to.getFullYear();

							// Add leading zeros
							if(hour < 10) hour = ("0"+hour).slice(-2);
							if(minute < 10) minute = ("0"+minute).slice(-2);
							if(day < 10) day = ("0"+day).slice(-2);
							if(month < 10) month = ("0"+month).slice(-2);
							if(_day < 10) _day = ("0"+_day).slice(-2);
							if(_month < 10) _month = ("0"+_month).slice(-2);

							// Update fields
							$("#hour-<?php echo $item['id']; ?>").val(hour);
							$("#minute-<?php echo $item['id']; ?>").val(minute);
							$("#one-time-day-<?php echo $item['id']; ?>").val(day);
							$("#one-time-month-<?php echo $item['id']; ?>").val(month);
							$("#one-time-year-<?php echo $item['id']; ?>").val(year);
							$("#day-from-<?php echo $item['id']; ?>").val(day);
							$("#month-from-<?php echo $item['id']; ?>").val(month);
							$("#year-from-<?php echo $item['id']; ?>").val(year);
							$("#day-to-<?php echo $item['id']; ?>").val(_day);
							$("#month-to-<?php echo $item['id']; ?>").val(_month);
							$("#year-to-<?php echo $item['id']; ?>").val(_year);

							// Show input form
							$("#sm-item-<?php echo $item['id']; ?>-edit").fadeIn(400);
						});
					});
				});
			})(jQuery);
		</script>
		<table id="sm-table-add-new" class="sm-table">
			<tbody>
				<?php echo $this->generate_script_publication_general($post, $item, 'create'); ?>
			</tbody>
		</table>
		<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}
	public function generate_script_list($post) 
	{ 
		global $wpdb;

		
    	$mainTable = $wpdb->prefix.'exxica_social_marketing';
    	$data = $wpdb->get_results("SELECT * FROM $mainTable WHERE post_id = $post->ID ORDER BY publish_localtime ASC");

		ob_start();
		?>
		<style>
		.sm-table {
			width: 100%;
			border-top: 1px solid #ddd;
		}
		</style>
		<table id="sm-table" class="sm-table">
			<thead>
				<tr>
					<th style="width:2%;text-align:center"></th>
    				<th style="width:10%;text-align:left;"><?php _e('Channel', $this->name); ?></th>
    				<th style="width:43%;text-align:left;"><?php _e('Text', $this->name); ?></th>
    				<th style="width:30%;text-align:right;"><?php _e('Publish Date', $this->name); ?></th>
    				<th style="width:15%;text-align:right;"><?php _e('Actions', $this->name); ?></th>
    			</tr>
			</thead>
			<tbody>
			<?php if( count($data) !== 0 ) : ?>
				<?php $i = 0; 
				foreach($data as $itemObj) : 
					$item = (array) $itemObj;
					$i++; 
				?>
				<?php echo $this->generate_script_list_row($post, $item, $i); ?>
    			<?php echo $this->generate_script_publication($post, $item); ?>
				<?php endforeach; ?>
			<?php else : ?>
				<tr>
					<td colspan="5"><?php _e('No publishing options found.', $this->name); ?></td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>
		<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}
	public function generate_script_actions($post, $item) 
	{
		ob_start();
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
		<?php if(time() <= $item['publish_localtime']) : ?>
			<!-- <a id="sm-item-<?php echo $item['id']; ?>-action-edit" href="#"><?php _e('Edit', $this->name); ?></a> | -->
			<a 
				id="sm-item-<?php echo $item['id']; ?>-action-remove" 
				href="#" 
				class="button button-secondary button-small" 
				title="<?php _e('Remove', $this->name); ?>">
				<div class="dashicons dashicons-no" style="color:red;padding-top:1px;"></div>
				<?php _e('Remove', $this->name); ?>
			</a>
		<?php else : ?>
			<!-- <a id="sm-item-<?php echo $item['id']; ?>-action-view" href="#"><?php _e('View', $this->name); ?></a> | -->
			<!-- <a id="sm-item-<?php echo $item['id']; ?>-action-duplicate" href="#"><?php _e('Duplicate', $this->name); ?></a> -->
			<a 
				id="sm-item-<?php echo $item['id']; ?>-action-remove" 
				href="#" 
				class="button button-secondary button-small" 
				title="<?php _e('Remove', $this->name); ?>">
				<div class="dashicons dashicons-no" style="color:red;padding-top:1px;"></div>
				<?php _e('Remove', $this->name); ?>
			</a>
			<?php _e('Duplicate', $this->name); ?>
		<?php endif;
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}
	public function generate_script_list_row($post, $item, $i) 
	{
		$date_format = get_option( 'exxica_social_marketing_date_format', __( 'm/d/Y', $this->name ) );
		$time_format = get_option( 'exxica_social_marketing_time_format', __( 'g:i A', $this->name ) );

		$text = str_split($item['publish_description'],20); 
		$row_color = ($i % 2 == 0) ? ' even' : ' odd'; 
		if(strtotime('+3 days') > $item['publish_localtime'] && time() < $item['publish_localtime'] ) $row_color .= ' close_to_publish';
		if(time() > $item['publish_localtime']) $row_color .= ' past_publish';

		$daynames = array(
			'Mon' => __('Mon', $this->name),
			'Tue' => __('Tue', $this->name),
			'Wed' => __('Wed', $this->name),
			'Thu' => __('Thu', $this->name),
			'Fri' => __('Fri', $this->name),
			'Sat' => __('Sat', $this->name),
			'Sun' => __('Sun', $this->name),
		);
		ob_start();
		?>				
		<script type="text/javascript">
			(function ( $ ) {
				"use strict";
				$(function () {
					$(document).ready(function() {
						window.item<?php echo $item['id']; ?>Toggled = false;
						var $item = $('#sm-item-<?php echo $item['id']; ?>');
						var $item_edit = $('#sm-item-<?php echo $item['id']; ?>-edit');
						var $chev = $('#chevron-<?php echo $item['id']; ?>');
						var $chan = $('#channel-name-<?php echo $item['id']; ?>');
						var $text_length = $('#text_num_chars-<?php echo $item['id']; ?>');
						var $text_max_length = $('#text_max_chars-<?php echo $item['id']; ?>');
						var $spinning_wheel = $("#spinning-wheel-<?php echo $item['id']; ?>");
						
						$spinning_wheel.hide();

						$item.click(function() {
							if(window.item<?php echo $item['id']; ?>Toggled) {
								$item_edit.fadeOut( 400, function() {
									$item.removeClass('selected');
									$item_edit.removeClass('selected');
									$chev.html('<div class="dashicons dashicons-arrow-right"></div>');
									$text_length.html("0");
									if($chan.val() == "Twitter") {
										$text_max_length.html("140");
									} else {
										$text_max_length.html("4000");
									}
								});
								window.item<?php echo $item['id']; ?>Toggled = false;
							} else {
								$item.addClass('selected');
								$item_edit.addClass('selected');
								$item_edit.fadeIn( 400, function() {
									$chev.html('<div class="dashicons dashicons-arrow-down"></div>');
									$text_length.html("<?php echo strlen($item['publish_description']); ?>");
									if($chan.val() == "Twitter") {
										$text_max_length.html("140");
									} else {
										$text_max_length.html("4000");
									}
								});
								window.item<?php echo $item['id']; ?>Toggled = true;
							}
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
		<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}
	public function generate_script_publication_readonly($post, $item) 
	{
		global $wpdb, $current_user;
		get_currentuserinfo();
		$exxica_user_name = get_option('exxica_social_marketing_account_'.$current_user->user_login);
    	$accTable = $wpdb->prefix.'exxica_social_marketing_accounts';
		$channels = array( 'Facebook', 'Twitter', 'LinkedIn', 'Google', 'Instagram', 'Flickr' );
    	$accountsFacebook = $wpdb->get_results("SELECT * FROM $accTable WHERE channel = '".$channels[0]."' AND exx_account = '".$exxica_user_name."'", ARRAY_A);
    	$accountsTwitter = $wpdb->get_results("SELECT * FROM $accTable WHERE channel = '".$channels[1]."' AND exx_account = '".$exxica_user_name."'", ARRAY_A);
    	$accountsLinkedIn = $wpdb->get_results("SELECT * FROM $accTable WHERE channel = '".$channels[2]."' AND exx_account = '".$exxica_user_name."'", ARRAY_A);
    	$accountsGoogle = $wpdb->get_results("SELECT * FROM $accTable WHERE channel = '".$channels[3]."' AND exx_account = '".$exxica_user_name."'", ARRAY_A);
    	$accountsInstagram = $wpdb->get_results("SELECT * FROM $accTable WHERE channel = '".$channels[4]."' AND exx_account = '".$exxica_user_name."'", ARRAY_A);
    	$accountsFlickr = $wpdb->get_results("SELECT * FROM $accTable WHERE channel = '".$channels[5]."' AND exx_account = '".$exxica_user_name."'", ARRAY_A);
		ob_start();
		?>
		<style>
			#pub-img-<?php echo $item['id']; ?> {
				max-width: 300px;
				height:	auto;
			}
		</style>
		<tr id="sm-item-<?php echo $item['id']; ?>-edit" style="display:none">
			<td colspan="5">
				<div style="display:table;width:100%;border:thin solid black;">
					<div style="display:table-row;width:100%;">
						<div style="display:table-cell;width:25%;height:130px;text-align:left;padding:5px;">
							<label for="channelwrap-<?php echo $item['id']; ?>"><?php _e('Channel', $this->name); ?></label>
							<div id="channelwrap-<?php echo $item['id']; ?>">
								<?php echo $item['channel']; ?>
							</div>
							<label for="accountwrap-<?php echo $item['id']; ?>"><?php _e('Account', $this->name); ?></label>
							<div id="accountwrap-<?php echo $item['id']; ?>">
								<?php if($item['channel'] == 'Facebook' ) : ?>
								<div id="accounts-facebook-<?php echo $item['id']; ?>">
									<label for="accounts-facebook-publish-to-<?php echo $item['id']; ?>"><?php _e('Published on',$this->name); ?>:</label>
									<?php 
										foreach( $accountsFacebook as $it ) {
											if( $it['fb_page_id'] == $item['channel_account'] ) {
												echo $it['channel_account'];
												break;
											}
										}
									?>
								</div>
								<?php elseif($item['channel'] == 'Twitter' ) : ?>
								<div id="accounts-twitter-<?php echo $item['id']; ?>">
									<label for="accounts-twitter-publish-to-<?php echo $item['id']; ?>"><?php _e('Published on',$this->name); ?>:</label>
									<?php echo $item['channel_account']; ?><br/>
								</div>
								<?php elseif($item['channel'] == 'LinkedIn' ) : ?>
								<div id="accounts-linkedin-<?php echo $item['id']; ?>">
									<label for="accounts-linkedin-publish-to-<?php echo $item['id']; ?>"><?php _e('Published on',$this->name); ?>:</label>
									<?php echo $item['channel_account']; ?>														
								</div>
								<?php elseif($item['channel'] == 'Google' ) : ?>
								<div id="accounts-google-<?php echo $item['id']; ?>">
									<label for="accounts-google-publish-to-<?php echo $item['id']; ?>"><?php _e('Published on',$this->name); ?>:</label>
									<?php echo $item['channel_account']; ?><br/>
								</div>
								<?php elseif($item['channel'] == 'Instagram' ) : ?>
								<div id="accounts-instagram-<?php echo $item['id']; ?>">
									<label for="accounts-instagram-publish-to-<?php echo $item['id']; ?>"><?php _e('Published on',$this->name); ?>:</label>
									<?php echo $item['channel_account']; ?><br/>
								</div>
								<?php elseif($item['channel'] == 'Flickr' ) : ?>
								<div id="accounts-flickr-<?php echo $item['id']; ?>">
									<label for="accounts-flickr-publish-to-<?php echo $item['id']; ?>"><?php _e('Published on',$this->name); ?>:</label>
									<?php echo $item['channel_account']; ?>
								</div>
								<?php endif; ?>
							</div>
						</div>
						<div style="display:table-cell;width:40%;height:130px;text-align:center;border-left:thin solid #aaa;border-right:thin solid #aaa;padding:5px;">
							<label for="textwrap-<?php echo $item['id']; ?>"><?php _e('Text', $this->name); ?></label>
							<div id="textwrap-<?php echo $item['id']; ?>" style="text-align:left;height:60px;overflow-y:scroll;overflow-x:hidden;">
								<?php echo $item['publish_description']; ?>
							</div>
							<label for="imagewrap-<?php echo $item['id']; ?>"><?php _e('Image', $this->name); ?></label>
							<div id="imagewrap-<?php echo $item['id']; ?>">
								<div id="image-<?php echo $item['id']; ?>" style="overflow:hidden;">
									<img id="pub-img-<?php echo $item['id']; ?>" src="<?php echo $item['publish_image_url']; ?>">
								</div>
							</div>							
						</div>
						<div style="display:table-cell;width:35%;height:130px;text-align:left;padding:5px;">
							<label for="patternwrap-<?php echo $item['id']; ?>"><?php _e('Published', $this->name); ?></label>
							<div id="patternwrap-<?php echo $item['id']; ?>">
								<?php echo date('d.m.Y \k\l\. H:i', $item['publish_localtime'] ); ?>
							</div>
						</div>
					</div>
				</div>
			</td>
		</tr>
		<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}
	public function generate_script_publication_general($post, $item, $action) 
	{
	    global $wpdb;

		$post_publish_time = strtotime($post->post_date);
		$date_format = get_option( 'exxica_social_marketing_date_format', __( 'm/d/Y', $this->name ) );

		// Check if post publish time is after publish_localtime
		if($action == 'create') {
			if($item['publish_localtime'] <= $post_publish_time) $item['publish_localtime'] = $post_publish_time+(60*30);
		}

	    $accTable = $wpdb->prefix.'exxica_social_marketing_accounts';
		$channels = array( 'Facebook', 'Twitter');//, 'LinkedIn', 'Google', 'Instagram', 'Flickr' );
    	$accounts = $wpdb->get_results("SELECT * FROM $accTable", ARRAY_A);
    	$original_data = array('post'=>$post, 'item'=>$item);
		ob_start();
		?>
		<?php if( $accounts ) : ?>
			<tr id="sm-item-<?php echo $item['id']; ?>-edit" style="display:none">
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
					<div style="display:table;width:100%;">
						<div style="display:table-row;width:100%;">
							<div style="display:table-cell;width:25%;height:130px;text-align:left;padding:5px;vertical-align:top;">
								<table style="width:100%;height:130px;">
									<tbody>
										<tr><td>
											<?php echo $this->generate_script_channel_wrap($post, $channels, $item); ?>
										</td></tr>
										<tr><td><?php echo $this->generate_script_account_wrap($post, $channels, $item); ?></td></tr>
									</tbody>
								</table>
							</div>
							<div style="display:table-cell;width:40%;height:130px;text-align:center;border-left:thin solid #aaa;border-right:thin solid #aaa;padding:5px;vertical-align:top;">
								<table style="width:100%;height:130px;">
									<tbody>
									<tr><td><?php echo $this->generate_script_text_wrap($post, $item); ?></td></tr>
									<tr><td><?php echo $this->generate_script_image_wrap($post, $item); ?></td></tr>
									</tbody>
								</table>
							</div>
							<div style="display:table-cell;width:35%;height:130px;text-align:left;padding:5px;vertical-align:top;">
								<table style="width:100%;height:130px;">
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
		<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}
	public function generate_script_publication($post, $item) 
	{
		ob_start();
		$d = new DateTime( date('Y-m-d H:i:s',$item['publish_localtime']) );
		$n = ($d->getTimestamp() + $d->getOffset());
		if(time() <= $n) : 
			echo $this->generate_script_publication_general($post, $item, 'update');
		else : 
			echo $this->generate_script_publication_readonly($post, $item);
		endif;
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}
	public function generate_script_channel_wrap($post, $channels, $item)
	{
		global $wp, $wpdb, $current_user;
		get_currentuserinfo();

		$show_chan = array(
			'Facebook' => get_option('exxica_social_marketing_show_channel_facebook_'.$current_user->user_login, 0),
			'Twitter' => get_option('exxica_social_marketing_show_channel_twitter_'.$current_user->user_login, 0),
			'LinkedIn' => get_option('exxica_social_marketing_show_channel_linkedin_'.$current_user->user_login, 0),
			'Google' => get_option('exxica_social_marketing_show_channel_google_'.$current_user->user_login, 0),
			'Instagram' => get_option('exxica_social_marketing_show_channel_instagram_'.$current_user->user_login, 0),
			'Flickr' => get_option('exxica_social_marketing_show_channel_flickr_'.$current_user->user_login, 0)
		);

		ob_start();
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
		<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}
	public function generate_script_account_wrap($post, $channels, $item)
	{
	    global $wpdb, $current_user;
	    get_currentuserinfo();
    	$accTable = $wpdb->prefix.'exxica_social_marketing_accounts';
    	$login_name = $current_user->user_login;
    	$exxica_login = get_option("exxica_social_marketing_account_".$login_name);

		$show_channel_facebook = get_option('exxica_social_marketing_show_channel_facebook_'.$login_name );
		$show_channel_twitter = get_option('exxica_social_marketing_show_channel_twitter_'.$login_name);
		$show_channel_linkedin = get_option('exxica_social_marketing_show_channel_linkedin_'.$login_name);
		$show_channel_google = get_option('exxica_social_marketing_show_channel_google_'.$login_name);
		$show_channel_instagram = get_option('exxica_social_marketing_show_channel_instagram_'.$login_name);
		$show_channel_flickr = get_option('exxica_social_marketing_show_channel_flickr_'.$login_name);

    	if(isset($channels[0]) && $show_channel_facebook == 1 ) {
    		$sqlFacebook = sprintf("SELECT * FROM $accTable WHERE channel = '%s' AND exx_account = '%s'", $channels[0], $exxica_login);
    		$accountsFacebook = $wpdb->get_results($sqlFacebook, ARRAY_A);
    	}
    	if(isset($channels[1]) && $show_channel_twitter == 1 ) {
    		$sqlTwitter = sprintf("SELECT * FROM $accTable WHERE channel = '%s' AND exx_account = '%s'", $channels[1], $exxica_login);
    		$accountsTwitter = $wpdb->get_results($sqlTwitter, ARRAY_A);
    	}
    	if(isset($channels[2]) && $show_channel_linkedin == 1 ) {
    		$sqlLinkedIn = sprintf("SELECT * FROM $accTable WHERE channel = '%s' AND exx_account = '%s'", $channels[2], $exxica_login);
    		$accountsLinkedIn = $wpdb->get_results($sqlLinkedIn, ARRAY_A);
    	}
    	if(isset($channels[3]) && $show_channel_google == 1 ) {
    		$sqlGoogle = sprintf("SELECT * FROM $accTable WHERE channel = '%s' AND exx_account = '%s'", $channels[3], $exxica_login);
    		$accountsGoogle = $wpdb->get_results($sqlGoogle, ARRAY_A);
    	}
    	if(isset($channels[4]) && $show_channel_instagram == 1 ) {
    		$sqlInstagram = sprintf("SELECT * FROM $accTable WHERE channel = '%s' AND exx_account = '%s'", $channels[4], $exxica_login);
    		$accountsInstagram = $wpdb->get_results($sqlInstagram, ARRAY_A);
    	}
		if(isset($channels[5]) && $show_channel_flickr == 1 ) {
    		$sqlFlickr = sprintf("SELECT * FROM $accTable WHERE channel = '%s' AND exx_account = '%s'", $channels[5], $exxica_login);
    		$accountsFlickr = $wpdb->get_results($sqlFlickr, ARRAY_A);
		}

		ob_start();
		?>

		<?php if( isset( $accountsFacebook ) ) : ?>
		<div id="accounts-facebook-<?php echo $item['id']; ?>">
			<label><?php _e('Pages you administer', $this->name); ?></label>
			<div style="display:table;width:100%;border:thin solid #ddd;background-color: #fff;">
				<div style="display:table-row;background-color:#bbb;">
					<div style="display:table-cell;text-align:left;font-weight:bold;"><?php _e('Name',$this->name); ?></div>
					<div style="display:table-cell;text-align:center;font-weight:bold;"><?php _e('Publish',$this->name); ?></div>
				</div>
				<?php for($k = 0; $k < count($accountsFacebook); $k++ ) : $account = $accountsFacebook[$k]; ?>
				<div style="display:table-row;">
					<div style="display:table-cell;text-align:left;"><?php echo $account['channel_account']; ?></div>
					<div style="display:table-cell;text-align:center;border-left: thin solid #ddd;">
						<input id="<?php echo $k; ?>-facebook-publish" type="radio" name="facebook-publish-<?php echo $item['id']; ?>"<?php echo ($item['channel_account'] == $account['fb_page_id']) ? ' checked="checked"' : ''; ?> value="<?php echo $account['fb_page_id']; ?>">
					</div>
				</div>
				<?php endfor; ?>
			</div>
		</div>
		<?php endif; ?>
		<?php if( isset( $accountsTwitter ) ) : ?>
		<div id="accounts-twitter-<?php echo $item['id']; ?>" style="display:none">
			<label><?php _e('Accounts you administer', $this->name); ?></label>
			<div style="display:table;width:100%;border:thin solid #ddd;background-color: #fff;">
				<div style="display:table-row;background-color:#bbb;">
					<div style="display:table-cell;text-align:left;font-weight:bold;"><?php _e('Name',$this->name); ?></div>
					<div style="display:table-cell;text-align:center;font-weight:bold;"><?php _e('Tweet',$this->name); ?></div>
				</div>
				<?php for($k = 0; $k < count($accountsTwitter); $k++ ) : $account = $accountsTwitter[$k]; ?>
				<div style="display:table-row;">
					<div style="display:table-cell;text-align:left;"><?php echo $account['channel_account']; ?></div>
					<div style="display:table-cell;text-align:center;border-left: thin solid #ddd;">
						<input id="<?php echo $k; ?>-twitter-publish" type="radio" name="twitter-publish-<?php echo $item['id']; ?>"<?php echo ($item['channel_account'] == $account['fb_page_id']) ? ' checked="checked"' : ''; ?> value="<?php echo $account['fb_page_id']; ?>">
					</div>
				</div>
				<?php endfor; ?>
			</div>
		</div>
		<?php endif; ?>
		<?php if( isset( $accountsLinkedIn) ) : ?>
		<div id="accounts-linkedin-<?php echo $item['id']; ?>" style="display:none">
			<label><?php _e('Pages you administer', $this->name); ?></label>
			<div style="display:table;width:100%;border:thin solid #ddd;background-color: #fff;">
				<div style="display:table-row;background-color:#bbb;">
					<div style="display:table-cell;text-align:left;font-weight:bold;"><?php _e('Name',$this->name); ?></div>
					<div style="display:table-cell;text-align:center;font-weight:bold;"><?php _e('Publish',$this->name); ?></div>
				</div>
				<?php for($k = 0; $k < count($accountsLinkedIn); $k++ ) : $account = $accountsLinkedIn[$k]; ?>
				<div style="display:table-row;">
					<div style="display:table-cell;text-align:left;"><?php echo $account['channel_account']; ?></div>
					<div style="display:table-cell;text-align:center;border-left: thin solid #ddd;">
						<input id="<?php echo $k; ?>-linkedin-publish" type="radio" name="linkedin-publish-<?php echo $item['id']; ?>"<?php echo ($item['channel_account'] == $account['channel_account']) ? ' checked' : ''; ?> value="<?php echo $account['name']; ?>">
					</div>
				</div>
				<?php endfor; ?>
			</div>												
		</div>
		<?php endif; ?>
		<?php if( isset( $accountsGoogle ) ) : ?>
		<div id="accounts-google-<?php echo $item['id']; ?>" style="display:none">
			<label><?php _e('Pages you administer', $this->name); ?></label>
			<div style="display:table;width:100%;border:thin solid #ddd;background-color: #fff;">
				<div style="display:table-row;background-color:#bbb;">
					<div style="display:table-cell;text-align:left;font-weight:bold;"><?php _e('Name',$this->name); ?></div>
					<div style="display:table-cell;text-align:center;font-weight:bold;"><?php _e('Publish',$this->name); ?></div>
					<div style="display:table-cell;text-align:center;font-weight:bold;"><?php _e('+1',$this->name); ?></div>
				</div>
				<?php for($k = 0; $k < count($accountsGoogle); $k++ ) : $account = $accountsGoogle[$k]; ?>
				<div style="display:table-row;">
					<div style="display:table-cell;text-align:left;"><?php echo $account['channel_account']; ?></div>
					<div style="display:table-cell;text-align:center;border-left: thin solid #ddd;">
						<input id="<?php echo $k; ?>-google-publish" type="radio" name="google-publish-<?php echo $item['id']; ?>"<?php echo ($item['channel_account'] == $account['channel_account']) ? ' checked' : ''; ?> value="<?php echo $account['name']; ?>">
					</div>
				</div>
				<?php endfor; ?>
			</div>
		</div>
		<?php endif; ?>
		<?php if( isset( $accountsInstagram ) ) : ?>
		<div id="accounts-instagram-<?php echo $item['id']; ?>" style="display:none">
			<label><?php _e('Accounts you administer', $this->name); ?></label>
			<div style="display:table;width:100%;border:thin solid #ddd;background-color: #fff;">
				<div style="display:table-row;background-color:#bbb;">
					<div style="display:table-cell;text-align:left;font-weight:bold;"><?php _e('Name',$this->name); ?></div>
					<div style="display:table-cell;text-align:center;font-weight:bold;"><?php _e('Publish',$this->name); ?></div>
					<div style="display:table-cell;text-align:center;font-weight:bold;"><?php _e('Like',$this->name); ?></div>
				</div>
				<?php for($k = 0; $k < count($accountsInstagram); $k++ ) : $account = $accountsInstagram[$k]; ?>
				<div style="display:table-row;">
					<div style="display:table-cell;text-align:left;"><?php echo $account['channel_account']; ?></div>
					<div style="display:table-cell;text-align:center;border-left: thin solid #ddd;">
						<input id="<?php echo $k; ?>-instagram-publish" type="radio" name="instagram-publish-<?php echo $item['id']; ?>"<?php echo ($item['channel_account'] == $account['channel_account']) ? ' checked' : ''; ?> value="<?php echo $account['name']; ?>">
					</div>
				</div>
				<?php endfor; ?>
			</div>
		</div>
		<?php endif; ?>
		<?php if( isset( $accountsFlickr ) ) : ?>
		<div id="accounts-flickr-<?php echo $item['id']; ?>" style="display:none">
			<label><?php _e('Accounts you administer', $this->name); ?></label>
			<div style="display:table;width:100%;border:thin solid #ddd;background-color: #fff;">
				<div style="display:table-row;background-color:#bbb;">
					<div style="display:table-cell;text-align:left;font-weight:bold;"><?php _e('Name',$this->name); ?></div>
					<div style="display:table-cell;text-align:center;font-weight:bold;"><?php _e('Publish',$this->name); ?></div>
				</div>
				<?php for($k = 0; $k < count($accountsFlickr); $k++ ) : $account = $accountsFlickr[$k]; ?>
				<div style="display:table-row;">
					<div style="display:table-cell;text-align:left;"><?php echo $account['channel_account']; ?></div>
					<div style="display:table-cell;text-align:center;border-left: thin solid #ddd;">
						<input id="<?php echo $k; ?>-flickr-publish" type="radio" name="flickr-publish-<?php echo $item['id']; ?>"<?php echo ($item['channel_account'] == $account['channel_account']) ? ' checked' : ''; ?> value="<?php echo $account['name']; ?>">
					</div>
				</div>
				<?php endfor; ?>
			</div>	
		</div>
		<?php endif;

		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}
	public function generate_script_text_wrap($post, $item)
	{
		ob_start();
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
		<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}
	public function generate_script_pattern_wrap($post, $item)
	{
		// TODO ONE TIME ONLY CONVERTED TO DATEPICKER - DO RANGE AS WELL


		$date_format = get_option( 'exxica_social_marketing_date_format', __( 'm/d/Y', $this->name ) );
		$time_format = get_option( 'exxica_social_marketing_time_format', __( 'g:i A', $this->name ) );
		$twentyfour_clock_enabled = get_option( 'exxica_social_marketing_twentyfour_clock_enabled', '1' );

		$p_date = date($date_format, $item['publish_localtime']);
		$p_day = date('d', $item['publish_localtime']);
		$p_month = date('m', $item['publish_localtime']);
		$p_year = date('Y', $item['publish_localtime']);
		$p_hour = date('H', $item['publish_localtime']);
		$p_minute = date('i', $item['publish_localtime']);
		$c_day = date('d', time() );
		$c_month = date('m', time() );
		$c_year = date('Y', time() );
		$f_day = date( 'd', $item['publish_localtime'] + 1209600 );
		$f_month = date( 'm', $item['publish_localtime'] + 1209600 );
		$f_year = date( 'Y', $item['publish_localtime'] + 1209600 );
		ob_start();
		?>
		<div>
			<label for="patternwrap-<?php echo $item['id']; ?>"><?php _e('Time Pattern', $this->name); ?></label>
			<div id="patternwrap-<?php echo $item['id']; ?>">
				<script type="text/javascript">
					(function ( $ ) {
						"use strict";
						$(function () {
							$(document).ready(function() {
								var $pattern = $('#pattern-<?php echo $item['id']; ?>');
								var $singlePattern = $('#one-time-event-wrap-<?php echo $item['id']; ?>')
								var $dailyPattern = $('#daily-event-wrap-<?php echo $item['id']; ?>')
								var $weeklyPattern = $('#weekly-event-wrap-<?php echo $item['id']; ?>')
								var $monthlyPattern = $('#monthly-event-wrap-<?php echo $item['id']; ?>')
								var $yearlyPattern = $('#yearly-event-wrap-<?php echo $item['id']; ?>')
								var $eventRange = $('#event-range-wrap-<?php echo $item['id']; ?>')
								var $identStr = $('span#ident_str');
								function showPattern( selected ) {
									if( selected == "daily" ) {
										$singlePattern.hide();
										$dailyPattern.show();
										$weeklyPattern.hide();
										$monthlyPattern.hide();
										$yearlyPattern.hide();
										$eventRange.show();
										$identStr.html("<?php _e('daily', $this->name); ?>");
									} else if( selected == "weekly" ) {
										$singlePattern.hide();
										$dailyPattern.hide();
										$weeklyPattern.show();
										$monthlyPattern.hide();
										$yearlyPattern.hide();
										$eventRange.show();
										$identStr.html("<?php _e('weekly', $this->name); ?>");
									}  else if( selected == "monthly" ) {
										$singlePattern.hide();
										$dailyPattern.hide();
										$weeklyPattern.hide();
										$monthlyPattern.show();
										$yearlyPattern.hide();
										$eventRange.show();
										$identStr.html("<?php _e('monthly', $this->name); ?>");
									} else if( selected == "yearly" ) {
										$singlePattern.hide();
										$dailyPattern.hide();
										$weeklyPattern.hide();
										$monthlyPattern.hide();
										$yearlyPattern.show();
										$eventRange.show();
										$identStr.html("<?php _e('yearly', $this->name); ?>");
									} else {
										$singlePattern.show();
										$dailyPattern.hide();
										$weeklyPattern.hide();
										$monthlyPattern.hide();
										$yearlyPattern.hide();
										$eventRange.hide();
									}
								}
								var selected = $pattern.find(":selected").val();
								showPattern(selected);
								$pattern.change(function() {
									selected = $(this).find(":selected").val();
									showPattern(selected);
								});
							});
						});
					})(jQuery);
				</script>
				<style>
					#one-time-dayname {
						font-weight: bold;
					}
					#one-time-dayname:before {
						content: " ";
					}
					#one-time-dayname:after {
						content: " ";
					}
				</style>
				<select id="pattern-<?php echo $item['id']; ?>" name="pattern" disabled="disabled">
					<optgroup label="<?php _e('Single Events',$this->name); ?>">
						<option value="single-selected" selected="selected"><?php _e('One-time only',$this->name); ?></option>
					</optgroup>
					<optgroup label="<?php _e('Repeatable Events',$this->name); ?>">
						<option disabled value="daily"><?php _e('Daily',$this->name); ?></option>
						<option disabled value="weekly"><?php _e('Weekly',$this->name); ?></option>
						<option disabled value="monthly"><?php _e('Monthly',$this->name); ?></option>
						<option disabled value="yearly"><?php _e('Yearly',$this->name); ?></option>
					</optgroup>																
				</select>
			</div>
		</div>
		<script>
		(function ( $ ) {
			"use strict";
			$(function () {
				$(document).ready(function() {
					<?php if(__('en_US', $this->name) == 'nb_NO') : ?>
					$.datepicker.setDefaults($.extend({'dateFormat':'dd.mm.yy'}, $.datepicker.regional['no']));
					<?php endif; ?>

					$('.datepicker').each(function() {
						$(this).datepicker();
					});
					$("#one-time-date-<?php echo $item['id']; ?>").datepicker("setDate", "<?php echo $p_date; ?>");
				});
			});
		})(jQuery);
		</script>
		<div id="wrap-<?php echo $item['id']; ?>">
			<div id="one-time-event-wrap-<?php echo $item['id']; ?>" style="display:none;" class="wrapping">
				<div id="one-time-event-<?php echo $item['id']; ?>" style="text-align:left;padding:5px;">
					<label for="one-time-date-<?php echo $item['id']; ?>"><?php _e('Date', $this->name); ?></label><br/>
					<input type="text" id="one-time-date-<?php echo $item['id']; ?>" class="datepicker" name="one-time-date">
				</div>
			</div>
			<?php
/*
			<div id="daily-event-wrap-<?php echo $item['id']; ?>" style="display:none;" class="wrapping">
				<div id="daily-event-<?php echo $item['id']; ?>-patterns" style="text-align:left;padding:5px;">
					<input id="daily-event-<?php echo $item['id']; ?>-pattern-1" type="radio" name="daily-pattern" value="specified" checked="checked"><?php _e('Every', $this->name); ?> <input type="number" id="daily-event-<?php echo $item['id']; ?>-spec-day" value="1" name="daily-pattern-specified" style="width:60px;"> <?php _e('day', $this->name); ?><br>
					<input id="daily-event-<?php echo $item['id']; ?>-pattern-2" type="radio" name="daily-pattern" value="every"><?php _e('Every weekday', $this->name); ?>
				</div>
			</div>
			<div id="weekly-event-wrap-<?php echo $item['id']; ?>" style="display:none;" class="wrapping">
				<div id="weekly-event-<?php echo $item['id']; ?>-weekdays" style="padding:5px;">
					<?php _e('Happens every', $this->name ); ?> <input id="weekly-event-<?php echo $item['id']; ?>-spec" type="number" min="1" value="1" name="weekly-pattern-specified" style="width: 60px;"> <?php _e('week on', $this->name ); ?>: </br>
					<div><input id="weekly-event-<?php echo $item['id']; ?>-monday" type="checkbox" name="weekday-<?php echo $item['id']; ?>" value="monday"><?php _e( 'Monday' ); ?>
					<input id="weekly-event-<?php echo $item['id']; ?>-tuesday" type="checkbox" name="weekday-<?php echo $item['id']; ?>" value="tuesday"><?php _e( 'Tuesday' ); ?></div>
					<div><input id="weekly-event-<?php echo $item['id']; ?>-wednesday" type="checkbox" name="weekday-<?php echo $item['id']; ?>" value="wednesday"><?php _e( 'Wednesday' ); ?>
					<input id="weekly-event-<?php echo $item['id']; ?>-thursday" type="checkbox" name="weekday-<?php echo $item['id']; ?>" value="thursday"><?php _e( 'Thursday' ); ?>
					<input id="weekly-event-<?php echo $item['id']; ?>-friday" type="checkbox" name="weekday-<?php echo $item['id']; ?>" value="friday"><?php _e( 'Friday' ); ?></div>
					<div><input id="weekly-event-<?php echo $item['id']; ?>-saturday" type="checkbox" name="weekday-<?php echo $item['id']; ?>" value="saturday"><?php _e( 'Saturday' ); ?>
					<input id="weekly-event-<?php echo $item['id']; ?>-sunday" type="checkbox" name="weekday-<?php echo $item['id']; ?>" value="sunday"><?php _e( 'Sunday' ); ?></div>
				</div>
				<?php $str = __('weekly', $this->name); ?>
			</div>
			<div id="monthly-event-wrap-<?php echo $item['id']; ?>" style="display:none;" class="wrapping">
				<div id="monthly-event-<?php echo $item['id']; ?>-patterns" style="padding:5px;">
					<input id="monthly-event-<?php echo $item['id']; ?>-pattern-1" type="radio" name="monthly-pattern" value="specified" checked="checked"><?php _e( 'Day', $this->name ); ?> <input id="monthly-event-<?php echo $item['id']; ?>-pattern-1-day" type="number" min="1" max="31" value="<?php echo $c_day; ?>" style="width: 60px;"> <?php _e('every', $this->name ); ?> <input id="monthly-event-<?php echo $item['id']; ?>-pattern-1-month" type="number" value="1" min="1" style="width: 60px;"> <?php _e('month', $this->name); ?>.<br/>
					<input id="monthly-event-<?php echo $item['id']; ?>-pattern-2" type="radio" name="monthly-pattern" value="specified-word"><?php _e( 'The', $this->name ); ?> 
					<select id="monthly-event-<?php echo $item['id']; ?>-pattern-2-select-num" name="monthly-select-num">
						<option value="first"><?php _e( 'first', $this->name ); ?></option>
						<option value="second"><?php _e( 'second', $this->name ); ?></option>
						<option value="third"><?php _e( 'third', $this->name ); ?></option>
						<option value="fourth"><?php _e( 'fourth', $this->name ); ?></option>
						<option value="fifth"><?php _e( 'fifth', $this->name ); ?></option>
					</select>
					<select id="monthly-event-<?php echo $item['id']; ?>-pattern-2-select-weekday" name="monthly-select-weekday">
						<option value="monday"><?php _e( 'Monday' ); ?></option>
						<option value="tuesday"><?php _e( 'Tuesday' ); ?></option>
						<option value="wednesday"><?php _e( 'Wednesday' ); ?></option>
						<option value="thursday"><?php _e( 'Thursday' ); ?></option>
						<option value="friday"><?php _e( 'Friday' ); ?></option>
						<option value="saturday"><?php _e( 'Saturday' ); ?></option>
						<option value="sunday"><?php _e( 'Sunday' ); ?></option>
					</select> <?php _e( 'every', $this->name ); ?> <input id="monthly-event-<?php echo $item['id']; ?>-pattern-2-month" type="number" value="1" min="1" style="width: 60px;"> <?php _e('month(s)', $this->name); ?>.
				</div>
				<?php $str = __('monthly', $this->name); ?>
			</div>
			<div id="yearly-event-wrap-<?php echo $item['id']; ?>" style="display:none;" class="wrapping">
				<div id="yearly-event-<?php echo $item['id']; ?>-patterns" style="padding:5px;">
					<?php _e('Happens every', $this->name); ?> <input id="yearly-event-<?php echo $item['id']; ?>-pattern-year" type="number" min="1" value="1" style="width:60px;"> <?php _e('year', $this->name); ?>.<br/>
					<input id="yearly-event-<?php echo $item['id']; ?>-pattern-1" type="radio" name="yearly-pattern" value="specified" checked="checked"><?php _e('Date', $this->name); ?>: <input id="yearly-event-<?php echo $item['id']; ?>-pattern-1-day" type="number" value="<?php echo $c_day; ?>" min="1" max="31" style="width:60px;"> 
					<select id="yearly-event-<?php echo $item['id']; ?>-pattern-1-month" name="pattern-1-month">
						<option value="01"<?php echo ( $c_month == "01" ) ? ' selected="selected"' : ''; ?>><?php _e('January'); ?></option>
						<option value="02"<?php echo ( $c_month == "02" ) ? ' selected="selected"' : ''; ?>><?php _e('February'); ?></option>
						<option value="03"<?php echo ( $c_month == "03" ) ? ' selected="selected"' : ''; ?>><?php _e('March'); ?></option>
						<option value="04"<?php echo ( $c_month == "04" ) ? ' selected="selected"' : ''; ?>><?php _e('April'); ?></option>
						<option value="05"<?php echo ( $c_month == "05" ) ? ' selected="selected"' : ''; ?>><?php _e('May'); ?></option>
						<option value="06"<?php echo ( $c_month == "06" ) ? ' selected="selected"' : ''; ?>><?php _e('June'); ?></option>
						<option value="07"<?php echo ( $c_month == "07" ) ? ' selected="selected"' : ''; ?>><?php _e('July'); ?></option>
						<option value="08"<?php echo ( $c_month == "08" ) ? ' selected="selected"' : ''; ?>><?php _e('August'); ?></option>
						<option value="09"<?php echo ( $c_month == "09" ) ? ' selected="selected"' : ''; ?>><?php _e('September'); ?></option>
						<option value="10"<?php echo ( $c_month == "10" ) ? ' selected="selected"' : ''; ?>><?php _e('October'); ?></option>
						<option value="11"<?php echo ( $c_month == "11" ) ? ' selected="selected"' : ''; ?>><?php _e('November'); ?></option>
						<option value="12"<?php echo ( $c_month == "12" ) ? ' selected="selected"' : ''; ?>><?php _e('December'); ?></option>
					</select><br/>
					<input id="yearly-event-<?php echo $item['id']; ?>-pattern-2" type="radio" name="yearly-pattern" value="every"><?php _e('Every', $this->name); ?>: 
					<select id="yearly-event-<?php echo $item['id']; ?>-pattern-2-select-num" name="yearly-select-num">
						<option value="first"><?php _e( 'first', $this->name ); ?></option>
						<option value="second"><?php _e( 'second', $this->name ); ?></option>
						<option value="third"><?php _e( 'third', $this->name ); ?></option>
						<option value="fourth"><?php _e( 'fourth', $this->name ); ?></option>
						<option value="fifth"><?php _e( 'fifth', $this->name ); ?></option>
					</select>
					<select id="yearly-event-<?php echo $item['id']; ?>-pattern-2-select-weekday" name="yearly-select-weekday">
						<option value="monday"><?php _e( 'Monday' ); ?></option>
						<option value="tuesday"><?php _e( 'Tuesday' ); ?></option>
						<option value="wednesday"><?php _e( 'Wednesday' ); ?></option>
						<option value="thursday"><?php _e( 'Thursday' ); ?></option>
						<option value="friday"><?php _e( 'Friday' ); ?></option>
						<option value="saturday"><?php _e( 'Saturday' ); ?></option>
						<option value="sunday"><?php _e( 'Sunday' ); ?></option>
					</select> <?php _e('in', $this->name); ?> 
					<select id="yearly-event-<?php echo $item['id']; ?>-pattern-2-month" name="pattern-2-month">
						<option value="01"<?php echo ( $c_month == "01" ) ? ' selected="selected"' : ''; ?>><?php _e('January'); ?></option>
						<option value="02"<?php echo ( $c_month == "02" ) ? ' selected="selected"' : ''; ?>><?php _e('February'); ?></option>
						<option value="03"<?php echo ( $c_month == "03" ) ? ' selected="selected"' : ''; ?>><?php _e('March'); ?></option>
						<option value="04"<?php echo ( $c_month == "04" ) ? ' selected="selected"' : ''; ?>><?php _e('April'); ?></option>
						<option value="05"<?php echo ( $c_month == "05" ) ? ' selected="selected"' : ''; ?>><?php _e('May'); ?></option>
						<option value="06"<?php echo ( $c_month == "06" ) ? ' selected="selected"' : ''; ?>><?php _e('June'); ?></option>
						<option value="07"<?php echo ( $c_month == "07" ) ? ' selected="selected"' : ''; ?>><?php _e('July'); ?></option>
						<option value="08"<?php echo ( $c_month == "08" ) ? ' selected="selected"' : ''; ?>><?php _e('August'); ?></option>
						<option value="09"<?php echo ( $c_month == "09" ) ? ' selected="selected"' : ''; ?>><?php _e('September'); ?></option>
						<option value="10"<?php echo ( $c_month == "10" ) ? ' selected="selected"' : ''; ?>><?php _e('October'); ?></option>
						<option value="11"<?php echo ( $c_month == "11" ) ? ' selected="selected"' : ''; ?>><?php _e('November'); ?></option>
						<option value="12"<?php echo ( $c_month == "12" ) ? ' selected="selected"' : ''; ?>><?php _e('December'); ?></option>
					</select>
				</div>
			</div>
			<hr/>
			<div id="event-range-wrap-<?php echo $item['id']; ?>" style="display:none;" class="wrapping">
				<div id="event-range-<?php echo $item['id']; ?>-from" style="padding:5px;text-align:right;">
					<label for="date-from-<?php echo $item['id']; ?>"><?php _e('From', $this->name); ?></label><br/>
					<input id="date-from-<?php echo $item['id']; ?>" name="date-from" class="datepicker">
					<input id="day-from-<?php echo $item['id']; ?>" name="day-from" value="<?php echo $p_day; ?>" size="2" maxlength="2" autocomplete="off" type="text">.
					<input id="month-from-<?php echo $item['id']; ?>" name="month-from" value="<?php echo $p_month; ?>" size="2" maxlength="2" autocomplete="off" type="text">.
					<input id="year-from-<?php echo $item['id']; ?>" name="year-from" value="<?php echo $p_year; ?>" size="4" maxlength="4" autocomplete="off" type="text">
				</div>
				<div id="event-range-<?php echo $item['id']; ?>-to" style="padding:5px;text-align:right;">
					<label for="date-to-<?php echo $item['id']; ?>"><?php _e('To', $this->name); ?></label><br/>
					<input id="date-to-<?php echo $item['id']; ?>" name="date-to" class="datepicker">
					<input id="day-to-<?php echo $item['id']; ?>" name="day-to" value="<?php echo $f_day; ?>" size="2" maxlength="2" autocomplete="off" type="text">.
					<input id="month-to-<?php echo $item['id']; ?>" name="month-to" value="<?php echo $f_month; ?>" size="2" maxlength="2" autocomplete="off" type="text">.
					<input id="year-to-<?php echo $item['id']; ?>" name="year-to" value="<?php echo $f_year; ?>" size="4" maxlength="4" autocomplete="off" type="text">
				</div>
				<hr/>
				<p style="font-size:10px;"><?php _e('The <span id="ident_str">daily</span> events can only span over a max of 12 events due to spam limitations.', $this->name); ?></p>
			</div>
*/
			?>
		</div>
		
		<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}
	public function generate_script_image_wrap($post, $item)
	{
		global $wp, $wpdb, $wp_query;
		$img = '';
		$table = $wpdb->prefix.'exxica_social_marketing';
		$id = $post->ID;

		if( isset($item['publish_image_url']) && strlen($item['publish_image_url']) !== 0 ) {
			// Publication has image
			$img = $item['publish_image_url'];
		} else {
			// Publication does not have image
			if ( function_exists('has_post_thumbnail') && has_post_thumbnail($id) ) {
				// Get the attached image as default
			 	$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'full' );
				if (!$thumbnail[0]) $img = false;
				else $img = $thumbnail[0];
			}
		}
		ob_start();
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
		<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}
	public function generate_script_time_wrap($post, $item)
	{

		$time_format = get_option( 'exxica_social_marketing_time_format', __( 'g:i A', $this->name ) );
		$twentyfour_clock_enabled = get_option( 'exxica_social_marketing_twentyfour_clock_enabled', '1' );

		$p_day = date('d', $item['publish_localtime']);
		$p_month = date('m', $item['publish_localtime']);
		$p_year = date('Y', $item['publish_localtime']);
		$p_hour = date( ( $twentyfour_clock_enabled ? 'H' : 'g'), $item['publish_localtime']);
		$p_minute = date('i', $item['publish_localtime']);
		$c_day = date('d', time() );
		$c_month = date('m', time() );
		$c_year = date('Y', time() );
		$f_day = date( 'd', $item['publish_localtime'] + 1209600 );
		$f_month = date( 'm', $item['publish_localtime'] + 1209600 );
		$f_year = date( 'Y', $item['publish_localtime'] + 1209600 );
		ob_start();
		?>
		<div>
			<label for="timewrap-<?php echo $item['id']; ?>"><?php _e('Time', $this->name); ?></label>
			<div id="timewrap-<?php echo $item['id']; ?>">
				<?php if($twentyfour_clock_enabled) : ?>
				<input id="hour-<?php echo $item['id']; ?>" name="hour" value="<?php echo $p_hour; ?>"  style="text-align:left;" size="2" maxlength="2" autocomplete="off" type="text">:
				<input id="minute-<?php echo $item['id']; ?>" name="minute" value="<?php echo $p_minute; ?>"  style="text-align:left;" size="2" maxlength="2" autocomplete="off" type="text">
				<?php else : ?>
				<input id="hour-<?php echo $item['id']; ?>" name="hour" value="<?php echo $p_hour; ?>"  style="text-align:left;" size="2" maxlength="2" autocomplete="off" type="text">:
				<input id="minute-<?php echo $item['id']; ?>" name="minute" value="<?php echo $p_minute; ?>"  style="text-align:left;" size="2" maxlength="2" autocomplete="off" type="text">
				<select name="ampm" id="ampm-<?php echo $item['id']; ?>" class="ampm exxica-select">
					<option value="am" <?php selected(date('a', $item['publish_localtime']), 'am'); ?>>AM</option>
					<option value="pm" <?php selected(date('a', $item['publish_localtime']), 'pm'); ?>>PM</option>
				</select>
				<?php endif; ?>
			</div>
		</div>
		<hr class="clear"/>
		<p style="font-size:0.8em;">
			<?php printf(__('Your scheduled publication might be delayed by up to 15 minutes due to limitations on our server. Once scheduled your publication will send even if %s is down.', $this->name), get_bloginfo( 'site_name' ) ); ?>
		</p>
		<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	public function generate_script_buttons($post, $item, $action)
	{
		ob_start();
		?>
		<?php if($action == 'create') : ?>
		<div style="display:table;width:100%;border-top:thin solid #ddd;border-bottom:thin solid #555;">
			<div style="display:table-row;width:100%;">
				<div style="display:table-cell;width:100%;height:30px;text-align:right;">
					<input style="position:relative;float:right;" id="submit-<?php echo $item['id']; ?>" type="submit" value="<?php _e( 'Save changes', $this->name ); ?>" class="button button-primary" id="submit" name="submit">
					<div id="save-changes-spinner" class="spinner">&nbsp;</div>
					<input style="margin-right:5px;position:relative;float:right;" id="reset-<?php echo $item['id']; ?>" class="button" type="submit" value="<?php _e( 'Discard changes', $this->name ); ?>" name="submit">
				</div>
			</div>
		</div>
		<?php endif; ?>
		<?php
		$out = ob_get_contents();
		ob_end_clean();
		//return $out;
		return false;
	}

	public function add_esm_edit($hook) 
	{
    	global $post, $wp, $wpdb;

    	if(is_null($post)) 
    		return false;
    			
    	$mainTable = $wpdb->prefix.'exxica_social_marketing';
    	$data = $wpdb->get_results("SELECT * FROM $mainTable WHERE post_id = $post->ID ORDER BY publish_localtime ASC");
		$d_ids = array();
		foreach( $data as $item ) {
			$d_ids[] = $item->id;
		}

		ob_start();
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

		<?php
		$out = ob_get_contents();
		ob_end_clean();
		echo $out;
	}
}