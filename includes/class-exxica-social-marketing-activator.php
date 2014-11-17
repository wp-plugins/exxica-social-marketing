<?php

/**
 * Fired during plugin activation
 *
 * @link       http://exxica.com
 * @since      1.0.0
 *
 * @package    Exxica_Social_Marketing
 * @subpackage Exxica_Social_Marketing/includes
 */

/**
 * Fired during plugin activation.
 *
 * @since      1.0.0
 * @package    Exxica_Social_Marketing
 * @subpackage Exxica_Social_Marketing/admin
 * @author     Gaute Rønningen <gaute@exxica.com>
 */
class Exxica_Social_Marketing_Activator {

	/**
	 * Triggers on activation of the plugin.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wp, $wpdb; 
		$sql = '';
		
		$latest_version = '1.1.6.1';
		$installed_version = get_option('exxica_social_marketing_version', false);

		$smTable = $wpdb->prefix . 'exxica_social_marketing';
		$accTable = $wpdb->prefix . 'exxica_social_marketing_accounts';
		$statTable = $wpdb->prefix . 'exxica_social_marketing_statuses';

		if(!$installed_version) {
			require_once ABSPATH.'wp-admin/includes/upgrade.php';
			// ESM first time setup
		    $sql = "CREATE TABLE IF NOT EXISTS $smTable(  
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
			); ";
			dbDelta( $sql );
		    $sql = "CREATE TABLE IF NOT EXISTS $accTable(  
			  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
			  `exx_account` varchar(128) NOT NULL,
			  `channel` varchar(64) NOT NULL,
			  `channel_account` varchar(128) NOT NULL,
			  `expiry_date` INT(20) NOT NULL,
			  `fb_page_id` varchar(128),
			  PRIMARY KEY (`id`)
			); ";
			dbDelta( $sql );
		    $sql = "CREATE TABLE IF NOT EXISTS $statTable (
			  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
			  `marketing_id` int(20) unsigned NOT NULL,
			  `status` int(20) unsigned NOT NULL COMMENT '0 = Ok, 1 = Error',
			  `message` text,
			  PRIMARY KEY (`id`)
			); ";
			dbDelta( $sql );
		} else {
			if($installed_version !== $latest_version) {
				/* 
				// TODO
				$installed_version_main = (int)strstr( $installed_version, '.', true);
				$installed_version_major = (int)strstr( substr( $installed_version, ( strlen( $installed_version_main ) + 1 ) ), '.', true);
				$installed_version_minor = (int)strstr( substr( $installed_version, ( strlen( $installed_version_main ) + 1 ) + ( strlen( $installed_version_major ) + 1 ) ), '.', true);

				// Upgrade installed tables
				if( $installed_version_main == 2 && $installed_version_major == 0 && $installed_version_minor == 0 ) {
					// 2.0.0 => 3.0.0
					$sql = "ALTER TABLE $accTable ADD COLUMN `expiry_date` INT(20) NOT NULL AFTER `channel_account`;";
					$sql .= "ALTER TABLE $smTable ADD COLUMN `exx_account` VARCHAR(128) NOT NULL AFTER `post_id`;";

				} elseif( $installed_version_main == 2 && $installed_version_major == 0 && $installed_version_minor == 1 ) {
					// 2.0.1 => 3.0.0
					$sql = "ALTER TABLE $smTable ADD COLUMN `publish_localtime` INT(20) NULL AFTER `publish_type`;";
					$sql .= "ALTER TABLE $smTable ADD COLUMN `exx_account` VARCHAR(128) NOT NULL AFTER `post_id`;";
					$sql .= "ALTER TABLE $accTable ADD COLUMN `expiry_date` INT(20) NOT NULL AFTER `channel_account`;";

				} elseif( $installed_version_main == 1 ) {
					// 1.x.x => 3.0.0
					$sql = "ALTER TABLE $smTable ADD COLUMN `publish_localtime` INT(20) NULL AFTER `publish_type`;";
					$sql .= "ALTER TABLE $smTable ADD COLUMN `exx_account` VARCHAR(128) NOT NULL AFTER `post_id`;";
					$sql .= "ALTER TABLE $accTable ADD COLUMN `expiry_date` INT(20) NOT NULL AFTER `channel_account`;";

				}
				*/

				// Update version
				update_option('exxica_social_marketing_version', $latest_version);
			}
		}
	}
}