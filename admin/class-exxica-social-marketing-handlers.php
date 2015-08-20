<?php
/**
 * The handler functionality of the plugin.
 *
 * @link       http://exxica.com
 * @since      1.1.2
 *
 * @package    Exxica_Social_Marketing
 * @subpackage Exxica_Social_Marketing/includes
 */

/**
 * The handler functionality of the plugin.
 *
 * @package    Exxica_Social_Marketing
 * @subpackage Exxica_Social_Marketing/admin
 * @author     Gaute Rønningen <gaute@exxica.com>
 */
class Exxica_Social_Marketing_Handlers
{
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.1.2
	 * @access   private
	 * @var      string    $name    The ID of this plugin.
	 */
	private $name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.1.2
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

    /**
     * The channel handler - receives channel data from Exxica and posts it to the local database.
     *
     * @since    1.1.2
     * @access   private
     * @var      Exxica_Db_Handler    $channel_handler    The channel handler class.
     */
    private $channel_handler;

    /**
     * The license handler - receives License data from the Exxica server and posts it to the local database.
     *
     * @since    1.1.2
     * @access   private
     * @var      Exxica_Process_Handler    $license_handler    The license handler class.
     */
    private $license_handler;

    /**
     * The overview handler - receives changes from the overview.
     *
     * @since    1.1.2
     * @access   private
     * @var      Exxica_Overview_Handler    $overview_handler    The overview handler class.
     */
    private $overview_handler;

    /**
     * The postdata handler - receives data from the plugin and sends it to the local database and the Exxica database.
     *
     * @since    1.1.2
     * @access   private
     * @var      Exxica_Postdata_Handler    $postdata_handler    The postdata handler class.
     */
    private $postdata_handler;

    /**
     * If the handlers are loaded this is true.
     *
     * @since    1.1.2
     * @access   private
     * @var      boolean    $loaded    If the handlers are loaded this is true.
     */
    private $loaded;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.1.2
	 * @var      string        $name       The name of this plugin.
	 * @var      string        $version    The version of this plugin.
     * @var      boolean       $loaded     If the handlers are loaded this is true.
	 */
	public function __construct( $name, $version ) 
	{
		$this->name = $name;
		$this->version = $version;
        $this->loaded = false;
    }

    /**
     * Load the handlers into their respective objects.
     *
     * @since    1.1.2
     * @var      Exxica_Db_Handler          $channel_handler        The database handler class.
     * @var      Exxica_Process_Handler     $license_handler        The process handler class.
     * @var      Exxica_Overview_Handler    $overview_handler       The overview handler class.
     * @var      Exxica_Postdata_Handler    $postdata_handler       The postdata handler class.
     * @var      boolean                    $loaded                 If the handlers are loaded this is true.
     */
    public function load_handlers()
    {
        $this->channel_handler = new Exxica_Db_Handler($this->name, $this->version, $_POST);
        $this->license_handler = new Exxica_Process_Handler($this->name, $this->version, $_POST);
        $this->overview_handler = new Exxica_Overview_Handler($this->name, $this->version);
        $this->postdata_handler = new Exxica_Postdata_Handler($this->name, $this->version);
        $this->loaded = true;
    }

    public function update_standard_channel()
    {
        $return = array();
        if( $this->loaded == true ) {
            $dbh = $this->channel_handler;
            $return = array( 'success' => true, 'data' => $dbh->updateStandardChannel() );
        } else {
            $return = array( 'success' => false );
        }
        $this->return_data( $return );
    }

    /**
     * Inserts new channel data.
     *
     * @since    1.1.2
     * @var      boolean                    $loaded                 If the handlers are loaded this is true.
     * 
     * @return   void
     */
    public function create_channel_data()
    {
        $return = array();
        if($this->loaded == true) {
            $dbh = $this->channel_handler;
            $return = array( 'success' => true, 'data' => $dbh->insertChannelData() );
        } else {
            $return = array( 'success' => false );
        }
        $this->return_data( $return );
    }


    /**
     * Delets old channel data.
     *
     * @since    1.1.2
     * @var      boolean                    $loaded                 If the handlers are loaded this is true.
     * 
     * @return   void
     */
    public function destroy_channel_data()
    {
        $return = array( 'success' => false );
        if($this->loaded == true) {
            $dbh = $this->channel_handler; 
            $results = $dbh->deleteChannelData();
            if($results) {
                $return = array( 'success' => true );
            }
        }
        $this->return_data( $return );
    }

