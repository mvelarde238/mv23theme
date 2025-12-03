<?php
namespace Ultimate_Fields\Ultimate_Builder;

/**
 * Handles post lock functionality for the builder editor.
 *
 * @since 1.0
 */
class Post_Lock_Handler {
	/**
	 * Enqueue the necessary scripts for post lock functionality.
	 *
	 * @since 1.0
	 */
	public static function enqueue_scripts() {
		// Enqueue WordPress Heartbeat API and post script for post-lock functionality
		wp_enqueue_script( 'heartbeat' );
		wp_enqueue_script( 'post' );
	}

	/**
	 * Print the post-lock dialog HTML and required hidden fields.
	 *
	 * @since 1.0
	 */
	public static function print_post_lock_dialog() {
		global $post;
		
		if ( ! $post ) {
			return;
		}
		
		// Check if the post is currently being edited by another user
		$user_id = wp_check_post_lock( $post->ID );
		$active_post_lock = '';
		
		if ( ! $user_id ) {
			// No one is editing, set the lock for current user
			$active_post_lock = wp_set_post_lock( $post->ID );
			if ( $active_post_lock ) {
				$active_post_lock = implode( ':', $active_post_lock );
			}
		} else {
			// Someone else is editing, don't take over the lock
			// Just get the existing lock info
			$lock = get_post_meta( $post->ID, '_edit_lock', true );
			if ( $lock ) {
				$active_post_lock = $lock;
			}
		}
		?>
		<!-- Required fields for WordPress Heartbeat post lock -->
		<input type="hidden" id="post_ID" name="post_ID" value="<?php echo esc_attr( $post->ID ); ?>" />
		<input type="hidden" id="active_post_lock" value="<?php echo esc_attr( $active_post_lock ); ?>" />
		<input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo wp_create_nonce( 'update-post_' . $post->ID ); ?>" />
		
		<div id="post-lock-dialog" class="notification-dialog-wrap" style="display:none;">
			<div class="notification-dialog-background"></div>
			<div class="notification-dialog">
				<div class="post-locked-message">
					<div class="post-locked-avatar"></div>
					<p class="wp-tab-first currently-editing" tabindex="0"></p>
					<p>
						<a class="button button-primary wp-tab-last" href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . get_post_type() ) ); ?>">
							<?php _e( 'Go back' ); ?>
						</a>
					</p>
				</div>
				<div class="post-taken-over" style="display:none;">
					<div class="post-locked-avatar"></div>
					<p class="wp-tab-first" tabindex="0">
						<span class="currently-editing"></span><br />
						<span class="locked-saving hidden">
							<img src="<?php echo esc_url( admin_url( 'images/spinner-2x.gif' ) ); ?>" width="16" height="16" alt="" />
							<?php _e( 'Saving revision&hellip;' ); ?>
						</span>
						<span class="locked-saved hidden">
							<?php _e( 'Your latest changes were saved as a revision.' ); ?>
						</span>
					</p>
				</div>
			</div>
		</div>
		<?php
	}
}
