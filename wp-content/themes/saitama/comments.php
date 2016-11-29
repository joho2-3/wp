<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @package saitama
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<?php if ( have_comments() ) : ?>
	<div class="media">
		<h3><?php echo esc_html__( 'Comments List', 'saitama' ); ?></h3>
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __( 'Prevoius comment', 'saitama' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Next comment', 'saitama' ) ); ?></div>
			</div>
			<br />
		<?php endif; ?>

		<?php wp_list_comments( array( 'callback' => 'saitama_custom_comment' ) ); ?>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __( 'Prevoius comment', 'saitama' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Next comment', 'saitama' ) ); ?></div>
			</div>
		<?php endif; ?>

	</div>
<?php endif; ?>

<div class="post-comment">
	<?php comment_form(); ?>
</div>