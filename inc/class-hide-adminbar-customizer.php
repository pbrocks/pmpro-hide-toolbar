<?php

defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );

new Hide_Adminbar_Customizer();

class Hide_Adminbar_Customizer {

	public function __construct() {
		add_action( 'customize_register', array( $this, 'site_customizer_manager' ) );
		add_action( 'init', array( $this, 'disable_adminbar_on_frontend' ), 9 );
	}

	/**
	 * Used by hook: 'customize_preview_init'
	 *
	 * @see add_action('customize_preview_init',$func)
	 */
	public function hide_admin_bar_in_edit_user_settings() {
		$current_screen = get_current_screen();
		if ( 'profile' === $current_screen->id || 'user-edit' === $current_screen->id) {
			wp_enqueue_style( 'hide-profile-toolbar-option', plugins_url( '/css/hide-profile-toolbar-option.css', __FILE__ ) );
		}
	}
	/**
	 * [disable_adminbar_on_frontend description]
	 *
	 * @param  [type] [description]
	 * @return [type]             [description]
	 */
	public function disable_adminbar_on_frontend() {
		if ( ! current_user_can( get_option( 'minimum_user_role' ) ) && '1' !== get_option( 'show_admin_bar_on_frontend' ) ) {
			add_filter( 'show_admin_bar', '__return_false' );
			add_action( 'admin_enqueue_scripts', 'hide_admin_bar_in_edit_user_settings' );
		}
	}
	/**
	 * [disable_adminbar_on_frontend description]
	 *
	 * @param  [type] [description]
	 * @return [type]             [description]
	 */
	public function dropdown_for_adminbar_user_role() {
		$dropdown = wp_dropdown_roles( $selected );
		return $dropdown;
	}

	/**
	 * [site_customizer_manager description]
	 *
	 * @param  [type] $customizer_additions [description]
	 * @return [type]             [description]
	 */
	public function site_customizer_manager( $customizer_additions ) {
		$this->panel_creation( $customizer_additions );
	}

	/**
	 * A section to show how you use the default customizer controls in WordPress
	 *
	 * @param Obj $customizer_additions - WP Manager
	 *
	 * @return Void
	 */
	private function panel_creation( $customizer_additions ) {
		$customizer_additions->add_section( 'mysite_section', array(
			'title'          => 'MySite Custom Controls',
			'description'    => 'MySite Custom Controls was created specifically to be included in the Hide Adminbar Plugin, but likely won\'t be used.',
			'priority'       => 35,
		) );

		/**
		 * Adding a Checkbox Toggle
		 */
		if ( ! class_exists( 'Customizer_Toggle_Control' ) ) {
			require_once dirname( __FILE__ ) . '/controls/checkbox/toggle-control.php';
		}

		$customizer_additions->add_setting( 'show_admin_bar_on_frontend', array(
			'default'        => true,
			'type'           => 'option',
			// 'type'           => 'theme_mod',
			'transport'      => 'refresh',
		) );

		$customizer_additions->add_control( new Customizer_Toggle_Control( $customizer_additions,'show_admin_bar_on_frontend', array(
				'label'   => 'Toggle Frontend Adminbar',
				'section' => 'mysite_section',
				'settings'   => 'show_admin_bar_on_frontend',
				// 'type'    => 'checkbox',
				'type'    => 'ios',
				'description'   => 'Toggle on or off the Frontend Adminbar for non-admins. Toggle is equivalent to a checkbox.',
				'priority' => 10,
				)
		) );

		$customizer_additions->add_setting( 'minimum_user_role', array(
			'default'        => 'read',
			'type'           => 'option',
			'transport'      => 'refresh',
		) );

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

		$customizer_additions->add_control( new WP_Customize_Control(
		 $customizer_additions,
		 'minimum_user_role',
		 array(
			    'label'      => __( 'Select User Role' ), 
			    'description' => __( 'Using this option you can change the theme colors' ),
			    'priority'   => 10,
				'section' => 'mysite_section',
			    'type'    => 'select',
			    'choices' => $user_role_choices,
			)
		) );
	}
}
