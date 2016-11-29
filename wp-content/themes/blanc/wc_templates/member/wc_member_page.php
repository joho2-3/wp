<?php
/**
 * <meta content="charset=UTF-8">
 * @package Welcart
 * @subpackage Welcart Default Theme
 *
 * @link		http://welcustom.net/
 * @arranged by	Mamekko
 */
get_header();
?>

<div class="row">
    <div class="columns">
        <?php get_template_part('breadcrumbs'); ?>
    </div><!-- columns -->
</div><!-- row -->

<div class="row">
	
	<div class="columns large-9">
	<?php if (have_posts()) : usces_remove_filter(); ?>
		<article id="wc_<?php usces_page_name(); ?>" <?php post_class(); ?>>

			<h1><?php _e('Membership', 'usces'); ?></h1>
			<table class="wc_member_table">
				<tr>
					<th scope="row"><?php _e('member number', 'usces'); ?></th>
					<td class="num"><?php usces_memberinfo( 'ID' ); ?></td>
				</tr>
				<tr>
					<th><?php _e('Strated date', 'usces'); ?></th>
					<td><?php usces_memberinfo( 'registered' ); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Full name', 'usces'); ?></th>
					<td><?php esc_html_e(sprintf(__('Mr/Mrs %s', 'usces'), usces_localized_name( usces_memberinfo( 'name1', 'return' ), usces_memberinfo( 'name2', 'return' ), 'return' ))); ?></td>
				</tr>
				<tr>
				<?php if(usces_is_membersystem_point()) : ?>
					<th><?php _e('The current point', 'usces'); ?></th>
					<td class="num"><?php usces_memberinfo( 'point' ); ?></td>
				<?php else : ?>
					<th>&nbsp;</th>
					<td class="num">&nbsp;</td>
				<?php endif; ?>
				</tr>
				<tr>
					<th scope="row"><?php _e('e-mail adress', 'usces'); ?></th>
					<td><?php usces_memberinfo('mailaddress1'); ?></td>
					<?php $html_reserve = ''; ?>
					<?php echo apply_filters( 'usces_filter_memberinfo_page_reserve', $html_reserve, usces_memberinfo( 'ID', 'return' ) ); ?>
				</tr>
			</table>
			<ul  class="button-group">
				<li><a href="#edit" class="button small info"><?php _e('To member information editing', 'usces'); ?></a></li>
				<?php do_action( 'usces_action_member_submenu_list' ); ?>
				<li><?php usces_loginout(); ?></li>
			</ul>
			
			<p class="header_explanation">
			<?php do_action('usces_action_memberinfo_page_header'); ?>
			</p>
			
			<?php if(!wp_is_mobile()): ?>
			<h2><?php _e('Purchase history', 'usces'); ?></h2>
			<div class="currency_code"><?php _e('Currency','usces'); ?> : <?php usces_crcode(); ?></div>
			<div class="table_container">
				<?php usces_member_history(); ?>
			</div>
			<?php endif; ?>
			
			<h2><a name="edit"></a><?php _e('Member information editing', 'usces'); ?></h2>
			<div class="error_message"><?php usces_error_message(); ?></div>
			<form action="<?php usces_url('member'); ?>#edit" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
				<table class="customer_form">
				<?php uesces_addressform( 'member', usces_memberinfo(NULL), 'echo' ); ?>
					<tr>
						<th scope="row"><?php _e('e-mail adress', 'usces'); ?></th>
						<td colspan="2"><input name="member[mailaddress1]" id="mailaddress1" type="email" value="<?php usces_memberinfo('mailaddress1'); ?>" /></td>
					</tr>
					<tr>
						<th scope="row"><?php _e('password', 'usces'); ?></th>
						<td colspan="2"><input class="hide" value=" " /><input name="member[password1]" id="password1" type="password" value="<?php usces_memberinfo('password1'); ?>" autocomplete="off" />
						<?php _e('Leave it blank in case of no change.', 'usces'); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php _e('Password (confirm)', 'usces'); ?></th>
						<td colspan="2"><input name="member[password2]" id="password2" type="password" value="<?php usces_memberinfo('password2'); ?>" />
						<?php _e('Leave it blank in case of no change.', 'usces'); ?></td>
					</tr>
				</table>
				<input name="member_regmode" type="hidden" value="editmemberform" />
				<div class="send">
					<input name="top" type="button" value="<?php _e('Back to the top page.', 'usces'); ?>" onclick="location.href='<?php echo home_url(); ?>'" />
					<input name="editmember" type="submit" value="<?php _e('update it', 'usces'); ?>" />
					<input name="deletemember" type="submit" value="<?php _e('delete it', 'usces'); ?>" onclick="return confirm('<?php _e('All information about the member is deleted. Are you all right?', 'usces'); ?>');" />
				</div>
				<?php do_action('usces_action_memberinfo_page_inform'); ?>
			</form>
			
			<div class="footer_explanation">
				<?php do_action('usces_action_memberinfo_page_footer'); ?>
			</div>
			
		</article>
	<?php endif; ?>
	</div>

<div class="columns large-3">
    <div id="sidebar">
        <?php dynamic_sidebar('column-member'); ?>
    </div>
</div><!-- columns -->

</div><!--row-->

<?php get_footer(); ?>