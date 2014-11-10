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
class Exxica_Social_Marketing_Status_Update
{
    protected $esm_table;
    protected $esm_account;
    protected $esm_origin;
    protected $api_key;
    protected $api_key_created;

    protected $error;

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
        global $wpdb, $wp_query, $current_user;
		get_currentuserinfo();

		$this->name = $name;
		$this->version = $version;

        // System variables
        $this->esm_table = $wpdb->prefix.'exxica_social_marketing';
        $this->esm_origin = get_option('exxica_social_marketing_referer');
        $this->esm_account = get_option( 'exxica_social_marketing_account_'.$current_user->user_login, $this->esm_origin.'/'.$current_user->user_login );
        $this->api_key = get_option('exxica_social_marketing_api_key_'.$current_user->user_login);
        $this->api_key_created = get_option('exxica_social_marketing_api_key_created_'.$current_user->user_login);
        
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function getNonce()
    {
        return $this->wpnonce;
    }

    public function getAction()
    {
        return $this->action;
    }

    private function hasErrors()
    {
        return (is_null($this->error)) ? false : true;
    }

    public function getErrors()
    {
        if($this->hasErrors())
        {
            return $this->error;
        }
        else
        {
            return false;
        }
    }

    private function generateApiSecret( $key, $time )
    {
        return (string) md5( (string) $key .'+'. (int) $time );
    }

    public function getUpdatedStatuses( )
    {
    	$_time = time();
        $_secret = $this->generateApiSecret( $this->api_key, $_time );
        
        $data[] = array(
            'unixtime' => (int)$_time,
            'api' => array(
                'action' => sanitize_text_field('status_update'),
                '_secret' => sanitize_text_field($_secret)
            )
        );

        $atts = array(
            'client' => array(
                'username' => (string)$this->esm_account,
                'origin' => (string)$this->esm_origin,
                'api' => array(
                    '_key' => (string)$this->api_key,
                    '_created' => (int)$this->api_key_created
                )
            ),
            'data' => $data
        );

        $to = 'http://api.exxica.com/publisher/exxica/status';
        $response = $this->postData($to, $atts);
        return $response;
    }

    private function postData( $to, $atts )
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json") );
            curl_setopt($ch, CURLOPT_TIMEOUT, 30 );
            curl_setopt($ch, CURLOPT_URL, $to);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE );
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2 );
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2 );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE );
            curl_setopt($ch, CURLOPT_POST, TRUE );
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query( $atts ) );
            $out = curl_exec($ch);
            curl_close($ch);
        } catch( Exception $ex ) {
            $out = array('success' => false, 'error' => array( 'code' => $ex->getCode(), 'message' => $ex->getMessage(), 'type' => 'CurlException') );
        }

        return $out;
    }
}