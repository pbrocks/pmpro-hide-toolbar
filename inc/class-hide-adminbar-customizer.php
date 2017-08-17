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
		if ( ! current_user_can( 'manage_options' ) && '' === get_option( 'show_admin_bar_on_frontend' ) ) {
			add_filter( 'show_admin_bar', '__return_false' );
			add_action( 'admin_enqueue_scripts', 'hide_admin_bar_in_edit_user_settings' );
		}
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
		// $customizer_additions->add_panel(
		// 'mysite_panel', array(
		// 'title'       => 'Site Panel',
		// 'label'       => 'Site Panel',
		// 'description' => 'This is a description of this mysite panel',
		// 'priority'    => 10,
		// )
		// );
		// Checkbox control
		$customizer_additions->add_section( 'mysite_section', array(
			'title'          => 'MySite Custom Controls',
			'description'    => 'MySite Custom Controls was created specifically to be included in the Hide Adminbar Plugin, but likely won\'t be used.',
			'priority'       => 35,
		) );

		$customizer_additions->add_setting( 'show_admin_bar_on_frontend', array(
			'default'        => true,
			'type'           => 'option',
			// 'type'           => 'option',
			'transport'      => 'refresh',
		) );

		$customizer_additions->add_control( 'show_admin_bar_on_frontend', array(
			'label'   => 'Show adminbar on frontend for non-admins',
			// 'section' => 'title_tagline',
			'section' => 'mysite_section',
			'type'    => 'checkbox',
			'priority' => 72,
		) );

		// $customizer_additions->add_section(
		// 'mysite_section', array(
		// 'title'          => 'Site Section',
		// 'label'          => 'Site Panel',
		// 'description'    => 'Description of the mysite Section of the Site panel',
		// 'priority'       => 35,
		// 'panel'          => 'mysite_panel',
		// )
		// );
		// /**
		// * Adding a Checkbox Toggle
		// */
		// $customizer_additions->add_setting( 'show_mysite_alert', array(
		// 'default'        => false,
		// 'transport' => 'refresh',
		// ) );
		// $customizer_additions->add_control( array(
		// 'label'   => 'Show Front Alert Message',
		// 'description'   => 'Show Front Alert Message => slide to turn on setting. Toggle is equivalent to a checkbox.',
		// 'settings'   => 'show_mysite_alert',
		// 'section' => 'mysite_section',
		// 'type'    => 'checkbox',
		// 'priority' => 1,
		// ) );
		// /**
		// * Textbox control
		// */
		// $customizer_additions->add_setting(
		// 'date_of_alert', array(
		// 'default'      => date( 'l jS F Y' ),
		// )
		// );
		// $customizer_additions->add_control(
		// array(
		// 'section'  => 'mysite_section',
		// 'settings'   => 'date_of_alert',
		// 'type'     => 'text',
		// 'label'       => 'Date of Alert',
		// 'description' => 'Effetive date for this alert or announcement.',
		// 'priority' => 1,
		// )
		// );
		// Textbox control
		$customizer_additions->add_setting( 'textbox_setting', array(
			'default'        => 'Default Value',
		) );

		$customizer_additions->add_control( 'textbox_setting', array(
			'label'   => 'Text Setting',
			'section' => 'mysite_section',
			'type'    => 'text',
			'priority' => 1,
		) );

	}

}
