<?php

HQ_BP_Filters::get_instance();
class HQ_BP_Filters {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * Only make one instance of the HQ_BP_Filters
	 *
	 * @return HQ_BP_Filters
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof HQ_BP_Filters ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {
		add_filter( 'bp_get_theme_compat_dir', array( $this, 'bp_templates_location' ) );
	}

	public function bp_templates_location() {
		return HQ_PATH . 'components/buddypress';
	}

}