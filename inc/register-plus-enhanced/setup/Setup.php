<?php
/**
 * Activation/Uninstall hooks
 *
 * @package register plus enhanced
 */

namespace rpe\setup;

/**
 * Install/Uninstall hooks
 */
class Setup {

	/**
	 * Constructor
	 *
	 * @method __construct
	 *
	 * @param  string $file plugin file.
	 */
	public function __construct( $file ) {
		$this->file = $file;

		register_activation_hook( $this->file, [ $this, 'activation' ] );
		register_deactivation_hook( $this->file, [ 'self', 'uninstall' ] );
		register_uninstall_hook( $this->file, [ 'self', 'uninstall' ] );
	}

	/**
	 * [activation]
	 *
	 * @method activation
	 */
	public static function activation() {
		global $wp_roles;
		add_role( 'rpr_unverified', 'Unverified' );
		update_option( 'register_plus_redux_last_activated', RPR_ACTIVATION_REQUIRED );
		add_option( 'rg_rpr_plugin_do_activation_redirect', true );
	}

	/**
	 * [uninstall]
	 *
	 * @method uninstall
	 */
	public static function uninstall() {
		global $wp_roles;
		remove_role( 'rpr_unverified' );
		delete_option( 'register_plus_redux_last_activated' );
	}
}
