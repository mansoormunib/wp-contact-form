<?php

/**
 * @since             1.0.0
 * @package           Contact_Form
 *
 * @wordpress-plugin
 * Plugin Name:       Shift Studio Contact form
 * Description:       The form will only have three input fields: Name, minimum 2 characters, Email, Description
 * Version:           1.0.0
 * Author:            Personal
 * Author URI:        https://www.linkedin.com/in/mansoormunib/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       contact-form
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-contact-form-activator.php
 */
function activate_Contact_Form() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-contact-form-activator.php';
	Contact_Form_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-contact-form-deactivator.php
 */
function deactivate_Contact_Form() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-contact-form-deactivator.php';
	Contact_Form_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_Contact_Form' );
register_deactivation_hook( __FILE__, 'deactivate_Contact_Form' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-contact-form.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_Contact_Form() {

	$plugin = new Contact_Form();
	$plugin->run();

}
run_Contact_Form();