    /**
     * Saves the license data.
     *
     * @since    1.1.2
     * @var      boolean                    $loaded                 If the handlers are loaded this is true.
     * 
     * @return   void
     */
    public function save_license_data()
    {
        if($this->loaded == true) {
            $dbh = $this->license_handler;
            $return = $dbh->process();
        } else {
            $return = array( 'success' => false );
        }
        $this->return_data( $return );
    }

    /**
     * Saves the overview data.
     *
     * @since    1.1.2
     * @var      boolean                    $loaded                 If the handlers are loaded this is true.
     * 
     * @return   void
     */
    public function update_overview_data()
    {
        if($this->loaded == true) {
            $success = false; $message = ''; $data = array(); $to_exxica = array(); 

            $dbh = $this->overview_handler;
            $action = 'update'; 
            $wpnonce = $dbh->getNonce();
            $locale = $dbh->getLocale();

            if( $wpnonce ) {
                if( $wpnonce = wp_create_nonce( '_inline_edit' ) ) {
                    $server_time = $dbh->getTime((int)$_POST['publish_utcdate']);
                    $client_time = $dbh->getTime((int)$_POST['publish_localdate']);

                    $to_exxica['data'][0]['unixtime'] = $server_time['unix'];
                    $to_exxica['data'][0]['localtime'] = $client_time['unix'];

                    $to_exxica['data'][0]['atom_unixtime'] = $server_time['atom'];
                    $to_exxica['data'][0]['atom_localtime'] = $client_time['atom'];

                    $to_exxica['data'][0]['post_id'] = $_POST['post_id'];
                    $to_exxica['data'][0]['item_id'] = $_REQUEST['item_id'];
                    $to_exxica['data'][0]['description'] = $_POST['post_title'];
                    $to_exxica['data'][0]['channel'] = $_POST['channel'];
                    $to_exxica['data'][0]['action'] = $action;
                    $success = $dbh->updateLocalData( $to_exxica );

                    if( $success ) {
                        $local_data = $dbh->getLocalData($_POST['post_id']);
                        $post = $dbh->getPostData($_POST['post_id']);
                        $message .= __('Data inserted into Local database. ', $locale );
                        $dataStr = trim($dbh->sendExternalData( $local_data, $post, $action, $to_exxica ) );
                        $data = json_decode($dataStr);

                        if( isset($data->success) && $data->success ) {
                            $success = true;
                            $message .= __('Data inserted into Exxica database. ', $locale );
                        } else {
                            $success = false;
                            $error = isset($data->error) ? $data->error : null;
                            $ext_input = isset($data->input) ? $data->input : null;
                            $message .= __('Exxica API reporting error. ', $locale);
                        }
                    }
                } else {
                    $message = __('Invalid nonce. ', $locale );
                }
            } else {
                $message = __('Values missing. ', $locale );
            }

            $return = array( 
                'success' => $success
            );
            if( isset( $error ) ) {
                $return = array_merge($return, array(
                    'error' => $error,
                    'input' => $ext_input
                ));
            } else {
                $return = array_merge($return, array(
                    'message' => $message
                ));
            }
        } else {
            $return = array( 'success' => false );
        }
        $this->return_data( $return );
    }

    /**
     * Saves the overview data.
     *
     * @since    1.1.2
     * @var      boolean                    $loaded                 If the handlers are loaded this is true.
     * 
     * @return   void
     */
    public function destroy_overview_data()
    {
        if($this->loaded == true) {
            $success = false; $message = ''; $data = array(); $to_exxica = array(); 

            $dbh = $this->overview_handler;
            $action = 'destroy';
            $wpnonce = $dbh->getNonce();
            $locale = $dbh->getLocale();

            if( $wpnonce ) {
                if( $wpnonce = wp_create_nonce( '_inline_edit' ) ) {
                    $data['unixtime'] = time();
                    $data['post_id'] = $_POST['post_id'];
                    $data['item_id'] = $_POST['item_id'];
                    $data['channel'] = $_POST['channel'];
                    $success = $dbh->removeLocalData( $data );
                    $message .= __('Data removed from Local database. ', $locale );
                    $exxica_data = json_decode( $dbh->sendExternalData( null, null, $action, $data ) );

                    if( isset($exxica_data->success) && $exxica_data->success ) {
                        $success = true;
                        $message .= __('Successfully deleted ', $locale ).$data['post_id'];
                    } else {
                        $success = false;
                        $error = isset($exxica_data->error) ? $exxica_data->error : null;
                        $ext_input = isset($exxica_data->input) ? $exxica_data->input : null;
                        $message .= __('Exxica API reporting error. ', $locale);
                    }
                } else {
                    $message = __('Invalid nonce. ', $locale );
                }
            } else {
                $message = __('Values missing. ', $locale );
            }

            $return = array( 
                'success' => $success
            );
            if( isset( $error ) ) {
                $return = array_merge($return, array(
                    'error' => $error,
                    'input' => $ext_input
                ));
            } else {
                $return = array_merge($return, array(
                    'message' => $message
                ));
            }
        } else {
            $return = array( 'success' => false );
        }
        $this->return_data( $return );
    }

