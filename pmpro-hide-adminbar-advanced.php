<?php
/**
 * Plugin Name: PMPro Hide Adminbar Advanced
 * Plugin URI: https://github.com/pbrocks/pmpro-hide-adminbar-advanced
 * Description: PMPro Hide Adminbar Advanced passes a filter to hide the adminbar on the frontend. Plugin also removes the user's setting in their profile. Admins can select affected user roles.
 * Version: 2.0
 * Author: pbrocks
 * Author URI: https://github.com/pbrocks
 */

defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );

include( 'inc/class-hide-adminbar-customizer.php' );
include( 'inc/class-dev-dashboard.php' );
