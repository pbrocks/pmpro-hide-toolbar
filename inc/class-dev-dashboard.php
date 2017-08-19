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

			$user_roles = get_option( 'wp_user_roles' );
			//var_dump($user_roles);

			wp_dropdown_roles();
			wp_dropdown_roles( $selected );

			echo '<pre><h2>This is the User Roles</h2>';
			// print_r( $user_roles );
			echo '</pre>';

			$theme_mods = get_theme_mods();
			// $minimum_user_role = get_theme_mod( 'minimum_user_role' );
			$minimum_user_role1 = get_option( 'minimum_user_role' );
			echo '<pre><h2>This is the theme_mods</h2>';
			// remove_theme_mod( 'minimum_user_role' );
			echo $minimum_user_role1 . '<br>';
			echo $minimum_user_role . '<br>';
			print_r( $theme_mods );
			echo '</pre>';
			echo '<h2>array stuff</h2>';
			
			$user_roles = get_option( 'wp_user_roles' );
			$user_role_choices = array();
			foreach($user_roles as $top_role => $top_capabilities){
				foreach($top_capabilities['capabilities'] as $mid_capability_name => $mid_capability_value){
					foreach($user_roles as $low_role => $low_capabilities){
						if(!$mid_capability_value || (count($top_capabilities)>count($low_capabilities)&&$low_capabilities[$mid_capability_name]==true)){
							break 2;
						}
					}
					$user_role_choices[$mid_capability_name]=$top_role;
					break;
				}
			}
			var_dump($user_role_choices);
	}
}
