<?php
/**
 * Main plugin file.
 *
 * @file register-plus-redux
 *
 * @package register-plus-redux
 */

/*
Author: radiok, Tanay Lakhani
Plugin Name: Register Plus Redux
Author URI: http://radiok.info/
Plugin URI: http://radiok.info/blog/category/register-plus-redux/
Description: Enhances the user registration process with complete customization and additional administration options.
Version: 4.1.1
Text Domain: register-plus-redux
Domain Path: /languages
 */

// NOTE: Debug, no more echoing
// trigger_error( sprintf( 'Register Plus Redux DEBUG: function($parameter=%s) from %s', print_r( $value, TRUE ), $pagenow ) );
// trigger_error( sprintf( 'Register Plus Redux DEBUG: function($parameter=%s)', print_r( $value, TRUE ) ) );
// TODO: meta key could be changed and ruin look ups.
// TODO: Disable functionality in wp-signup and wp-admin around rpr_active_for_network.
// TODO: Custom messages may not work with Wordpress MS as it uses wpmu_welcome_user_notification not wp_new_user_notification.
// TODO: Verify wp_new_user_notification triggers when used in MS due to the $pagenow checks.
// TODO: Enhancement- Configuration to set default display_name and/or lockdown display_name.
// TODO: Enhancement- Create rpr-signups table and mirror wpms.
// TODO: Enhancement- Signups table needs an edit view.
// TODO: Enhancement- MS users aren't being linked to a site, this is by design, as a setting to automatically add users at specified level.
// TODO: Enhancement- Alter admin pages to match registration/signup.
// TODO: Enhancement- Widget is lame/near worthless.
define( 'RPR_VERSION', '4.1.1' );
define( 'RPR_ACTIVATION_REQUIRED', '3.9.6' );
define( 'RPE_PLUGIN_FILE', __FILE__ );

require_once 'vendor/autoload.php';

$rpe_setup = new \rpe\setup\Setup( RPE_PLUGIN_FILE );

require_once 'register-plus-redux-legacy.php';

// include secondary php files outside of object otherwise $register_plus_redux will not be an instance yet.
if ( class_exists( 'Register_Plus_Redux' ) ) {
	// rumor has it this may need to declared global in order to be available at plugin activation.
	$register_plus_redux = new Register_Plus_Redux();

	if ( is_admin() ) {
		require_once plugin_dir_path( __FILE__ ) . 'rpr-admin.php';
		$rpr_admin = new RPR_Admin( $rpe_setup );
	}

	if ( is_admin() ) {
		require_once plugin_dir_path( __FILE__ ) . 'rpr-admin-menu.php';
	}

	$do_include = false;
	if ( '1' === $register_plus_redux->rpr_get_option( 'enable_invitation_tracking_widget' ) ) {$do_include = true;}
	if ( $do_include && is_admin() ) {
		require_once plugin_dir_path( __FILE__ ) . 'rpr-dashboard-widget.php';
	}

	// TODO: Determine which features require the following file.
	require_once plugin_dir_path( __FILE__ ) . 'rpr-login.php';

	// TODO: Determine which features require the following file.
	if ( is_multisite() ) {
		require_once plugin_dir_path( __FILE__ ) . 'rpr-signup.php';
	}

	$do_include = false;
	if ( '1' === $register_plus_redux->rpr_get_option( 'verify_user_admin' ) ) {$do_include = true;}
	if ( is_array( $register_plus_redux->rpr_get_option( 'show_fields' ) ) ) {$do_include = true;}
	if ( is_array( get_option( 'register_plus_redux_usermeta-rv2' ) ) ) {$do_include = true;}
	if ( '1' === $register_plus_redux->rpr_get_option( 'enable_invitation_code' ) ) {$do_include = true;}
	if ( '1' === $register_plus_redux->rpr_get_option( 'user_set_password' ) ) {$do_include = true;}
	if ( '1' === $register_plus_redux->rpr_get_option( 'autologin_user' ) ) {$do_include = true;}
	if ( $do_include && is_multisite() && Register_Plus_Redux::rpr_active_for_network() ) {
		require_once plugin_dir_path( __FILE__ ) . 'rpr-activate.php';
	}

	// NOTE: Requires rpr-admin.php for rpr_new_user_notification_warning make.
	$do_include = false;
	if ( '1' === $register_plus_redux->rpr_get_option( 'verify_user_email' ) ) {$do_include = true;}
	if ( '1' === $register_plus_redux->rpr_get_option( 'disable_user_message_registered' ) ) {$do_include = true;}
	if ( '1' === $register_plus_redux->rpr_get_option( 'disable_user_message_created' ) ) {$do_include = true;}
	if ( '1' === $register_plus_redux->rpr_get_option( 'custom_user_message' ) ) {$do_include = true;}
	if ( '1' === $register_plus_redux->rpr_get_option( 'verify_user_admin' ) ) {$do_include = true;}
	if ( '1' === $register_plus_redux->rpr_get_option( 'disable_admin_message_registered' ) ) {$do_include = true;}
	if ( '1' === $register_plus_redux->rpr_get_option( 'disable_admin_message_created' ) ) {$do_include = true;}
	if ( '1' === $register_plus_redux->rpr_get_option( 'custom_admin_message' ) ) {$do_include = true;}
	if ( $do_include ) {
		require_once plugin_dir_path( __FILE__ ) . 'rpr-new-user-notification.php';
	}
} // End if().
