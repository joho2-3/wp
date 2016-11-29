<?php
/**
 * The template for displaying Reviews for Welcart e-commerce templates.
 * For Welcart e-commerce plugin only.
 * @link		http://welcustom.net/
 * @author		Mamekko
 * @copyright	Copyright (c) 2015 welcustom.net
 */

if (!post_password_required()): ?>

<aside class="aside-review">
	<div class="row">
		<div class="column">
			<h1 class="font-quicksand"><?php _e('REVIEWS','blanc'); ?>&nbsp;<small>&#40;<?php comments_number('0','1','%'); ?>&#41;</small></h1>

			<?php if( comments_open()): ?>
			<?php
			$req = get_option( 'require_name_email' );
			$aria_req = ( $req ? " aria-required='true'" : '' );
			comment_form( array(
				'title_reply' => __('Add a review','blanc'),
				'fields' => array(
					'author' => '<p class="comment-form-author">' .'<label for="author">' .__( 'Name', 'blanc' ) .'</label> ' .( $req ? '<span class="required">*</span>' : '' ) .
					'<input id="author" name="author" type="text" value="' .esc_attr( $commenter['comment_author'] ) .'" size="30"' .$aria_req .' /></p>',
					'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email', 'blanc' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .
					'<input id="email" name="email" type="text" value="' .esc_attr( $commenter['comment_author_email'] ) .'" size="30"' .$aria_req .' /></p>',
					'url' => '',
				),
				'label_submit' => __('Submit','blanc'),
				'class_submit' => 'submit button small alert',
				'comment_notes_after'  => '',
				'format' => 'html5'
			)
			); ?>
			<?php endif; ?>
		</div>
	</div>

	<div class="row">
		<div class="column">
			<?php if( have_comments() ): ?>
			<div id="comments">
				<ul class="no-bullet">
				<?php wp_list_comments( array(
					'format' => 'html5'
					)
				); ?>
				</ul>
			</div>
			<div class="comments_pagination">
				<?php paginate_comments_links(); ?>
			</div>
			<?php elseif(!comments_open()): ?>
			<p class="no-review"><?php _e('No reviews yet.','blanc'); ?></p>
			<?php endif; ?>
		</div>
	</div>
</aside>

<?php endif; ?>