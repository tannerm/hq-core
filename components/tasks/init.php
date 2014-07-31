<?php

HQ_Tasks::get_instance();
class HQ_Tasks {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * Only make one instance of the HQ_Tasks
	 *
	 * @return HQ_Tasks
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof HQ_Tasks ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_taxonomy'  ) );
	}

	public function register_post_type() {
		$labels = array(
			'name'               => _x( 'Tasks', 'post type general name',  'hq' ),
			'singular_name'      => _x( 'Task',  'post type singular name', 'hq' ),
			'name_admin_bar'     => _x( 'Task',  'add new on admin bar',    'hq' ),
			'new_item'           => __( 'New Task',                         'hq' ),
			'edit_item'          => __( 'Edit Task',                        'hq' ),
			'view_item'          => __( 'View Task',                        'hq' ),
			'all_items'          => __( 'All Tasks',                        'hq' ),
			'search_items'       => __( 'Search Tasks',                     'hq' ),
			'parent_item_colon'  => __( 'Parent Task:',                     'hq' ),
			'not_found'          => __( 'No tasks found.',                  'hq' ),
			'not_found_in_trash' => __( 'No tasks found in Trash.',         'hq' )
		);

		$args = array(
			'labels'       => $labels,
			'hierarchical' => true,
			'public'       => true,
			'menu_icon'    => 'dashicons-list-view',
			'supports'     => array( 'title', 'author', 'editor', 'comments' ),
			'taxonomies'   => array( 'hq_task_lists' ),
		);

		register_post_type( 'hq_tasks', apply_filters( 'hq_tasks_args', $args ) );
	}

	public function register_taxonomy() {
		$labels = array(
			'name'              => _x( 'Task Lists', 'taxonomy general name',  'hq' ),
			'singular_name'     => _x( 'Task List', 'taxonomy singular name',  'hq' ),
			'search_items'      => __( 'Search Lists',                         'hq' ),
			'all_items'         => __( 'All Lists',                            'hq' ),
			'parent_item'       => __( 'Parent List',                          'hq' ),
			'parent_item_colon' => __( 'Parent list:',                         'hq' ),
			'edit_item'         => __( 'Edit List',                            'hq' ),
			'update_item'       => __( 'Update List',                          'hq' ),
			'add_new_item'      => __( 'Add New Task List',                    'hq' ),
			'new_item_name'     => __( 'New List Name',                        'hq' ),
			'menu_name'         => __( 'Task Lists',                           'hq' ),
		);

		$args = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'genre' ),
		);

		register_taxonomy( 'hq_task_lists', 'hq_tasks', apply_filters( 'hq_task_lists_args', $args ) );
	}

}