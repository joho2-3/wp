<?php
/**
 * The template for displaying search form
 * @link		http://welcustom.net/
 * @author		Mamekko
 * @copyright	Copyright (c) 2015 welcustom.net
 */
?>
<form action="<?php echo esc_url( home_url('/') ); ?>" class="searchform" id="searchform_s" method="get" role="search">
	<div class="row">
		<div class="large-12 columns">
			<div class="row collapse postfix-radius">
				<div class="small-10 columns">
					<input type="search" class="field" name="s" value="<?php esc_attr( get_search_query() ); ?>" placeholder="<?php _e('keywords...','blanc');?>">
				</div>
				<div class="small-2 columns">
					<input type="submit" class="submit button postfix black font-awesome" value="&#xf002;">
					<input type="hidden" name="searchitem" value="posts">
				</div>
			</div>
		</div>
	</div>
</form>