<?php 
class Exxica_Db_Handler
{
	protected $esm_account;
	protected $esm_origin;
	protected $esm_accounts_table;

	protected $input;
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
    public function __construct( $name, $version, $post_data ) 
    {
      global $wpdb, $wp_query, $current_user;

      $this->name = $name;
      $this->version = $version;
      $this->input = $post_data;
  		get_currentuserinfo();

  		$this->esm_origin = get_option('exxica_social_marketing_referer');
      $this->esm_account = get_option( 'exxica_social_marketing_account_'.$current_user->user_login, $this->esm_origin.'/'.$current_user->user_login );
  		$this->esm_accounts_table = $wpdb->prefix.'exxica_social_marketing_accounts';
  	}

	public function inputHasError()
	{
		return isset($this->input['error']['code']) ? true : false;
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

	public function getInput()
	{
		return $this->input;
	}

	public function deleteChannelData()
	{
		global $wpdb, $wp_query; 
		$table = $this->esm_accounts_table;

		try {
			return $wpdb->query( $wpdb->prepare( 
				"
					DELETE FROM $table WHERE id=%d;
				", 
			    array(
					(int)$this->input['id']
				) 
			) );
		} catch( Exception $ex ) {
			$this->error = array('code' => $ex->getCode(), 'message' => $ex->getMessage(), 'type' => 'Exception');
			return false;
		}
	}

	public function insertChannelData( ) 
	{
		global $wpdb, $wp_query, $current_user;
		$response = array();
    $post = $this->input;

		get_currentuserinfo();
		$table = $this->esm_accounts_table;
		$exxica_user_name = get_option( 'exxica_social_marketing_account_'.$current_user->user_login );

	    foreach( $post['data']['xhr'] as $item ) {
	    	if( $item['chan'] == 'Facebook' ) {
			    $response[] = array( 'channel'=> $item['chan'], 'channel_account' => $item['name'], 'fb_page_id' => $item['id'] );
		    	$existing = $wpdb->get_row( sprintf("SELECT id FROM $table WHERE fb_page_id = '%s' AND exx_account = '%s' AND channel = '%s'", $item['id'], $exxica_user_name, $item['chan']) );

		    	if( is_null( $existing ) ) {
		    		// Record does not exist, creates new
			        try {
			        	$wpdb->query( $wpdb->prepare( 
			        		"
			        		INSERT INTO $table(exx_account, channel, channel_account, fb_page_id, expiry_date)
			        		 VALUES( %s, %s, %s, %d, %d )
			           		"
			        		, array(
			        			(string)$exxica_user_name,
			        			(string)$item['chan'],
			        			(string)$item['name'],
			        			(int)$item['id'],
                    (int)strtotime("+1 month")
			        		) 
			        	) );
			        } catch(Exception $ex) {
			        	$this->error = array('code' => $ex->getCode(), 'message' => $ex->getMessage(), 'type' => 'Exception');
			        	$response = false;
			        } 
			    } else {
			    	// Record exists, updates it
			    	try {
			    		$wpdb->query( $wpdb->prepare( 
			        		"
			        		UPDATE $table
			        		 SET exx_account=%s, channel=%s, channel_account=%s, fb_page_id=%d, expiry_date=%d WHERE id=%d
			           		"
			        		, array(
			        			(string)$exxica_user_name,
			        			(string)$item['chan'],
			        			(string)$item['name'],
                    (int)$item['id'],
                    (int)strtotime("+1 month"),
			        			$existing->id
			        		) 
			        	) );
				    } catch(Exception $ex) {
				    	$this->error = array('code' => $ex->getCode(), 'message' => $ex->getMessage(), 'type' => 'Exception');
			        	$response = false;
			        } 
			    }
			} elseif( $item['chan'] == 'Twitter' ) {
          // Don't register if name is blank (occurs on first registration due to temp login variables)
          if($item['name'] !== '') { 
  	    		update_option('exxica_social_marketing_show_channel_twitter_'.$current_user->user_login, 1);
  			    $response[] = array( 'channel'=> $item['chan'], 'channel_account' => $item['name'] );
  		    	$existing = $wpdb->get_row( sprintf("SELECT id FROM $this->esm_accounts_table WHERE channel_account = '%s' AND channel = '%s'", $item['name'], $item['chan']) );

  		    	if( is_null( $existing ) ) {
  		    		// Record does not exist, creates new
  			        try {
  			        	$wpdb->query( $wpdb->prepare( 
  			        		"
  			        		INSERT INTO $table(exx_account, channel, channel_account, fb_page_id, expiry_date)
  			        		 VALUES( %s, %s, %s, %d, %d )
  			           		"
  			        		, array(
  			        			(string)$exxica_user_name,
  			        			(string)$item['chan'],
  			        			(string)$item['name'],
                      (int)$item['id'],
  			        			(int)strtotime("+1 month")
  			        		) 
  			        	) );
  			        } catch(Exception $ex) {
  			        	$this->error = array('code' => $ex->getCode(), 'message' => $ex->getMessage(), 'type' => 'Exception');
  			        	$response = false;
  			        } 
  			    } else {
  			    	// No action if it exists
  			    }
          }
		    }
	    }
	    return $response;
	}
}

class Exxica_Process_Handler
{
	protected $data;
	protected $return;
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
    public function __construct( $name, $version, $post_data ) 
    {
      $this->name = $name;
      $this->version = $version;
      $this->data = $post_data;

  		$this->return = array();
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

	public function isSuccessful()
	{
		return $this->data['success'];
	}

	private function checkKeyPairs( $_key, $_created, $_secret )
	{
		return (md5($_key.'+'.$_created) == $_secret) ? true : false;
	}

	private function getApi()
	{
		return $this->data['api'];
	}

	public function process()
	{
    global $wp, $wpdb, $current_user;

		if( $this->isSuccessful() )
		{
      get_currentuserinfo();
			$api = $this->getApi();
			if( $this->checkKeyPairs( $api['api_key'], $api['api_key_created'], $api['api_secret'] ) )
			{
				update_option('exxica_social_marketing_api_key_'.$current_user->user_login, $api['api_key'] );
				update_option('exxica_social_marketing_api_key_created_'.$current_user->user_login, $api['api_key_created'] );
        update_option(
            'exxica_social_marketing_account_type_'.$current_user->user_login, 
            isset($api['account_type']) ? $api['account_type'] : 'Basic' );
        update_option(
            'exxica_social_marketing_expiry_'.$current_user->user_login, 
            isset($api['expires']) ? $api['expires'] : strtotime("-1 day") );

				return array( 
          'success' => true, 
          'api_key' => (string)$api['api_key'], 
          'api_secret' => (string)$api['api_secret'], 
          'api_key_created' => (int)$api['api_key_created'], 
          'username' => (string)$api['username'],
          'usermail' => (string)$api['usermail'],
          'account_type' => isset($api['account_type']) ? $api['account_type'] : 'Basic',
          'license_expires' => isset($api['expires']) ? $api['expires'] : strtotime("-1 day")
        );
			}
			else
			{
				$this->error = array( 'code' => 1, 'message' => 'API Key Pair mismatch.', 'type' => 'ApiSecretException' );
				return array( 'success' => false, 'error' => $this->error );
			}
		}
		else
		{
			$this->error = $this->data['error'];
			return array( 'success' => false, 'error' => $this->error );
		}
	}
}

class Exxica_Overview_Handler
{
    protected $esm_table;
    protected $esm_account;
    protected $user_email;
    protected $esm_origin;
    protected $locale;

