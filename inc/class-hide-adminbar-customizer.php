<?php

defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );

new Hide_Toolbar_Customizer();

class Hide_Toolbar_Customizer {

	public function __construct() {
		add_action( 'customize_register', array( $this, 'site_customizer_manager' ) );
		add_action( 'init', array( $this, 'disable_toolbar_on_frontend' ), 9 );
	}

	/**
	 * Used by hook: 'customize_preview_init'
	 *
	 * @see add_action('customize_preview_init',$func)
	 */
	public function hide_toolbar_in_edit_user_settings() {
		$current_screen = get_current_screen();
		if ( 'profile' === $current_screen->id || 'user-edit' === $current_screen->id) {
			wp_enqueue_style( 'hide-profile-toolbar-option', plugins_url( '/css/hide-profile-toolbar-option.css', __FILE__ ) );
		}
	}
	/**
	 * [disable_toolbar_on_frontend description]
	 *
	 * @param  [type] [description]
	 * @return [type]             [description]
	 */
	public function disable_toolbar_on_frontend() {
		if ( ! current_user_can( get_option( 'minimum_user_role' ) ) && '1' !== get_option( 'show_toolbar_on_frontend' ) ) {
			add_filter( 'show_toolbar', '__return_false' );
			add_action( 'admin_enqueue_scripts', 'hide_toolbar_in_edit_user_settings' );
		}
	}
	/**
	 * [disable_toolbar_on_frontend description]
	 *
	 * @param  [type] [description]
	 * @return [type]             [description]
	 */
	public function dropdown_for_toolbar_user_role() {
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
		$this->create_panel( $customizer_additions );
		$this->create_section( $customizer_additions );
	}

	/**
	 * Customizer Section
	 *
	 * @param Obj $customizer_additions - WP Manager
	 *
	 * @return Void
	 */
	private function create_panel( $customizer_additions ) {
		$customizer_additions->add_panel(
			'pmpro_panel', array(
			'title'       => 'PMPro Panel',
			'label'       => 'PMPro Panel',
			'description' => 'This is a description of this PMPro panel',
			'priority'    => 10,
			)
		);
	}

	/**
	 * Customizer Section
	 *
	 * @param Obj $customizer_additions - WP Manager
	 *
	 * @return Void
	 */
	private function create_section( $customizer_additions ) {
		$customizer_additions->add_section( 'hide_toolbar_section', array(
			'title'          => 'PMPro Hide Toolbar',
			'description'    => 'PMPro Hide Toolbar was created specifically to be included in the Hide Toolbar Plugin, but likely won\'t be used.',
			'priority'       => 35,
			'panel'          => 'pmpro_panel',
		) );

		/**
		 * Adding a Checkbox Toggle
		 */
		if ( ! class_exists( 'Customizer_Toggle_Control' ) ) {
			require_once dirname( __FILE__ ) . '/controls/checkbox/toggle-control.php';
		}

		$customizer_additions->add_setting( 'show_toolbar_on_frontend', array(
			'default'        => true,
			'type'           => 'option',
			// 'type'           => 'theme_mod',
			'transport'      => 'refresh',
		) );

		$customizer_additions->add_control( new Customizer_Toggle_Control( $customizer_additions,'show_toolbar_on_frontend', array(
				'label'   => 'Toggle Frontend Toolbar',
				'section' => 'hide_toolbar_section',
				'settings'   => 'show_toolbar_on_frontend',
				// 'type'    => 'checkbox',
				'type'    => 'ios',
				'description'   => 'Toggle on or off the Frontend Toolbar for non-admins. Toggle is equivalent to a checkbox.',
				'priority' => 10,
				)
		) );

		$customizer_additions->add_setting( 'minimum_user_role', array(
			'default'        => 'read',
			'type'           => 'option',
			'transport'      => 'refresh',
		) );

		$customizer_additions->add_control( new WP_Customize_Control(
		 $customizer_additions,
		 'minimum_user_role',
		 array(
			    'label'      => __( 'Select User Role' ), 
			    'description' => __( 'Using this option you can select the minimum level necessary to view the toolbar on the frontend. To add more roles, add to the \'pmpha_user_roles\' filter.' ),
			    'priority'   => 10,
				'section' => 'hide_toolbar_section',
			    'type'    => 'select',
			    'choices' => $this->get_user_role_choices(),
			)
		) );
	}

	/**
	 * [get_user_role_choices description]
	 *
	 * @param  [type] $customizer_additions [description]
	 * @return [type]             [description]
	 */
	public function get_user_role_choices() {

		$user_role_choices = array(
 			'edit_posts' => 'Subscriber',
 			'publish_posts' => 'Contributor',
 			'edit_pages' => 'Author',
 			'manage_options' => 'Editor');
 		/**
		 * Filter to add user roles
		 *
		 * @since 2.0
		 *
		 * @param array $user_role_choices Array of user roles. Each element in array should be [capability=>role_name].	 
		 */
		return apply_filters('pmpha_user_roles', $user_role_choices);
	}

}