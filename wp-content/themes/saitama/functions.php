<?php
/**
 * saitama functions and definitions.
 *
 * @package  saitama
 * @license  GNU General Public License v2.0
 * @since    saitama 1.0
 */

if ( ! function_exists( 'saitama_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function saitama_setup() {
	global $content_width;

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on saitama, use a find and replace
	 * to change 'saitama' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'saitama', get_template_directory() . '/languages' );

	/*-------------------------------------------*/
	/*	Set content width
	/* 	(Auto set up to media max with.)
	/*-------------------------------------------*/
	if ( ! isset( $content_width ) ) $content_width = 750;

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	set_post_thumbnail_size( 800, 600, true );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array( 'GlobalNav' => esc_html__( 'GlobalNav', 'saitama' ) ) );
	register_nav_menus( array( 'FooterNav' => esc_html__( 'FooterNav', 'saitama' ) ) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-header', array(
		'default-image' => '%s/assets/img/default-header.gif',
		'width' => 850,
		'height' => 300,
		'flex-width' => true,
		'flex-height' => true,
		'header-text' => false,
		'uploads' => true,
	) );

	register_default_headers( array(
		'accelerate' => array(
			'url' => '%s/assets/img/default-header.gif',
			'thumbnail_url' => '%s/assets/img/default-header.gif',
			'description' => 'Default Image'
		),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	add_editor_style( '/assets/css/editor.css' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );

	/*
	 * It will be able to use the HTML in the user's profile information
	 */
	remove_filter( 'pre_user_description', 'wp_filter_kses' );
	remove_filter( 'pre_user_description', 'wp_filter_post_kses' );

}
endif; // saitama_setup
add_action( 'after_setup_theme', 'saitama_setup' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function saitama_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'saitama' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<div class="margin-bottom-40">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="headline headline-md"><h2>',
		'after_title'   => '</h2></div>',
	) );

    $footer_widget_area_count = 4;

	for ( $i = 1; $i <= $footer_widget_area_count; $i++ ) {
		register_sidebar( array(
			'name'			=> __( 'Footer', 'saitama' ).$i,
			'id' 			=> 'footer-'.$i,
			'before_widget' => '',
			'after_widget' 	=> '',
			'before_title' 	=> '<div class="headline"><h2>',
			'after_title' 	=> '</h2></div>',
		) );
	}

	register_sidebar( array(
		'name'			=> __( 'Front Page Top', 'saitama' ),
		'id'			=> 'front-page-top',
		'description'	=> '',
		'before_widget'	=> '',
		'after_widget'	=> '',
		'before_title'	=> '',
		'after_title'	=> '',
	) );

}
add_action( 'widgets_init', 'saitama_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function saitama_scripts() {

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// CSS
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/plugins/bootstrap/css/bootstrap.min.css' );
	wp_enqueue_style( 'assets-style', get_template_directory_uri() . '/assets/css/style.css' );
	wp_enqueue_style( 'line-icons', get_template_directory_uri() . '/assets/plugins/line-icons/line-icons.css' );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/plugins/font-awesome/css/font-awesome.min.css' );
	wp_enqueue_style( 'brand-buttons', get_template_directory_uri() . '/assets/css/plugins/brand-buttons/brand-buttons.css' );
	wp_enqueue_style( 'brand-buttons-inversed', get_template_directory_uri() . '/assets/css/plugins/brand-buttons/brand-buttons-inversed.css' );

	wp_enqueue_style( 'blog_magazin', get_template_directory_uri() . '/assets/css/pages/blog_magazine.css' );
	wp_enqueue_style( 'blog-style', get_template_directory_uri() . '/assets/css/pages/blog.css' );
	wp_enqueue_style( 'page-404', get_template_directory_uri() . '/assets/css/pages/page_404_error.css' );
	wp_enqueue_style( 'timeline1', get_template_directory_uri() . '/assets/css/pages/feature_timeline1.css' );
	wp_enqueue_style( 'timeline2', get_template_directory_uri() . '/assets/css/pages/feature_timeline2.css' );

	wp_enqueue_style( 'saitama-style', get_stylesheet_uri() );


	// JavaScript
	wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/assets/plugins/bootstrap/js/bootstrap.min.js', array('jquery'), '', true );
	wp_enqueue_script( 'backtotop-js', get_template_directory_uri() . '/assets/plugins/back-to-top.js', array('jquery'), '', true );
	wp_enqueue_script( 'app-js', get_template_directory_uri() . '/assets/js/app.js', array('jquery'), '', true );
	wp_enqueue_script( 'custom-js', get_template_directory_uri() . '/assets/js/custom.js', array('jquery'), '', true );

}
add_action( 'wp_enqueue_scripts', 'saitama_scripts' );

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Implement the Custom Widgets
 */
require get_template_directory() . '/inc/custom-widgets.php';

/**
 * Implement the Custom Walker
 */
require get_template_directory() . '/inc/custom-walker.php';

/**
 * Implement the Page Navi
 */
require get_template_directory() . '/inc/page-navi.php';

/**
 * Implement the Bread Crumb
 */
require get_template_directory() . '/inc/bread-crumb.php';

/**
 * Implement the Expansion Pack Installer
 */
//require get_template_directory() . '/inc/functons-plugin-install.php';