    protected $api_key;
    protected $api_key_created;

    protected $error;

    protected $wpnonce;
    protected $text;    

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
        global $wpdb, $wp_query, $current_user;
        get_currentuserinfo();

        // Post variables
        $this->wpnonce = isset($_REQUEST['_wpnonce']) ? $_REQUEST['_wpnonce'] : false;
        $this->text = isset($_REQUEST['text']) ? $_REQUEST['text'] : false;

        // System variables
        $this->locale = 'exxica-social-marketing';
        $this->esm_table = $wpdb->prefix.'exxica_social_marketing';
        $this->esm_origin = get_option('exxica_social_marketing_referer');
        $this->esm_account = get_option( 'exxica_social_marketing_account_'.$current_user->user_login, $this->esm_origin.'/'.$current_user->user_login );
        $this->api_key = get_option('exxica_social_marketing_api_key_'.$current_user->user_login, '9dad8134bb1f1c986af6036f287ee03c');
        $this->api_key_created = get_option('exxica_social_marketing_api_key_created_'.$current_user->user_login, 1403097585);
        
        $this->user_email = $current_user->user_email;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function getNonce()
    {
        return $this->wpnonce;
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

    public function getTime($stamp)
    {
        return array('unix'=>date('U', $stamp),'atom'=>date(DATE_ATOM, $stamp));
    }

    public function updateLocalData( $input = array() ) 
    {
        global $wpdb, $wp_query;

        foreach( $input['data'] as $item ) {
            $data = array(
                'post_id' => (int)$item['post_id'],
                'exx_account' => sanitize_text_field($this->esm_account),
                'publish_localtime' => (int)$item['localtime'],
                'publish_unixtime' => (int)$item['unixtime'],
                'description' => sanitize_text_field($item['description'])
            );

            try {
                $wpdb->query( $wpdb->prepare( 
                    "
                    UPDATE $this->esm_table
                     SET
                      post_id = %d,
                      exx_account = %s,
                      publish_localtime = %d,
                      publish_unixtime = %d,
                      publish_description = %s
                     WHERE id = %d
                    "
                    , array(
                        $data['post_id'], 
                        $data['exx_account'], 
                        $data['publish_localtime'],
                        $data['publish_unixtime'], 
                        $data['description'],
                        $item['item_id']
                    ) 
                ) );
                return true;
            } catch(Exception $e) {
                return false;
            }
        }
    }

    public function removeLocalData( $input = array() ) 
    {
        global $wpdb, $wp_query;

        try {
            $wpdb->query( $wpdb->prepare( 
                "
                DELETE FROM $this->esm_table
                 WHERE id = %d
                "
                , array(
                    $input['item_id']
                ) 
            ) );
            return true;
        } catch(Exception $e) {
            return false;
        }
    }

    public function getLocalData( $post_id )
    {
        global $wpdb, $wp_query;

        try {
            $results = $wpdb->get_row("SELECT * FROM $this->esm_table WHERE post_id =".$post_id);
            if($results) {
                return $results;
            } else {
                return false;
            }
        } catch(Exception $e) {
            return false;
        }
    }

    public function getPostData( $post_id ) 
    {
        global $wpdb, $wp_query;

        try {
            $results = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID =".$post_id);
            if($results) {
                return $results;
            } else {
                return false;
            }
        } catch(Exception $e) {
            return false;
        }
    }

    private function generateApiSecret( $key, $time )
    {
        return (string) md5( (string) $key .'+'. (int) $time );
    }


    /**
     * Sends data to Exxica database.
     * @param   $data       array()         The data to insert
     * @return              boolean         True if successful
     */
    public function sendExternalData( $local_data, $post, $action = 'update', $input = array() ) 
    {
        $raisedError = false;

        if( $action == 'destroy' ) {
            $_secret = $this->generateApiSecret( $this->api_key, $input['unixtime'] );
            $data[] = array(
                'unixtime' => (int)$input['unixtime'],
                'post_id' => (int)$input['post_id'],
                'item_id' => (int)$input['item_id'],
                'channel' => sanitize_text_field($input['channel']),
                'api' => array(
                    'action' => sanitize_text_field($action),
                    '_secret' => sanitize_text_field($_secret)
                )
            );
        } else if( $action == 'update' ) {
            foreach( $input['data'] as $item ) {
                $_secret = $this->generateApiSecret( $this->api_key, $item['unixtime'] );
                $strArr = str_split($post->post_content, 100);
                $_excerpt = (strlen($post->post_excerpt) == 0) ? $strArr[0].'...' : $post->post_excerpt;
                $data[] = array(
                    'title' => sanitize_text_field($local_data->publish_title), // Local
                    'description' => sanitize_text_field($item['description']), // Input
                    'excerpt' => sanitize_text_field($_excerpt), // Post
                    'publish_type' => sanitize_text_field($local_data->publish_type), // Local
                    'unixtime' => (int)$item['unixtime'], // Input
                    'channel' => sanitize_text_field($item['channel']), // Input
                    'publish_account' => sanitize_text_field($local_data->channel_account), // Local
                    'publish_article_url' => esc_url_raw($local_data->publish_article_url), // Local
                    'publish_image_url' => esc_url_raw($local_data->publish_image_url), // Local
                    'post_id' => (int)$item['post_id'], // Input
                    'item_id' => (int)$item['item_id'], // Input
                    'offset' => sanitize_text_field(isset($item['offset']) ? $item['offset'] : '+0000'), // Input
                    'api' => array(
                        'action' => sanitize_text_field($item['action']),
                        '_secret' => sanitize_text_field($_secret)
                    )
                );
            }
        } else {
            $out = array('success' => false, 'error' => array( 'code' => 1, 'message' => 'Unrecognized action.', 'type' => 'ActionException') );
            $raisedError = true;
        }

        $atts = array(
            'client' => array(
                'username' => (string)$this->esm_account,
                'email' => (string)htmlentities($this->user_email),
                'origin' => (string)$this->esm_origin,
                'api' => array(
                    '_key' => (string)$this->api_key,
                    '_created' => (int)$this->api_key_created
                )
            ),
            'data' => $data
        );

        $to = "http://api.exxica.com/publisher/exxica/publish";
        if(!$raisedError) $out = $this->postData( $to, $atts );

        return $out;
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
            //print_r(http_build_query( $atts ) );
            $out = curl_exec($ch);
            curl_close($ch);
        } catch( Exception $ex ) {
            $out = array('success' => false, 'error' => array( 'code' => $ex->getCode(), 'message' => $ex->getMessage(), 'type' => 'CurlException') );
        }

        return $out;
    }
}

class Exxica_Postdata_Handler
{
    protected $post;
    protected $esm_table;
    protected $esm_account;
    protected $user_email;
    protected $esm_origin;
    protected $locale;