    /**
     * Creates new post data.
     *
     * @since    1.1.2
     * @var      boolean                    $loaded                 If the handlers are loaded this is true.
     * 
     * @return   void
     */
    public function create_post_data()
    {
        $return = array( 'success' => false );
        if($this->loaded == true) {
            $success = false; $message = ''; $data = array(); $to_exxica = array(); 
            $dbh = $this->postdata_handler;
            $post = $dbh->setPost($_POST['post_id']);
            $locale = $dbh->getLocale(); 
            $wpnonce = $dbh->getNonce();
            $action = 'create';

            if( $wpnonce ) {
                if( $wpnonce = wp_create_nonce( 'postdataajax-nonce' ) ) {
                    $the_title = $dbh->getPostTitle();
                    $the_excerpt = $dbh->getExcerpt();
                    $the_message = $dbh->getText();

                    $server_time = $dbh->getTime((int)$_POST['one_time_utc_time']);
                    $client_time = $dbh->getTime((int)$_POST['local_time']);

                    $to_exxica['data'][0]['unixtime'] = $server_time['unix'];
                    $to_exxica['data'][0]['localtime'] = $client_time['unix'];

                    $to_exxica['data'][0]['atom_unixtime'] = $server_time['atom'];
                    $to_exxica['data'][0]['atom_localtime'] = $client_time['atom'];

                    $to_exxica['data'][0]['post_id'] = $_POST['post_id'];
                    $to_exxica['data'][0]['item_id'] = $_POST['item_id'];
                    $to_exxica['data'][0]['description'] = $the_message;
                    $to_exxica['data'][0]['title'] = $the_title;
                    $to_exxica['data'][0]['excerpt'] = $the_excerpt;
                    $to_exxica['data'][0]['channel'] = $_POST['channel'];
                    $to_exxica['data'][0]['publish_account'] = $_POST['publish_account'];
                    $to_exxica['data'][0]['publish_image_url'] = $_POST['image_url'];
                    $to_exxica['data'][0]['publish_article_url'] = $dbh->getPostPermalink();
                    $to_exxica['data'][0]['publish_type'] = 'publish';
                    $to_exxica['data'][0]['action'] = $action;

                    $response = $dbh->insertLocalData( $to_exxica );

                    if( $response['success'] ) {
                        $message .= __('Data inserted into Local database. ', $locale );
                        if($action == 'create') $to_exxica['data'][0]['item_id'] = $response['item_id'];
                        $dataStr = $dbh->sendExternalData( $action, $to_exxica );
                        $data = json_decode(trim($dataStr));

                        if( isset($data->success) && $data->success ) {
                            $success = true;
                            $message .= __('Data inserted into Exxica database. ', $locale );
                        } else {
                            $success = false;
                            $error = isset($data->error) ? $data->error : null;
                            $ext_input = isset($data->input) ? $data->input : null;
                            $message .= __('Exxica API reporting error. ', $locale);
                        }
                    }
                } else {
                    $message = __('Invalid nonce. ', $locale );
                }
            } 

            $return = array( 
                'success' => $success
            );
            if( isset( $error ) ) {
                $return = array_merge($return, array(
                    'error' => $error,
                    'input' => $ext_input
                ));
            } else {
                $return = array_merge($return, array(
                    'message' => $message
                ));
            }
        }
        $this->return_data( $return );
    }

