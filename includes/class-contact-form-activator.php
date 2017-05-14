<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Contact_Form
 * @subpackage Contact_Form/includes
 */
class Contact_Form_Activator {

	/**
	 * On plugin activate following function will be called
	 * @since    1.0.0
	 */
	public static function activate() {
		self::createCustomTable();
	}

	/**
	 * We will create custom table on activation
	 * @since    1.0.0
	 */
	protected static function createCustomTable(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'shift_studio_contact_form';
		$wpdb_collate = $wpdb->collate;
		$sql =
			"CREATE TABLE {$table_name} (
			id mediumint(8) unsigned NOT NULL auto_increment ,
			name varchar(255) NULL,
			email varchar(255) NULL,
			description text NULL,
			PRIMARY KEY (id)
			)
			COLLATE {$wpdb_collate}";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta( $sql );
	}

}
