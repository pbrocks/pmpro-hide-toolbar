<?php

defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );

new Dev_Dashboard();

class Dev_Dashboard {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'dev_menu' ) );
	}

	public function dev_menu() {
		add_dashboard_page( 'Dev Dashboard', 'Dev Dashboard', 'edit_posts', 'dev-dashboard.php',  array( $this, 'dev_menu_page' ), 'dashicons-tickets', 11 );
	}

	public function dev_menu_page() {
		global $menu, $submenu;

			echo '<h2>' . __FILE__ . '</h2>';

			echo '<h3>You are viewing this menu from a ';
			// echo Setup_Functions::detect_mobile_device();
			echo ' device</h3>';
			echo '<h2> show_admin_bar_on_frontend = ' . get_option( 'show_admin_bar_on_frontend' ) . ' type = ' . gettype( get_option( 'show_admin_bar_on_frontend' ) ) . '</h2>';
			if ( '1' === get_option( 'show_admin_bar_on_frontend' ) ) {
				echo 'Show Adminbar is ON';
			} else { 
				echo 'Show Adminbar is set to OFF';
			}

			echo '<pre>';
			echo 'You can find this file in  <br>';
			echo plugins_url( '/', __FILE__ );
			echo '<br>';
			echo '</pre>';

			echo '<pre><h2>This is the Menu</h2>';
			print_r( $menu );
			echo '</pre>';

			echo '<pre><h2>This is the Submenu</h2>';
			print_r( $submenu );
			echo '</pre>';
	}
}