    /**
     * Updates old post data.
     *
     * @since    1.1.2
     * @var      boolean                    $loaded                 If the handlers are loaded this is true.
     * 
     * @return   void
     */
    public function update_post_data()
    {
        $return = array( 'success' => false );
        if($this->loaded == true) {
            $success = false; $message = ''; $data = array(); $to_exxica = array(); 
            $dbh = $this->postdata_handler;
            $post = $dbh->setPost($_POST['post_id']);
            $action = 'update';
            $locale = $dbh->getLocale();  
            $wpnonce = $dbh->getNonce();

            if( $wpnonce ) {
                if( $wpnonce = wp_create_nonce( 'postdataajax-nonce' ) ) {
                    $the_title = $dbh->getPostTitle();
                    $the_excerpt = $dbh->getExcerpt();
                    $the_message = $dbh->getText();

                    $server_time = $dbh->getTime((int)$_POST['one_time_utc_time']);
                    $client_time = $dbh->getTime((int)$_POST['local_time']);

                    $to_exxica['data'][0]['unixtime'] = $server_time['unix'];
                    $to_exxica['data'][0]['localtime'] = $client_time['unix'];

                    $to_exxica['data'][0]['atom_unixtime'] = $server_time['atom'];
                    $to_exxica['data'][0]['atom_localtime'] = $client_time['atom'];

                    $to_exxica['data'][0]['post_id'] = $_POST['post_id'];
                    $to_exxica['data'][0]['item_id'] = $_POST['item_id'];
                    $to_exxica['data'][0]['description'] = $the_message;
                    $to_exxica['data'][0]['title'] = $the_title;
                    $to_exxica['data'][0]['excerpt'] = $the_excerpt;
                    $to_exxica['data'][0]['channel'] = $_POST['channel'];
                    $to_exxica['data'][0]['publish_account'] = $_POST['publish_account'];
                    $to_exxica['data'][0]['publish_image_url'] = $_POST['image_url'];
                    $to_exxica['data'][0]['publish_article_url'] = $dbh->getPostPermalink();
                    $to_exxica['data'][0]['publish_type'] = 'publish';
                    $to_exxica['data'][0]['action'] = $action;

                    $response = $dbh->updateLocalData( $to_exxica );

                    if( $response['success'] ) {
                        $message .= __('Data updated in Local database. ', $locale );
                        if($action == 'create') $to_exxica['data'][0]['item_id'] = $response['item_id'];
                        $dataStr = $dbh->sendExternalData( $action, $to_exxica );
                        $data = json_decode(trim($dataStr));

                        if( isset($data->success) && $data->success ) {
                            $success = true;
                            $message .= __('Data updated in Exxica database. ', $locale );
                        } else {
                            $success = false;
                            $error = isset($data->error) ? $data->error : null;
                            $ext_input = isset($data->input) ? $data->input : null;
                            $message .= __('Exxica API reporting error. ', $locale);
                        }
                    }
                } else {
                    $message = __('Invalid nonce. ', $locale );
                }
            }

            $return = array( 
                'success' => $success
            );
            if( isset( $error ) ) {
                $return = array_merge($return, array(
                    'error' => $error,
                    'input' => $ext_input
                ));
            } else {
                $return = array_merge($return, array(
                    'message' => $message
                ));
            }
        }
        $this->return_data( $return );
    }

    /**
     * Deletes old post data.
     *
     * @since    1.1.2
     * @var      boolean                    $loaded                 If the handlers are loaded this is true.
     * 
     * @return   void
     */
    public function destroy_post_data()
    {
        $return = array( 'success' => false );
        if($this->loaded == true) {
            $success = false; $message = ''; $data = array(); $to_exxica = array(); 
            $dbh = $this->postdata_handler;
            $post = $dbh->setPost($_POST['post_id']);
            $locale = $dbh->getLocale(); 
            $wpnonce = $dbh->getNonce();
            $action = 'destroy';

            $server_stamp = (int)$_POST['publish_unixtime'];

            $data['unixtime'] = date('U', $server_stamp);
            $data['post_id'] = $_POST['post_id'];
            $data['item_id'] = $_POST['item_id'];
            $data['channel'] = $_POST['channel'];

            $success = $dbh->removeLocalData( $data );
            $exxica_data = $dbh->sendExternalData( $action, $data );

            if( ! isset($exxica_data->success) || $exxica_data->success !== false ) {
                $success = true;
                $message .= __('Successfully deleted ', $locale ).$data['post_id'];
            } else {
                $success = false;
                $error = $exxica_data->error;
                $ext_input = isset($exxica_data->input) ? $exxica_data->input : null;
                $message .= __('Exxica API reporting error. ', $locale);
            }

            $return = array( 
                'success' => $success
            );
            if( isset( $error ) ) {
                $return = array_merge($return, array(
                    'error' => $error,
                    'input' => $ext_input
                ));
            } else {
                $return = array_merge($return, array(
                    'message' => $message
                ));
            }
        }
        $this->return_data( $return );
    }

    /**
     * Returns the data to the user in a valid format.
     * 
     * Returns the data as "text/html" due to IE wanting to download "application/json".
     *
     * @since    1.1.2
     * @access   private
     * 
     * @param    array                      $return                 An array with the data that should be returned.
     * 
     * @return   void
     */
    private function return_data( $return )
    {
        header('Content-Type: text/html');
        echo json_encode( $return );
    }

