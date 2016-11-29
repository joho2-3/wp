<?php
/**
 * The template for displaying single posts.
 * @link		http://welcustom.net/
 * @author		Mamekko
 * @copyright	Copyright (c) 2015 welcustom.net
 */
get_header();
?>

<?php get_template_part('breadcrumbs'); ?>

<div class="row">
	<div id="main" class="columns large-9">

		<?php if( have_posts() ): while( have_posts() ): the_post(); ?>
		<article <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">
			<h1 itemprop="name" class="entry-title"><?php the_title(); ?></h1>
			
			<ul class="inline-list inline-postmeta">
				<li>
					<span class="fa-stack text-red"><i class="fa fa-circle fa-stack-2x fa-red"></i><i class="fa fa-calendar fa-stack-1x fa-inverse"></i></span>
					<time datetime="<?php echo get_the_date('c'); ?>" itemprop="datePublished">
						<?php echo get_the_date(); ?>
					</time>
				</li>
				<?php if( has_category() ): ?>
				<li>
					<span class="fa-stack text-green"><i class="fa fa-circle fa-stack-2x fa-green"></i><i class="fa fa-folder fa-stack-1x fa-inverse"></i></span>
					<?php the_category(','); ?>
				</li>
				<li>
					<span class="fa-stack text-blue"><i class="fa fa-circle fa-stack-2x fa-blue"></i><i class="fa fa-comment fa-stack-1x fa-inverse"></i></span>
					<a href="<?php  comments_link(); ?>"><?php comments_number(); ?></a>
				</li>
				<?php endif; ?>
			</ul>
			
			<span itemprop="articleBody">
			<?php the_content(); ?>
			<?php wp_link_pages('before=<div id="page-links" class="text-center">&after=</div>&pagelink=<span>%</span>'); ?>
			</span>

			<?php the_tags('<p class="text-gray"><i class="fa fa-tag"></i> ', ',', '</p>'); ?>
			<div class="navlink clearfix">
				<span class="navlink-prev left">
				<?php previous_post_link('<span class="navlink-meta">&laquo; ' . __( 'Previous Post', 'blanc' ) . '</span> %link', '%title', true); ?>
				</span>
				<span class="navlink-next right text-right">
				<?php next_post_link('<span class="navlink-meta">' . __( 'Next Post', 'blanc' ) . ' &raquo;</span> %link', '%title', true); ?>
				</span>
			</div>
		</article>
		<?php endwhile; endif; ?>

		<?php comments_template(); ?>
		
	</div><!-- columns -->

	<div id="sidebar" class="columns large-3">
		<?php dynamic_sidebar('column-blog'); ?>
	</div><!-- columns -->
	
</div><!-- row -->

<?php get_footer(); ?>