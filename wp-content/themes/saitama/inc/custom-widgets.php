<?php
/**
 *  Custom Tag Cloud Widget
 **/
function saitama_widget_tag_cloud_args( $args ) {
	$args['format'] = 'list';
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'saitama_widget_tag_cloud_args' );

function saitama_wp_tag_cloud( $html ) {
	$html = str_replace( 'wp-tag-cloud', 'list-unstyled blog-tags', $html );
	$html = preg_replace( '~class=\'tag-link-(\d+)\' title=\'([^\']+)\' style=\'font-size:([^\']+)\'~m', '',  $html );
	return $html;
}
add_filter( 'wp_tag_cloud', 'saitama_wp_tag_cloud' );

/**
 *  Custom Other Widgets
 **/
function saitama_custom_widgets() {

	/**
	 *  Custom Category Widget
	 **/
	class saitama_WP_Widget_Categories extends WP_Widget_Categories {

		function __construct() {
			parent::__construct();
		}

		function widget( $args, $instance ) {

			/** This filter is documented in wp-includes/default-widgets.php */
			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Categories', 'saitama' ) : $instance['title'], $instance, $this->id_base );

			//$c = ! empty( $instance['count'] ) ? '1' : '0';
			$c = 0;
			$h = ! empty( $instance['hierarchical'] ) ? '1' : '0';
			$d = ! empty( $instance['dropdown'] ) ? '1' : '0';

			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			$cat_args = array(
				'orderby'      => 'name',
				'show_count'   => $c,
				'hierarchical' => $h
			);

			if ( $d ) {
				static $first_dropdown = true;

				$dropdown_id = ( $first_dropdown ) ? 'cat' : "{$this->id_base}-dropdown-{$this->number}";
				$first_dropdown = false;

				echo '<label class="screen-reader-text" for="' . esc_attr( $dropdown_id ) . '">' . $title . '</label>';

				$cat_args['show_option_none'] = __( 'Select Category', 'saitama' );
				$cat_args['id'] = $dropdown_id;

				wp_dropdown_categories( apply_filters( 'widget_categories_dropdown_args', $cat_args ) );
?>

<script type='text/javascript'>
/* <![CDATA[ */
(function() {
	var dropdown = document.getElementById( "<?php echo esc_js( $dropdown_id ); ?>" );
	function onCatChange() {
		if ( dropdown.options[ dropdown.selectedIndex ].value > 0 ) {
			location.href = "<?php echo home_url(); ?>/?cat=" + dropdown.options[ dropdown.selectedIndex ].value;
		}
	}
	dropdown.onchange = onCatChange;
})();
/* ]]> */
</script>

<?php
			} else {
?>
				<ul class="list-unstyled blog-tags">
<?php
					$cat_args['title_li'] = '';

					wp_list_categories( apply_filters( 'widget_categories_args', $cat_args ) );
?>
				</ul>
	<?php
			}

			echo $args['after_widget'];

		} // widgets

	} // end saitama_WP_Widget_Categories

	register_widget( 'saitama_WP_Widget_Categories' );


	/**
	 *  Custom Archive Widget
	 **/
	class saitama_WP_Widget_Archives extends WP_Widget_Archives {

		function __construct() {
			parent::__construct();
		}

		function widget( $args, $instance ) {
			$c = ! empty( $instance['count'] ) ? '1' : '0';
			$d = ! empty( $instance['dropdown'] ) ? '1' : '0';

			/** This filter is documented in wp-includes/default-widgets.php */
			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Archives', 'saitama' ) : $instance['title'], $instance, $this->id_base );

			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			if ( $d ) {
				$dropdown_id = "{$this->id_base}-dropdown-{$this->number}";
?>
				<label class="screen-reader-text" for="<?php echo esc_attr( $dropdown_id ); ?>"><?php echo $title; ?></label>
				<select id="<?php echo esc_attr( $dropdown_id ); ?>" name="archive-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'>
					<?php
					$dropdown_args = apply_filters( 'widget_archives_dropdown_args', array(
						'type'            => 'monthly',
						'format'          => 'option',
						'show_post_count' => $c
					) );

					switch ( $dropdown_args['type'] ) {
						case 'yearly':
							$label = __( 'Select Year', 'saitama' );
							break;
						case 'monthly':
							$label = __( 'Select Month', 'saitama' );
							break;
						case 'daily':
							$label = __( 'Select Day', 'saitama' );
							break;
						case 'weekly':
							$label = __( 'Select Week', 'saitama' );
							break;
						default:
							$label = __( 'Select Post', 'saitama' );
							break;
					}
					?>

					<option value=""><?php echo esc_attr( $label ); ?></option>
					<?php wp_get_archives( $dropdown_args ); ?>

				</select>
<?php
			} else {
?>
				<ul class="list-group sidebar-nav-v1" id="sidebar-nav-1">
<?php
					$archives = wp_get_archives( apply_filters( 'widget_archives_args', array(
												'type'            => 'monthly',
												'show_post_count' => $c,
												'echo'            => false
											) ) );

					$archives = str_replace( '<li>', '<li class="list-group-item">', $archives );
					$archives = preg_replace( '/<\/a>&nbsp;\(([0-9]*)\)/', '&nbsp;&nbsp;<span class="badge rounded badge-default">$1 article</span></a>', $archives );
					echo $archives;
?>
				</ul>
<?php
			}

			echo $args['after_widget'];

		} // end widget

	} // end saitama_WP_Widget_Archives

	register_widget( 'saitama_WP_Widget_Archives' );


	/**
	 *  Custom Pages Widget
	 **/
	class saitama_WP_Widget_Pages extends WP_Widget_Pages {

		function __construct() {
			parent::__construct();
		}

		function widget( $args, $instance ) {
			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Pages', 'saitama' ) : $instance['title'], $instance, $this->id_base );

			$sortby = empty( $instance['sortby'] ) ? 'menu_order' : $instance['sortby'];
			$exclude = empty( $instance['exclude'] ) ? '' : $instance['exclude'];

			if ( $sortby == 'menu_order' )
				$sortby = 'menu_order, post_title';

			$out = wp_list_pages( apply_filters( 'widget_pages_args', array(
				'title_li'    => '',
				'echo'        => 0,
				'sort_column' => $sortby,
				'exclude'     => $exclude
			) ) );

			if ( ! empty( $out ) ) {
				echo $args['before_widget'];
				if ( $title ) {
					echo $args['before_title'] . $title . $args['after_title'];
				}
			?>
			<ul class="list-unstyled list-border-top">
				<?php echo $out; ?>
			</ul>
			<?php
				echo $args['after_widget'];
			}

		} // end widget

	} // end saitama_WP_Widget_Pages

	register_widget( 'saitama_WP_Widget_Pages' );


	/**
	 *  Custom Recent Posts Widget
	 **/
	class saitama_WP_Widget_Recent_Posts extends WP_Widget_Recent_Posts {

		function __construct() {
			parent::__construct();
		}

		public function widget($args, $instance) {
			$cache = array();
			if ( ! $this->is_preview() ) {
				$cache = wp_cache_get( 'widget_recent_posts', 'widget' );
			}

			if ( ! is_array( $cache ) ) {
				$cache = array();
			}

			if ( ! isset( $args['widget_id'] ) ) {
				$args['widget_id'] = $this->id;
			}

			if ( isset( $cache[ $args['widget_id'] ] ) ) {
				echo $cache[ $args['widget_id'] ];
				return;
			}

			ob_start();

			$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts', 'saitama' );
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

			$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
			if ( ! $number )
				$number = 5;
			$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

			$r = new WP_Query( apply_filters( 'widget_posts_args', array(
				'posts_per_page'      => $number,
				'no_found_rows'       => true,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true
			) ) );

			if ($r->have_posts()) :
?>
			<?php echo $args['before_widget']; ?>
			<?php if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			} ?>
			<ul class="list-unstyled list-border-top">
			<?php while ( $r->have_posts() ) : $r->the_post(); ?>
				<li>
					<a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
				<?php if ( $show_date ) : ?>
					<span class="post-date"><?php echo get_the_date(); ?></span>
				<?php endif; ?>
				</li>
			<?php endwhile; ?>
			</ul>
			<?php echo $args['after_widget']; ?>
<?php
			wp_reset_postdata();

			endif;

			if ( ! $this->is_preview() ) {
				$cache[ $args['widget_id'] ] = ob_get_flush();
				wp_cache_set( 'widget_recent_posts', $cache, 'widget' );
			} else {
				ob_end_flush();
			}
		} // end widget

	} // end saitama_WP_Widget_Recent_Posts

	register_widget( 'saitama_WP_Widget_Recent_Posts' );

}
add_action( 'widgets_init', 'saitama_custom_widgets' );