    protected $api_key;
    protected $api_key_created;

    protected $error;

    protected $wpnonce;
    protected $text;

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
        global $wpdb, $wp_query, $current_user;
        get_currentuserinfo();

        // Post variables
        $this->wpnonce = isset($_REQUEST['_wpnonce']) ? $_REQUEST['_wpnonce'] : false;
        $this->text = isset($_REQUEST['text']) ? $_REQUEST['text'] : false;

        // System variables
        $this->locale = 'exxica-social-marketing';
        $this->esm_table = $wpdb->prefix.'exxica_social_marketing';
        $this->esm_origin = get_option('exxica_social_marketing_referer');
        $this->esm_account = get_option( 'exxica_social_marketing_account_'.$current_user->user_login, $this->esm_origin.'/'.$current_user->user_login );
        $this->api_key = get_option('exxica_social_marketing_api_key_'.$current_user->user_login, '9dad8134bb1f1c986af6036f287ee03c');
        $this->api_key_created = get_option('exxica_social_marketing_api_key_created_'.$current_user->user_login, 1403097585);

        $this->user_email = $current_user->user_email;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function getNonce()
    {
        return $this->wpnonce;
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

    public function setPost($post_id)
    {
        $this->post = get_post($post_id);
        return $this->post;
    }

    public function getText()
    {
        return sanitize_text_field($this->text);
    }

    public function getPost()
    {
        return (is_null($this->post)) ? false : $this->post;
    }

    public function getPostTitle()
    {
        return (is_null($this->post)) ? false : sanitize_text_field($this->post->post_title);
    }

    public function getPostExcerpt()
    {
        return (is_null($this->post)) ? false : sanitize_text_field($this->post->post_excerpt);
    }

    public function getPostMessage()
    {
        return (is_null($this->post)) ? false :  sanitize_text_field($this->post->post_content);
    }

    public function getPostPermalink()
    {
        return get_post_permalink( $this->post->ID );
    }

    public function getExcerpt($excerpt_length = 20)
    {
        $the_excerpt = '';
        if( strlen($this->post->post_excerpt) !== 0 && !is_null($this->post->post_excerpt) ) {
            $the_excerpt = $this->post->post_excerpt;
        } else {
            $the_excerpt = $this->post->post_content; //Gets post_content to be used as a basis for the excerpt
            $the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
            $words = explode(' ', $the_excerpt, $excerpt_length + 1);

            if(count($words) > $excerpt_length) :
                array_pop($words);
                array_push($words, '…');
                $the_excerpt = implode(' ', $words);
            endif;
        }

        return strip_tags($the_excerpt);
    }

    public function getTime($stamp)
    {
        return array('unix'=>date('U', $stamp),'atom'=>date(DATE_ATOM, $stamp));
    }

    public function cleanUrl($string) {
       return esc_url_raw( $string );
       //return preg_replace('/[^A-Za-z0-9\-.:\/?=%&]/', '', $string); // Removes special chars.
    }

    /**
     * Inserts data into database.
     * @param   $data       array()         The data to insert
     * @return  bool
     */
    public function insertLocalData( $input = array() ) 
    {
        global $wpdb, $wp_query;

        foreach( $input['data'] as $item ) {
            $data = array(
                'post_id' => (int)$item['post_id'],
                'referer' => sanitize_text_field($this->esm_origin),
                'exx_account' => sanitize_text_field($this->esm_account),
                'channel' => sanitize_text_field($item['channel']),
                'channel_account' => sanitize_text_field($item['publish_account']),
                'publish_type' => sanitize_text_field($item['publish_type']),
                'publish_localtime' => (int)$item['localtime'],
                'publish_unixtime' => (int)$item['unixtime'],
                'publish_image_url' => esc_url_raw($item['publish_image_url']),
                'publish_article_url' => esc_url_raw($item['publish_article_url']),
                'title' => sanitize_text_field($item['title']),
                'description' => sanitize_text_field($item['description'])
            );
            try {

                $wpdb->insert( 
                    $this->esm_table, 
                    array( 
                        'post_id'               => $data['post_id'], 
                        'exx_account'           => $data['exx_account'],
                        'channel'               => $data['channel'],
                        'channel_account'       => $data['channel_account'],
                        'publish_type'          => $data['publish_type'],
                        'publish_localtime'     => $data['publish_localtime'],
                        'publish_unixtime'      => $data['publish_unixtime'],
                        'publish_image_url'     => $data['publish_image_url'],
                        'publish_article_url'   => $data['publish_article_url'],
                        'publish_title'         => $data['title'],
                        'publish_description'   => $data['description']
                    ), 
                    array( 
                        '%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s'
                    ) 
                );
                return array( 'success' => true, 'item_id' => $wpdb->insert_id );
            } catch(Exception $e) {
                return array( 'success' => false );
            }
        }
    }

    public function updateLocalData( $input = array() ) 
    {
        global $wpdb, $wp_query;

        foreach( $input['data'] as $item ) {
            $data = array(
                'post_id' => (int)$item['post_id'],
                'exx_account' => sanitize_text_field($this->esm_account),
                'channel' => sanitize_text_field($item['channel']),
                'channel_account' => sanitize_text_field($item['publish_account']),
                'publish_type' => sanitize_text_field($item['publish_type']),
                'publish_localtime' => (int)$item['localtime'],
                'publish_unixtime' => (int)$item['unixtime'],
                'publish_image_url' => esc_url_raw($item['publish_image_url']),
                'publish_article_url' => esc_url_raw($item['publish_article_url']),
                'title' => sanitize_text_field($item['title']),
                'description' => sanitize_text_field($item['description'])
            );

            try {
                $wpdb->query( $wpdb->prepare( 
                    "
                    UPDATE $this->esm_table
                     SET
                      post_id = %d,
                      exx_account = %s,
                      channel = %s,
                      channel_account = %s,
                      publish_type = %s,
                      publish_localtime = %d,
                      publish_unixtime = %d,
                      publish_image_url = %s,
                      publish_article_url = %s,
                      publish_title = %s,
                      publish_description = %s
                     WHERE id = %d
                    "
                    , array(
                        $data['post_id'], 
                        $data['exx_account'], 
                        $data['channel'], 
                        $data['channel_account'], 
                        $data['publish_type'], 
                        $data['publish_localtime'],
                        $data['publish_unixtime'], 
                        $data['publish_image_url'], 
                        $data['publish_article_url'], 
                        $data['title'], 
                        $data['description'],
                        $item['item_id']
                    ) 
                ) );
                return array( 'success' => true );
            } catch(Exception $e) {
                return array( 'success' => false );
            }
        }
    }

    public function removeLocalData( $input = array() ) 
    {
        global $wpdb, $wp_query;

        try {
            $wpdb->query( $wpdb->prepare( 
                "
                DELETE FROM $this->esm_table
                 WHERE id = %d
                "
                , array(
                    $input['item_id']
                ) 
            ) );
            return true;
        } catch(Exception $e) {
            return false;
        }
    }

    private function generateApiSecret( $key, $time )
    {
        return (string) md5( (string) $key .'+'. (int) $time );
    }

    /**
     * Gets updated data from database.
     * @param   
     * @return  $data       array()         The updated table
     */
    public function getUpdatedData($data) 
    {
        global $wpdb, $wp_query;

        return $data;
    }


    /**
     * Sends data to Exxica database.
     * @param   $data       array()         The data to insert
     * @return              boolean         True if successful
     */
    public function sendExternalData( $doAction = 'create', $input = array() ) 
    {
        $raisedError = false;
        if( $doAction == 'create' ) {
            foreach( $input['data'] as $item ) {
                $_secret = $this->generateApiSecret( $this->api_key, $item['unixtime'] );
                $data[] = array(
                    'title' => sanitize_text_field($item['title']),
                    'description' => sanitize_text_field($item['description']),
                    'excerpt' => sanitize_text_field($item['excerpt']),
                    'publish_type' => sanitize_text_field($item['publish_type']),
                    'channel' => sanitize_text_field($item['channel']),
                    'publish_account' => sanitize_text_field($item['publish_account']),
                    'publish_image_url' => esc_url_raw($item['publish_image_url']),
                    'publish_article_url' => esc_url_raw($item['publish_article_url']),
                    'unixtime' => (int)$item['unixtime'],
                    'post_id' => (int)$item['post_id'],
                    'item_id' => (int)$item['item_id'],
                    'offset' => sanitize_text_field(isset($item['offset']) ? $item['offset'] : '+0000'),
                    'api' => array(
                        'action' => sanitize_text_field($item['action']),
                        '_secret' => sanitize_text_field($_secret)
                    )
                );
            }
        } else if( $doAction == 'destroy' ) {
            $_secret = $this->generateApiSecret( $this->api_key, $input['unixtime'] );
            $data[] = array(
                'unixtime' => (int)$input['unixtime'],
                'post_id' => (int)$input['post_id'],
                'item_id' => (int)$input['item_id'],
                'channel' => sanitize_text_field($input['channel']),
                'api' => array(
                    'action' => sanitize_text_field($doAction),
                    '_secret' => sanitize_text_field($_secret)
                )
            );
        } else if( $doAction == 'update' ) {
            foreach( $input['data'] as $item ) {
                $_secret = $this->generateApiSecret( $this->api_key, $item['unixtime'] );
                $data[] = array(
                    'title' => sanitize_text_field($item['title']),
                    'description' => sanitize_text_field($item['description']),
                    'excerpt' => sanitize_text_field($item['excerpt']),
                    'publish_type' => sanitize_text_field($item['publish_type']),
                    'channel' => sanitize_text_field($item['channel']),
                    'publish_account' => sanitize_text_field($item['publish_account']),
                    'publish_image_url' => esc_url_raw($item['publish_image_url']),
                    'publish_article_url' => esc_url_raw($item['publish_article_url']),
                    'unixtime' => (int)$item['unixtime'],
                    'post_id' => (int)$item['post_id'],
                    'item_id' => (int)$item['item_id'],
                    'offset' => sanitize_text_field(isset($item['offset']) ? $item['offset'] : '+0000'),
                    'api' => array(
                        'action' => sanitize_text_field($item['action']),
                        '_secret' => sanitize_text_field($_secret)
                    )
                );
            }
        } else {
            $out = array('success' => false, 'error' => array( 'code' => 1, 'message' => 'Unrecognized action.', 'type' => 'ActionException') );
            $raisedError = true;
        }

        $atts = array(
            'client' => array(
                'username' => (string)$this->esm_account,
                'email' => (string)htmlentities($this->user_email),
                'origin' => (string)$this->esm_origin,
                'api' => array(
                    '_key' => (string)$this->api_key,
                    '_created' => (int)$this->api_key_created
                )
            ),
            'data' => $data
        );

        $to = "http://api.exxica.com/publisher/exxica/publish";
        //$to = "http://api.exxica.com/publisher/test/publish";
        if(!$raisedError) $out = $this->postData( $to, $atts );
        
        return $out;
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
            //print_r(http_build_query( $atts ) );
            $out = curl_exec($ch);
            curl_close($ch);
        } catch( Exception $ex ) {
            $out = array('success' => false, 'error' => array( 'code' => $ex->getCode(), 'message' => $ex->getMessage(), 'type' => 'CurlException') );
        }

        return $out;
    }
}