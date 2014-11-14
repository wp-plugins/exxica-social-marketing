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
 * @subpackage Exxica_Social_Marketing/admin
 * @author     Gaute Rønningen <gaute@exxica.com>
 */
class Exxica_Social_Marketing_Admin 
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

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() 
	{
		wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) . 'css/exxica-social-marketing-admin.css', array(), $this->version );
		wp_enqueue_style( $this->name.'-jquery-ui', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css', array(), $this->version );
	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook) 
	{

		wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) . 'js/exxica-social-marketing-admin.js', array( 'jquery' ), $this->version, FALSE );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-button' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-ui-spinner' );
		wp_enqueue_script( $this->name . '-jquery-ui-datepicker-localization-no', plugins_url( 'js/datepicker-no.js', __FILE__ ), array( 'jquery-ui-datepicker' ), $this->version );

		if( $hook === 'users_page_exxica-sm-settings' ) 
			wp_enqueue_script( $this->name . '-settings-script', plugins_url( 'js/settings-page-social-marketing.js', __FILE__ ), array( 'jquery' ), $this->version );
		
		wp_enqueue_script( $this->name . '-jquery-validate', plugins_url( 'js/jquery.validate.min.js', __FILE__ ), array( 'jquery' ), $this->version );

		// AJAX setup
		wp_localize_script( $this->name, 'PostHandlerAjax', array(
			    'nonce'				=> 		wp_create_nonce( 'postdataajax-nonce' ),
		    )
		);
		wp_localize_script( $this->name, 'PostHandlerAjax_Create', array(
			    'ajaxurl'          	=> 		admin_url('admin-ajax.php?action=create_post_data'),
			    'nonce'				=> 		wp_create_nonce( 'postdataajax-nonce' ),
		    )
		);
		wp_localize_script( $this->name, 'PostHandlerAjax_Update', array(
			    'ajaxurl'          	=> 		admin_url('admin-ajax.php?action=update_post_data'),
			    'nonce'				=> 		wp_create_nonce( 'postdataajax-nonce' ),
		    )
		);
		wp_localize_script( $this->name, 'PostHandlerAjax_Destroy', array(
			    'ajaxurl'          	=> 		admin_url('admin-ajax.php?action=destroy_post_data'),
			    'nonce'				=> 		wp_create_nonce( 'postdataajax-nonce' ),
		    )
		);
		wp_localize_script( $this->name, 'ChannelHandlerAjax_Insert', array(
			    'ajaxurl'          	=> 		admin_url('admin-ajax.php?action=create_channel_data'),
			    'nonce'				=> 		wp_create_nonce( 'dbhandlerajax-nonce' ),
		    )
		);
		wp_localize_script( $this->name, 'ChannelHandlerAjax_Destroy', array(
			    'ajaxurl'          	=> 		admin_url('admin-ajax.php?action=destroy_channel_data'),
			    'nonce'				=> 		wp_create_nonce( 'dbhandlerajax-nonce' ),
		    )
		);
		wp_localize_script( $this->name, 'FactoryResetAjax', array(
			    'ajaxurl'          	=> 		admin_url('admin-ajax.php?action=factory_reset'),
			    'nonce'				=> 		wp_create_nonce( 'factoryreset-nonce' ),
		    )
		);
		wp_localize_script( $this->name, 'processAjax', array(
			    'ajaxurl'          	=> 		admin_url('admin-ajax.php?action=save_license_data'),
			    'nonce'				=> 		wp_create_nonce( 'processajax-nonce' ),
		    )
		);

		wp_localize_script( $this->name, 'exxicaVerifyAjax', array(
			    'ajaxurl'          	=> 		'http://api.exxica.com/publisher/exxica/verify',
			    'nonce'				=> 		wp_create_nonce( 'exxicaverifyajax-nonce' ),
		    )
		);
		wp_localize_script( $this->name, 'exxicaSyncAjax', array(
			    'ajaxurl'          	=> 		'http://api.exxica.com/publisher/exxica/sync',
			    'nonce'				=> 		wp_create_nonce( 'exxicasyncajax-nonce' ),
		    )
		);

		wp_localize_script( $this->name, 'facebookLoginAjax', array(
			    'ajaxurl'          	=> 		'http://api.exxica.com/publisher/facebook/login',
			    'nonce'				=> 		wp_create_nonce( 'facebookloginajax-nonce' ),
		    )
		);
		wp_localize_script( $this->name, 'twitterLoginAjax', array(
			    'ajaxurl'          	=> 		'http://api.exxica.com/publisher/twitter/login',
			    'nonce'				=> 		wp_create_nonce( 'twitterloginajax-nonce' ),
		    )
		);
		wp_localize_script( $this->name, 'Language', array(
				'days_ago'			=>		__(' days ago', $this->name),
				'expires_in'		=>		__('in about ', $this->name),
				'days'				=>		__(' days', $this->name)
		    )
		);
	}

	public function create_nav_menu() 
	{
		$capability = 'read';
		$page_title = __('My social marketing', $this->name);
		$menu_title = __('My social marketing', $this->name);
		$menu_slug = 'exxica-sm-settings';
		$function = array($this, 'display_esm_user_settings_page');

		add_users_page( $page_title, $menu_title, $capability, $menu_slug, $function );

		$capability = 'manage_options';
		$page_title = __('Social marketing', $this->name);
		$menu_title = __('Social marketing', $this->name);
		$menu_slug = 'exxica-sm-system-settings';
		$function = array($this, 'display_esm_system_settings_page');

		add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function );

		$capability = 'read';
		$page_title = __('Exxica Social Marketing Overview', $this->name);
		$menu_title = __('Marketing Overview', $this->name);
		$menu_slug = 'exxica-sm-overview';
		$function = array($this, 'display_esm_overview_page');

		add_posts_page( $page_title, $menu_title, $capability, $menu_slug, $function );
	}	

	private function daysDiff($d1,$d2) {
		return floor((max($d1,$d2)-min($d1,$d2))/86400);
	}

	public function display_esm_user_settings_page()
	{
		global $wp, $wpdb, $current_user;
		get_currentuserinfo();

		$license_types = array(
			'Basic' => __('Free Trial', $this->name),
			'Premium' => __('Paid Subscription', $this->name),
			'Lifetime' => __('Lifetime Subscription', $this->name)
		);

		$account_type_src = get_option( 'exxica_social_marketing_account_type_'.$current_user->user_login );
		$account_type_translated = $license_types[$account_type_src];
		$account_type = $account_type_translated;

		$license_expiry_epoch = get_option( 'exxica_social_marketing_expiry_'.$current_user->user_login );
		$daysdiff = $this->daysDiff( (int)$license_expiry_epoch, time() );
		if($license_expiry_epoch <= time()) {
			$license_expiry_text = sprintf(__('%s days ago', $this->name), $daysdiff );
		} else {
			$license_expiry_text = sprintf(__('in %s days', $this->name), $daysdiff );
		}
		

		$lic_ok = false;
		$locale = $this->name;
		$origin = get_option( 'exxica_social_marketing_referer' );
		$un = get_option( 'exxica_social_marketing_account_'.$current_user->user_login, $origin.'/'.$current_user->user_login );
		$usermail = $current_user->user_email;
		$key = get_option( 'exxica_social_marketing_api_key_'.$current_user->user_login );
		$time = get_option( 'exxica_social_marketing_api_key_created_'.$current_user->user_login, time() ); 

		$secret = md5( $key . '+' . $time );
		$accTable = $wpdb->prefix . 'exxica_social_marketing_accounts';
		$facebook_accounts = $wpdb->get_results( "SELECT ID, channel_account AS name, expiry_date FROM $accTable WHERE channel = 'Facebook' AND exx_account = '$un'", ARRAY_A );
		$twitter_accounts = $wpdb->get_results( "SELECT ID, channel_account AS name, expiry_date FROM $accTable WHERE channel = 'Twitter' AND exx_account = '$un'", ARRAY_A );
		$linkedin_accounts = $wpdb->get_results( "SELECT ID, channel_account AS name, expiry_date FROM $accTable WHERE channel = 'LinkedIn' AND exx_account = '$un'", ARRAY_A );
		$google_accounts = $wpdb->get_results( "SELECT ID, channel_account AS name, expiry_date FROM $accTable WHERE channel = 'Google' AND exx_account = '$un'", ARRAY_A );
		$instagram_accounts = $wpdb->get_results( "SELECT ID, channel_account AS name, expiry_date FROM $accTable WHERE channel = 'Instagram' AND exx_account = '$un'", ARRAY_A );
		$flickr_accounts = $wpdb->get_results( "SELECT ID, channel_account AS name, expiry_date FROM $accTable WHERE channel = 'Flickr' AND exx_account = '$un'", ARRAY_A );

		include_once('partials/exxica-social-marketing-admin-display.php');
	}

	public function display_esm_system_settings_page()
	{
		global $wp, $wpdb;

		if(isset($_POST['_wpnonce'])) {
			if( $_POST['_wpnonce'] == wp_create_nonce('systemsettings') ) {
				// Save settings
				update_option( 'exxica_social_marketing_date_format', $_POST['date_format_custom']);
				update_option( 'exxica_social_marketing_time_format', $_POST['time_format_custom']);
				update_option( 'exxica_social_marketing_twentyfour_clock_enabled', $_POST['twentyfour_hour_clock']);
			}
		}

		$date_format = get_option( 'exxica_social_marketing_date_format', __( 'm/d/Y', $this->name ) );
		$time_format = get_option( 'exxica_social_marketing_time_format', __( 'g:i A', $this->name ) );
		$twentyfour_clock_enabled = get_option( 'exxica_social_marketing_twentyfour_clock_enabled', '1' );

		include_once('partials/exxica-social-marketing-admin-settings.php');
	}

	public function display_esm_overview_page()
	{
		// Set globals
		global $wp, $wpdb;

		$date_format = get_option( 'exxica_social_marketing_date_format', __( 'm/d/Y', $this->name ) );
		$time_format = get_option( 'exxica_social_marketing_time_format', __( 'g:i A', $this->name ) );
		$twentyfour_clock_enabled = get_option( 'exxica_social_marketing_twentyfour_clock_enabled', '1' );

		// Set locals
		$table = $wpdb->prefix.'exxica_social_marketing';
		$status_table = $wpdb->prefix.'exxica_social_marketing_statuses';
		$sources = array(
			'post' => __('Post', $this->name),
			'page' => __('Page', $this->name),
			'landing' => __('Landing Page', $this->name),
			'system_page' => __('System Page', $this->name)
		);

		$page = (isset($_REQUEST['page_num'])) ? (int)$_REQUEST['page_num'] : 0;
		$limit = 10;
		$offset = $page * 10;

		// Run update
		$status = new Exxica_Social_Marketing_Status_Update( $this->name, $this->version );

		$response = json_decode( trim( $status->getUpdatedStatuses() ) );

		if( ! isset( $response->error ) ) {
			$item = $response->data;

			$decoded_response = json_decode( trim( $item->sent_response ) );
			$decoded_response = json_decode($decoded_response->return);
			
			$exists = $wpdb->get_row("SELECT id FROM $status_table WHERE marketing_id=".$item->id);
			if(!is_null($exists)) {
				// Update
				$wpdb->replace( 
					$status_table, 
					array( 
						'id' => $exists->id,
						'marketing_id' => $item->id,
						'status' => 1,
						'message' => $decoded_response->error->message
					), 
					array( 
						'%d', 
						'%d', 
						'%d',
						'%s'
					) 
				);
			} else {
				// Create
				$wpdb->insert( 
					$status_table, 
					array( 
						'marketing_id' => $item->id,
						'status' => 1,
						'message' => $decoded_response->error->message
					), 
					array( 
						'%d', 
						'%d',
						'%s'
					) 
				);
			}
		}


		// Fetch updated data from DB
		if(isset($_REQUEST['smtype']) && $_REQUEST['smtype'] == 'log') {
			$sql = "SELECT $table.*, $status_table.`message`, $status_table.`status` FROM $table LEFT JOIN $status_table ON ($table.`id` = $status_table.`marketing_id`) WHERE publish_localtime < ".time()." ORDER BY publish_localtime DESC LIMIT ".$limit." OFFSET ".$offset;
		} else {
			$sql = "SELECT $table.*, $status_table.`message`, $status_table.`status` FROM $table LEFT JOIN $status_table ON ($table.`id` = $status_table.`marketing_id`) WHERE publish_localtime >= ".strtotime('-1 day')." ORDER BY publish_localtime ASC LIMIT ".$limit." OFFSET ".$offset;
		}

		$all_items = $wpdb->get_results( "SELECT * FROM $table WHERE publish_localtime >= ".time() , ARRAY_A);
		$sm_items = $wpdb->get_results( $sql , ARRAY_A);

		$shown_page = $page + 1;
		$last_page = ceil(count($all_items)/$limit);


		include_once('partials/exxica-social-marketing-admin-overview.php');
	}

	public function create_admin_dashboard_widget()
	{
		global $wp_meta_boxes;

		wp_add_dashboard_widget('esm_dashboard_widget', __('Exxica Social Marketing',$this->name), array($this, 'display_dashboard_widget') );
	}

	public function display_dashboard_widget() 
	{
		global $wp, $wpdb;
		
		$table = $wpdb->prefix.'exxica_social_marketing';
		$five_expired_items = $wpdb->get_results( "SELECT * FROM $table WHERE publish_localtime < ".time()." ORDER BY publish_localtime DESC LIMIT 5", ARRAY_A);
		$publishing_today = $wpdb->get_results( "SELECT * FROM $table WHERE publish_localtime >= ".time()." AND publish_localtime < ".strtotime('+1 day')." ORDER BY publish_localtime ASC LIMIT 5", ARRAY_A);

		include_once('partials/exxica-social-marketing-admin-dashboard-widget.php');
	}

	public function set_default_values()
	{
		global $wp, $wpdb, $current_user;
		get_currentuserinfo();
    	$str = preg_replace( '/^www\./' , '' , get_site_url() );
    	$str = preg_replace('#^https?://#', '', $str );

		update_option( 'exxica_social_marketing_account_'.$current_user->user_login, $str.'/'.$current_user->user_login );


		if( ! get_option( 'exxica_social_marketing_account_type_'.$current_user->user_login ) ) {
			update_option('exxica_social_marketing_account_type_'.$current_user->user_login, 'Basic');
		}
		if( ! get_option( 'exxica_social_marketing_expiry_'.$current_user->user_login ) ) {
			update_option('exxica_social_marketing_expiry_'.$current_user->user_login, strtotime("+45 days") );
		}
		if( ! get_option( 'exxica_social_marketing_referer') ) {
		    update_option( 'exxica_social_marketing_referer', $str );
		}
		if( ! get_option( 'exxica_social_marketing_api_key_'.$current_user->user_login ) ) {
			update_option('exxica_social_marketing_api_key_'.$current_user->user_login, md5(get_option( 'exxica_social_marketing_account' ) . get_option( 'exxica_social_marketing_referer' ) . get_option( 'exxica_social_marketing_api_key_created' ) ));
			update_option('exxica_social_marketing_api_key_created_'.$current_user->user_login, time() );
		}
		if( ! get_option('exxica_social_marketing_version') ) {
			update_option('exxica_social_marketing_version', $this->version);
		}
		if( ! get_option( 'exxica_social_marketing_show_channel_facebook_'.$current_user->user_login ) ) {
			update_option('exxica_social_marketing_show_channel_facebook_'.$current_user->user_login, 1 );
			update_option('exxica_social_marketing_show_channel_twitter_'.$current_user->user_login, 0);
			update_option('exxica_social_marketing_show_channel_linkedin_'.$current_user->user_login, 0);
			update_option('exxica_social_marketing_show_channel_google_'.$current_user->user_login, 0);
			update_option('exxica_social_marketing_show_channel_instagram_'.$current_user->user_login, 0);
			update_option('exxica_social_marketing_show_channel_flickr_'.$current_user->user_login, 0);
		}
	}

	public function exxica_help($hook)
	{
		$d = array();
		if( "users_page_exxica-sm-settings" == $hook) {
			$d = array(
				array(
					'id' => 'esm-help-info',	
					'title' => __('Information', $this->name ),
					'content' => $this->help_text('information')
				),
				array(
					'id' => 'esm-help-subscription',	
					'title' => __('Subscription', $this->name ),
					'content' => $this->help_text('subscription')
				),
				array(
					'id' => 'esm-help-advanced',	
					'title' => __('Advanced', $this->name ),
					'content' => $this->help_text('advanced')
				)
			);

		} else if( "posts_page_exxica-social-marketing-overview" == $hook ) {
			// TODO
		}

		$screen = get_current_screen();

		foreach($d as $item) {
			$screen->add_help_tab( array( 'id' => $item['id'], 'title' => $item['title'], 'content' => $item['content'] ) );
		}			
	}

	private function help_text($type)
	{ 
		$sub_cancel_url = "http://www.exxica.com/";
		$sub_cancel_text = "exxica.com";
		$sub_cancel_mail_url = "mailto:post@exxica.com?subject=".__('Cancel Exxica Social Marketing Subsription',$this->name);
		$sub_cancel_mail_text = "post@exxica.com";

		ob_start();
		?>
		<?php if($type == "information") : ?>
		<h2><?php _e('Information', $this->name); ?></h2>
		<ul>
			<li><?php _e('The Exxica username is getting generated automatically and is only shown as information.', $this->name); ?></li>
			<li><?php _e('If Exxica Social Marketing are to work properly, at least one account must be authorized through the Exxica server. The accounts will be used by the current user to publish publications to their respective pages.<br/>To start this process you must click the authorization buttons below.', $this->name); ?>
			<li><?php _e('The authorized accounts are only available to the current user. Other users will have to authorize with their own accounts.', $this->name); ?></li>
			<li><?php _e('Authorized accounts can be removed from your server. And if they are removed in error, you can re-syncronize your accounts by pressing "Update" atop the account list.', $this->name); ?></li>
			<li><?php _e('Authorized accounts will have to be renewed every 30 days. This is security precaution.'); ?></li>
		</ul>
		<h2><?php _e('Disclaimer', $this->name); ?></h2>
		<p><?php _e('Exxica AS disclaims all responsibility and all liability (including through negligence) for all expenses, losses, damages and costs you might incur as a result of the use of Exxica Social Marketing Scheduler.', $this->name); ?></p>
		<?php elseif($type == "subscription") : ?>
		<h2><?php _e('Cancelling subscription', $this->name); ?></h2>
		<p><?php printf(__('If you want to cancel your subscription, please go to <a href="%s" target="_blank" alt="%s">%s</a> or send us an e-mail at <a href="%s" target="_blank">%s</a>.',$this->name),
			$sub_cancel_url,$sub_cancel_text,$sub_cancel_text,$sub_cancel_mail_url,$sub_cancel_mail_text
		); ?></p>
		<?php elseif($type == "advanced") : ?>
		<h2><?php _e('Flush data', $this->name); ?></h2>
		<p><?php _e('This will flush all datas from your Exxica Social Marketing tables and re-install the tables with default values, use this <strong>ONLY</strong> as a last resort.', $this->name); ?></p>
		<a href="#" id="reinstall" class="button-secondary"><?php _e('Re-install tables', $this->name); ?></a>
		<?php endif; ?>
		<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	public function add_custom_media_buttons($context)
	{
		$color = '#555';
		$menu_text = __('Social Marketing', $this->name);
		ob_start();
		?>
		<style>
		#esm-modal {
			position: fixed;
			top: 32px;
			left: 30px;
			right: 30px;
			bottom: 30px;
			z-index: 10000;
		}
		</style>
		<script type="text/javascript">
			(function ( $ ) {
				"use strict";
				$(function () {
					$(document).ready(function() {
						$('#esm-button').click(function(e) {
							$('#esm-modal').show();
						});
					});
				});
			})(jQuery);
		</script>
		<a id="esm-button" class="button button-secondary" href="#"><span style="margin:4px 4px 0 0;color:<?php echo $color;?>;" class="dashicons dashicons-share-alt2"></span><?php echo $menu_text; ?></a>
		<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $context.$out;
	}	
}