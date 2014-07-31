<?php

HQ_Init::get_instance();
class HQ_Init {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * Only make one instance of the HQ_Init
	 *
	 * @return HQ_Init
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof HQ_Init ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {
		add_action( 'plugins_loaded',   array( $this, 'components_init' ), 9 );
	}

	/**
	 * Initialize components
	 */
	public function components_init() {
		foreach( self::enabled_components() as $component ) {
			$init_path = apply_filters( "hq_component_{$component}init_path", self::get_component_path( $component ) . 'init.php' );
			include_once( $init_path );
		}
	}

	/**
	 * Get the path for the specified component
	 * 
	 * @param $component
	 *
	 * @return bool|mixed|void
	 */
	public static function get_component_path( $component ) {
		if ( ! in_array( $component, self::components() ) ) {
			return false;
		}

		return apply_filters( "hq_component_{$component}_path", HQ_PATH . "components/$component/" );
	}

	/**
	 * Define components
	 *
	 * @return mixed|void
	 */
	public static function components() {
		return apply_filters( 'hq_components', array( 'tasks', 'notes', 'buddypress' ) );
	}

	/**
	 * Define enabled components
	 *
	 * Defaults to all available components
	 *
	 * @return mixed|void
	 */
	public static function enabled_components() {
		return apply_filters( 'hq_enabled_components', self::components() );
	}

}