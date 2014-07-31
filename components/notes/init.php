<?php

HQ_Notes::get_instance();
class HQ_Notes {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * Only make one instance of the HQ_Notes
	 *
	 * @return HQ_Notes
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof HQ_Notes ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
	}

	public function register_post_type() {
		$labels = array(
			'name'               => _x( 'Notes',   'post type general name',  'hq' ),
			'singular_name'      => _x( 'Note',    'post type singular name', 'hq' ),
			'name_admin_bar'     => _x( 'Note',    'add new on admin bar',    'hq' ),
			'new_item'           => __( 'New Note',                           'hq' ),
			'edit_item'          => __( 'Edit Note',                          'hq' ),
			'view_item'          => __( 'View Note',                          'hq' ),
			'all_items'          => __( 'All Notes',                          'hq' ),
			'search_items'       => __( 'Search Notes',                       'hq' ),
			'parent_item_colon'  => __( 'Parent Note:',                       'hq' ),
			'not_found'          => __( 'No notes found.',                    'hq' ),
			'not_found_in_trash' => __( 'No notes found in Trash.',           'hq' )
		);

		$args = array(
			'labels'       => $labels,
			'hierarchical' => true,
			'public'       => true,
			'menu_icon'    => 'dashicons-welcome-write-blog',
			'supports'     => array( 'title', 'author', 'editor', 'comments' ),
			'taxonomies'   => array( 'hq_note_groups' ),
		);

		register_post_type( 'hq_note', apply_filters( 'hq_notes_args', $args ) );
	}

}