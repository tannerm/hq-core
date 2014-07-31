<?php

/**
 * BuddyPress - Groups Loop
 *
 * Hard coding projects query. Check BuddyPress original template for alternative code.
 *
 * @package HQ
 */

bp_group_create_button();

$args = apply_filters( 'hq_groups_query_args', array(
	'type'    => 'active',
	'action'  => 'active',
	'user_id' => get_current_user_id(),
	'scope'   => 'personal'
) );

if ( bp_has_groups( $args ) ) : ?>

	<ul id="groups-list" class="item-list row" role="main">

	<?php while ( bp_groups() ) : bp_the_group(); ?>

		<li <?php bp_group_class(); ?>>

			<div class="item">
				<h3 class="item-title"><a href="<?php bp_group_permalink(); ?>"><?php bp_group_name(); ?></a></h3>
				<div class="item-meta"><span class="activity"><?php printf( __( 'active %s', 'buddypress' ), bp_get_group_last_active() ); ?></span></div>

				<div class="item-desc"><?php bp_group_description_excerpt(); ?></div>

				<?php do_action( 'bp_directory_groups_item' ); ?>

			</div>

			<div class="action">

				<?php do_action( 'bp_directory_groups_actions' ); ?>

				<div class="meta">
					<?php // bp_group_type(); ?>
					<?php
					$member_query_args = apply_filters( 'hq_group_members_query_args', array(
						'group_id' => bp_get_group_id(),
						'per_page' => 12,
						'exclude_admins_mods' => 0,
					) );

					if ( bp_group_has_members( $member_query_args ) ) : while ( bp_group_members() ) : bp_group_the_member(); ?>
						<a href="<?php echo bp_get_group_member_url(); ?>"><?php bp_group_member_avatar_thumb(); ?></a>
					<?php endwhile; endif; ?>

				</div>

			</div>

			<div class="clear"></div>
		</li>

	<?php endwhile; ?>

	</ul>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'There were no groups found.', 'buddypress' ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_groups_loop' ); ?>
