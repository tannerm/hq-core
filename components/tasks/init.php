<?php

HQ_Tasks::get_instance();
class HQ_Tasks {

	/**
	 * @var
	 */
	protected static $_instance;

	public static $task_cpt = 'hq_tasks';

	public static $task_tax = 'hq_task_lists';

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
		$this->includes();

		add_action( 'init',                                array( $this, 'register_post_type'      ) );
		add_action( 'init',                                array( $this, 'register_taxonomy'       ) );
		add_action( self::$task_tax . '_add_form_fields',  array( $this, 'task_project_field_add'  ) );
		add_action( self::$task_tax . '_edit_form_fields', array( $this, 'task_project_field_edit' ) );
		add_action( 'create_term',                         array( $this, 'save_task_project'       ) );
		add_action( 'edit_term',                           array( $this, 'save_task_project'       ) );
	}

	private function includes() {
		include_once( __DIR__ . '/functions.php' );
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
			'taxonomies'   => array( self::$task_tax ),
		);

		register_post_type( self::$task_cpt, apply_filters( 'hq_tasks_args', $args ) );
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

		register_taxonomy( self::$task_tax, self::$task_cpt, apply_filters( 'hq_task_lists_args', $args ) );
	}

	public function task_project_field_add( $taxonomy ) {
		?>
		<div class="form-field">
			<label for="project">Project</label>
			<select name="project" id="project" class="postform">
				<option value="-1" selected="selected">None</option>
				<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>
					<option value="<?php echo esc_attr( bp_get_group_id() ); ?>"><?php echo esc_html( bp_get_group_name() ); ?></option>
				<?php endwhile; endif; ?>
			</select>
			<p>Select the project to which this task list will apply.</p>
		</div>
		<?php
	}

	public function task_project_field_edit( $task_list ) {
		$project = hq_tasks_get_task_list_project( $task_list->term_id );
		?>
		<tr class="form-field">
			<th scope="row"><label for="project">Project</label></th>
			<td>
				<select name="project" id="project" class="postform">
					<option value="-1">None</option>
					<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>
						<option value="<?php echo esc_attr( bp_get_group_id() ); ?>" <?php selected( bp_get_group_id(), $project ); ?>><?php echo esc_html( bp_get_group_name() ); ?></option>
					<?php endwhile; endif; ?>
				</select>
			</td>
		</tr>
	<?php
	}

	public function save_task_project( $task_list ) {
		if ( empty( $_POST['project'] ) ) {
			return;
		}

		hq_tasks_update_project_task_lists( $task_list, (int)$_POST['project'] );
	}

}