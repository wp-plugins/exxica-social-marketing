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

		include('partials/html-output/exxica-social-marketing-admin-html-new-publication.php');

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

		include('partials/html-output/exxica-social-marketing-admin-html-list.php');

		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}
	public function generate_script_actions($post, $item) 
	{
		ob_start();

		include('partials/html-output/exxica-social-marketing-admin-html-actions.php');

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

		include('partials/html-output/exxica-social-marketing-admin-html-list-row.php');

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

		include('partials/html-output/exxica-social-marketing-admin-html-publication-readonly.php');

		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}
	public function generate_script_publication_general($post, $item, $action) 
	{
		global $wpdb, $current_user;
		get_currentuserinfo();
		$exxica_user_name = get_option('exxica_social_marketing_account_'.$current_user->user_login);

		$post_publish_time = strtotime($post->post_date);
		$date_format = get_option( 'exxica_social_marketing_date_format', __( 'm/d/Y', $this->name ) );

		// Check if post publish time is after publish_localtime
		if($action == 'create') {
			if($item['publish_localtime'] <= $post_publish_time) $item['publish_localtime'] = $post_publish_time+(60*30);
		}

	    $accTable = $wpdb->prefix.'exxica_social_marketing_accounts';
		$channels = array( 'Facebook', 'Twitter');//, 'LinkedIn', 'Google', 'Instagram', 'Flickr' );
    	$accounts = $wpdb->get_results("SELECT * FROM $accTable WHERE exx_account = '".$exxica_user_name."'", ARRAY_A);
    	$original_data = array( 'post' => $post, 'item' => $item );

		ob_start();

		include('partials/html-output/exxica-social-marketing-admin-html-publication-general.php');

		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}
	public function generate_script_publication($post, $item) 
	{
		$date_format = get_option( 'exxica_social_marketing_date_format', __( 'm/d/Y', $this->name ) );
		$time_format = get_option( 'exxica_social_marketing_time_format', __( 'g:i A', $this->name ) );

		$d = new DateTime( date($date_format.' '.$time_format,$item['publish_localtime']) );
		$n = ($d->getTimestamp() + $d->getOffset());

		ob_start();

		include('partials/html-output/exxica-social-marketing-admin-html-publication.php');

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

		include('partials/html-output/exxica-social-marketing-admin-html-wrap-channel.php');

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

		include('partials/html-output/exxica-social-marketing-admin-html-wrap-account.php');

		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}
	public function generate_script_text_wrap($post, $item)
	{
		ob_start();

		include('partials/html-output/exxica-social-marketing-admin-html-wrap-text.php');

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

		include('partials/html-output/exxica-social-marketing-admin-html-wrap-pattern.php');

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

		include('partials/html-output/exxica-social-marketing-admin-html-wrap-image.php');

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

		include('partials/html-output/exxica-social-marketing-admin-html-wrap-time.php');

		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}
	public function generate_script_buttons($post, $item, $action)
	{
		ob_start();

		include('partials/html-output/exxica-social-marketing-admin-html-buttons.php');

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

		include('partials/html-output/exxica-social-marketing-admin-html-modal.php');

		$out = ob_get_contents();
		ob_end_clean();
		echo $out;
	}
}