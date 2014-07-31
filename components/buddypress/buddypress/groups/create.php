<div id="buddypress">

	<form action="" method="post" id="create-group-form" class="standard-form" >

		<?php do_action( 'template_notices' ); ?>

		<div class="item-body" id="group-create-body">

			<?php do_action( 'bp_before_group_details_creation_step' ); ?>

			<div>
				<label for="group-name"><?php _e( 'Group Name (required)', 'buddypress' ); ?></label>
				<input type="text" name="group-name" id="group-name" placeholder="New Project Name" aria-required="true" />
			</div>

			<div>
				<label for="group-desc"><?php _e( 'Group Description (required)', 'buddypress' ); ?></label>
				<input type="text" name="group-desc" id="group-desc" placeholder="Enter a description of the project here" />
			</div>

			<?php
			do_action( 'bp_after_group_details_creation_step' );
			do_action( 'groups_custom_group_fields_editable' ); // @Deprecated

			wp_nonce_field( 'groups_create_save_group-details' ); ?>

			<h4><?php _e( 'Privacy Options', 'buddypress' ); ?></h4>

			<div class="radio">
				<label><input type="radio" name="group-status" value="public" />
					<strong><?php _e( 'This is a public project', 'hq' ); ?></strong>
					<ul>
						<li><?php _e( 'Anyone can view and join this project.', 'hq' ); ?></li>
					</ul>
				</label>

				<label><input type="radio" name="group-status" value="hidden" checked="checked" />
					<strong><?php _e( 'This is a private project', 'buddypress' ); ?></strong>
					<ul>
						<li><?php _e( 'Only users who are invited can view and join this prject.', 'hq' ); ?></li>
					</ul>
				</label>
			</div>

			<h4><?php _e( 'Group Invitations', 'buddypress' ); ?></h4>

			<p><?php _e( 'Which members of this group are allowed to invite others?', 'buddypress' ); ?></p>

			<div class="radio">
				<label>
					<input type="radio" name="group-invite-status" value="members"<?php bp_group_show_invite_status_setting( 'members' ); ?> />
					<strong><?php _e( 'All group members', 'buddypress' ); ?></strong>
				</label>

				<label>
					<input type="radio" name="group-invite-status" value="mods"<?php bp_group_show_invite_status_setting( 'mods' ); ?> />
					<strong><?php _e( 'Group admins and mods only', 'buddypress' ); ?></strong>
				</label>

				<label>
					<input type="radio" name="group-invite-status" value="admins"<?php bp_group_show_invite_status_setting( 'admins' ); ?> />
					<strong><?php _e( 'Group admins only', 'buddypress' ); ?></strong>
				</label>
			</div>

			<?php if ( bp_is_active( 'forums' ) ) : ?>

				<h4><?php _e( 'Group Forums', 'buddypress' ); ?></h4>

				<?php if ( bp_forums_is_installed_correctly() ) : ?>

					<p><?php _e( 'Should this group have a forum?', 'buddypress' ); ?></p>

					<div class="checkbox">
						<label><input type="checkbox" name="group-show-forum" id="group-show-forum" value="1"<?php checked( bp_get_new_group_enable_forum(), true, true ); ?> /> <?php _e( 'Enable discussion forum', 'buddypress' ); ?>
						</label>
					</div>
				<?php elseif ( is_super_admin() ) : ?>

					<p><?php printf( __( '<strong>Attention Site Admin:</strong> Group forums require the <a href="%s">correct setup and configuration</a> of a bbPress installation.', 'buddypress' ), bp_core_do_network_admin() ? network_admin_url( 'settings.php?page=bb-forums-setup' ) : admin_url( 'admin.php?page=bb-forums-setup' ) ); ?></p>

				<?php endif; ?>

			<?php endif; ?>

			<?php wp_nonce_field( 'groups_create_save_group-create' ); ?>

			<div class="submit" id="previous-next">
				<input type="submit" value="<?php esc_attr_e( 'Create Project', 'hq' ); ?>" id="group-creation-create" name="save" class="button" />
			</div>

			<?php /* Don't leave out this hidden field */ ?>
			<input type="hidden" name="group_id" id="group_id" value="<?php bp_new_group_id(); ?>" />

		</div>
		<!-- .item-body -->

	</form>

</div>