    public function factory_reset()
    {
        global $wp, $wpdb;

        $success = false;
        $wpnonce = isset($_REQUEST['_wpnonce']) ? $_REQUEST['_wpnonce'] : false;
        $error = null;

        if( $wpnonce ) {
            if( $wpnonce = wp_create_nonce( 'factoryreset-nonce' ) ) {
                try {
                    $smTable = $wpdb->prefix . 'exxica_social_marketing';
                    $accTable = $wpdb->prefix . 'exxica_social_marketing_accounts';
                    $statTable = $wpdb->prefix . 'exxica_social_marketing_statuses';

                    // Remove general option values
                    delete_option('exxica_social_marketing_account');
                    delete_option('exxica_social_marketing_account_');
                    delete_option('exxica_social_marketing_account_type_');
                    delete_option('exxica_social_marketing_api_key');
                    delete_option('exxica_social_marketing_api_key_created');
                    delete_option('exxica_social_marketing_expiry_');
                    delete_option('exxica_social_marketing_referer');
                    delete_option('exxica_social_marketing_show_channel_facebook_');
                    delete_option('exxica_social_marketing_show_channel_flickr_');
                    delete_option('exxica_social_marketing_show_channel_google_');
                    delete_option('exxica_social_marketing_show_channel_instagram_');
                    delete_option('exxica_social_marketing_show_channel_linkedin_');
                    delete_option('exxica_social_marketing_show_channel_twitter_');
                    delete_option('exxica_social_marketing_version');

                    // Remove user specific values
                    $wp_user_search = $wpdb->get_results("SELECT ID, user_login FROM $wpdb->users ORDER BY ID");
                    $login = '';
                    foreach ( $wp_user_search as $userid ) {
                        $login = stripslashes($userid->user_login);
                        delete_option('exxica_social_marketing_account_'.$login);
                        delete_option('exxica_social_marketing_account_type_'.$login);
                        delete_option('exxica_social_marketing_expiry_'.$login);
                        delete_option('exxica_social_marketing_show_channel_facebook_'.$login);
                        delete_option('exxica_social_marketing_show_channel_flickr_'.$login);
                        delete_option('exxica_social_marketing_show_channel_google_'.$login);
                        delete_option('exxica_social_marketing_show_channel_instagram_'.$login);
                        delete_option('exxica_social_marketing_show_channel_instagram_'.$login);
                        delete_option('exxica_social_marketing_show_channel_twitter_'.$login);
                    }

                    // Remove all ESM tables
                    $wpdb->query("DROP TABLE IF EXISTS $smTable;");
                    $wpdb->query("DROP TABLE IF EXISTS $accTable;");
                    $wpdb->query("DROP TABLE IF EXISTS $statTable;");

                    // ESM first time setup
                    $wpdb->query("CREATE TABLE IF NOT EXISTS $smTable(  
                      `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
                      `post_id` int(20) NOT NULL,
                      `exx_account` varchar(128) NOT NULL,
                      `channel` varchar(64) NOT NULL,
                      `channel_account` varchar(128) NOT NULL,
                      `publish_type` varchar(16) NOT NULL,
                      `publish_localtime` int(20),
                      `publish_unixtime` int(20) NOT NULL,
                      `publish_image_url` text NOT NULL,
                      `publish_article_url` text NOT NULL,
                      `publish_title` text NOT NULL,
                      `publish_description` text NOT NULL,
                      PRIMARY KEY (`id`)
                    ); ");
                    $wpdb->query("CREATE TABLE IF NOT EXISTS $accTable(  
                      `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
                      `exx_account` varchar(128) NOT NULL,
                      `channel` varchar(64) NOT NULL,
                      `channel_account` varchar(128) NOT NULL,
                      `expiry_date` INT(20) NOT NULL,
                      `fb_page_id` varchar(128),
                      PRIMARY KEY (`id`)
                    ); ");
                    $wpdb->query("CREATE TABLE IF NOT EXISTS $statTable (
                      `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
                      `marketing_id` int(20) unsigned NOT NULL,
                      `status` int(20) unsigned NOT NULL COMMENT '0 = Ok, 1 = Error',
                      `message` text,
                      PRIMARY KEY (`id`)
                    ); ");
                    $success = true;
                } catch(Exception $ex) {
                    $success = false;
                    $error = array('message' => $ex->getMessage());
                }
            }
        }

        $this->return_data( array( 'success' => $success, 'error' => $error) );
    }
}