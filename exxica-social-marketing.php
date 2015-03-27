<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * this starts the plugin.
 *
 * @link              http://exxica.com
 * @since             1.0.0
 * @package           Exxica_Social_Marketing
 *
 * @wordpress-plugin
 * Plugin Name:       Exxica Social Marketing
 * Description:       A tool that helps you publish your WordPress posts and pages to your selected social platforms. It has been made to help planning and scheduling publications on different social platforms which again increases traffic on your website.
 * Version:           1.1.7.2
 * Author:            Gaute Rønningen
 * Author URI:        http://exxica.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       exxica-social-marketing
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-exxica-social-marketing-activator.php';

/**
 * The code that runs during plugin deactivation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-exxica-social-marketing-deactivator.php';

/** This action is documented in includes/class-exxica-social-marketing-activator.php */
register_activation_hook( __FILE__, array( 'Exxica_Social_Marketing_Activator', 'activate' ) );

/** This action is documented in includes/class-exxica-social-marketing-deactivator.php */
register_activation_hook( __FILE__, array( 'Exxica_Social_Marketing_Deactivator', 'deactivate' ) );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-exxica-social-marketing.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_Exxica_Social_Marketing() {

	$plugin = new Exxica_Social_Marketing();
	$plugin->run();

}
run_Exxica_Social_Marketing();
