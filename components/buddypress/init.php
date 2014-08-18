<?php

HQ_BP::get_instance();
class HQ_BP {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * Only make one instance of the HQ_BP
	 *
	 * @return HQ_BP
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof HQ_BP ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {
		add_filter( 'bp_template_stack',           array( $this, 'bp_templates_location'     ), 13     );
		add_filter( 'bp_groups_directory_header',        array( $this, 'group_title'               ), 11 ); // use higher priority to overwrite appended button
		add_filter( 'bp_get_group_create_button',        array( $this, 'group_create_button'       )     );
		add_filter( 'bp_get_groups_current_create_step', array( $this, 'current_group_create_step' )     );

		// using a weird action to hook in after group_steps are specified
		add_filter( 'groups_valid_status',                        array( $this, 'group_create_steps' ) );
		add_action( 'wp_head',                                    array( $this, 'remove_filters'     ) );
		add_action( 'groups_create_group_step_save_group-create', array( $this, 'create_project'     ) );
	}

	/**
	 * Specify path for BuddyPress Templates
	 * @return string
	 */
	public function bp_templates_location() {
		return HQ_Init::get_component_path( 'buddypress' );
	}

	public function remove_filters() {
		remove_filter( 'wp_title', 'bp_modify_page_title', 10, 3 );
	}

	/**
	 * Keep BP from overwriting page title
	 *
	 * @param $title
	 *
	 * @return mixed
	 */
	public function group_title( $title ) {
		global $wp_query;

		if ( ! empty( $wp_query->post->post_title ) ) {
			$title = $wp_query->post->post_title;
		}

		return $title;
	}

	/**
	 * Customize Group create button
	 *
	 * @param $button_args
	 *
	 * @return array
	 */
	public function group_create_button( $button_args ) {
		return array(
			'id'         => 'create_project',
			'component'  => 'groups',
			'link_text'  => __( 'Create a Project', 'hq' ),
			'link_title' => __( 'Create a Project', 'hq' ),
			'link_class' => 'button group-create bp-title-button',
			'link_href'  => trailingslashit( bp_get_root_domain() ) . trailingslashit( bp_get_groups_root_slug() ) . trailingslashit( 'create' ),
			'wrapper'    => false,
		);
	}

	public function current_group_create_step( $current_step ) {
		if ( $current_step ) {
			return $current_step;
		}

		return 'group-create';
	}

	/**
	 * Using the groups_valid_status filter to update the creation steps so that
	 * we can make sure there is only one step.
	 *
	 * @param $steps
	 *
	 * @return mixed
	 */
	public function group_create_steps( $steps ) {
		global $bp;

		$bp->groups->group_creation_steps = array(
			'group-create' => array(
				'name'       => __( 'Create',  'hq' ),
				'position'   => 0
			)
		);

		return $steps;

	}

	/**
	 * Create project
	 */
	public function create_project() {
		global $bp;

		// Make sure we have the write info
		if ( empty( $_POST['group-name'] ) || empty( $_POST['group-desc'] ) || !strlen( trim( $_POST['group-name'] ) ) || !strlen( trim( $_POST['group-desc'] ) ) ) {
			bp_core_add_message( __( 'Please fill in all of the required fields', 'buddypress' ), 'error' );
			bp_core_redirect( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/create/' );
		}

		$args = array(
			'name'         => $_POST['group-name'],
			'description'  => $_POST['group-desc'],
			'slug'         => groups_check_slug( sanitize_title( esc_attr( $_POST['group-name'] ) ) ),
			'date_created' => bp_core_current_time(),
			'status'       => $_POST['group-status'],
		);

		// Create the new project
		if ( !$bp->groups->new_group_id = groups_create_group( apply_filters( 'hq_create_project', $args ) ) ) {
			bp_core_add_message( __( 'There was an error saving group details, please try again.', 'buddypress' ), 'error' );
			bp_core_redirect( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/create/step/' . bp_get_groups_current_create_step() . '/' );
		}

		$bp->groups->current_group = groups_get_group( array( 'group_id' => $bp->groups->new_group_id ) );

		// reset cookies
		setcookie( 'bp_new_group_id', false, time() - 1000, COOKIEPATH );
		setcookie( 'bp_completed_create_steps', false, time() - 1000, COOKIEPATH );

	}

}