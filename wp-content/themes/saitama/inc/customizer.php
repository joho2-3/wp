<?php
/**
 * saitama Theme Customizer
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 */
function saitama_customize_register( $wp_customize ) {
	$saitama_theme_options = get_option('saitama_theme_options');

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	/*
	 * Design Settings
	 */
	$wp_customize->add_section( 'saitama_design', array(
		'title'		=> __('Design Settings', 'saitama' ),
		'priority'	=> 500,
	) );

	$wp_customize->add_setting( 'saitama_theme_options[keyColor]', array(
		'default'            => '',
		'sanitize_callback'  => 'maybe_hash_hex_color',
		'capability'         => 'edit_theme_options',
		'type'               => 'option',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'keyColor', array(
		'label'    => __('Key color', 'saitama'),
		'section'  => 'saitama_design',
		'settings' => 'saitama_theme_options[keyColor]',
		)
	) );

	$wp_customize->add_setting( 'saitama_theme_options[keyColorDark]', array(
		'default'            => '',
		'sanitize_callback'  => 'maybe_hash_hex_color',
		'capability'         => 'edit_theme_options',
		'type'               => 'option',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'keyColorDark', array(
		'label'    => __('Key Color(dark)', 'saitama'),
		'section'  => 'saitama_design',
		'settings' => 'saitama_theme_options[keyColorDark]',
		)
	) );

    $wp_customize->add_setting( 'saitama_theme_options[site_logo_image]',  array(
        'default'        	=> '',
        'sanitize_callback' => 'esc_url_raw',
		'capability'        => 'edit_theme_options',
        'type'           	=> 'option',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control(
		$wp_customize,
		'site_logo_image',
		array(
			'label'     => __('Site logo image', 'saitama'),
			'section'   => 'saitama_design',
			'settings'  => 'saitama_theme_options[site_logo_image]',
		)
	) );

	/*
	 * Slider Settings
	 */
	$wp_customize->add_section( 'saitama_slider', array(
		'title'		=> __('Slide show', 'saitama' ),
		'priority'	=> 600,
	) );

	$priority = 610;

	for ( $i=1; $i<=5; $i++ ) {
	    $wp_customize->add_setting( 'saitama_theme_options[slider'.$i.'_image]',  array(
	        'default'        	=> '',
	        'sanitize_callback' => 'esc_url_raw',
			'capability'        => 'edit_theme_options',
	        'type'           	=> 'option',
    	) );
		$wp_customize->add_control( new WP_Customize_Image_Control(
			$wp_customize,
			'slider'.$i.'_image',
			array(
				'label'     => __('Slide image', 'saitama').' '.$i,
				'section'   => 'saitama_slider',
				'settings'  => 'saitama_theme_options[slider'.$i.'_image]',
				'priority'  => $priority,
			)
		) );
    	$priority++;

		$wp_customize->add_setting( 'saitama_theme_options[slider'.$i.'_link_url]', array(
			'default'			=> '',
			'sanitize_callback'	=> 'esc_url_raw',
			'capability'        => 'edit_theme_options',
			'type'				=> 'option',
		) );
		$wp_customize->add_control( 'slider'.$i.'_link_url', array(
			'label'		=> __('Link url', 'saitama').' '.$i,
			'section'	=> 'saitama_slider',
			'settings'	=> 'saitama_theme_options[slider'.$i.'_link_url]',
			'type'		=> 'text',
			'priority'	=> $priority,
		) );
    	$priority++;

		$wp_customize->add_setting('saitama_theme_options[slider'.$i.'_blank]', array(
	    	'default'			=> false,
			'sanitize_callback' => 'saitama_sanitize_checkbox',
			'capability'        => 'edit_theme_options',
	    	'type'				=> 'option',
		) );
		$wp_customize->add_control( 'slider'.$i.'_blank', array(
			'label'		=> __('Open in new window', 'saitama').' '.$i,
			'section'	=> 'saitama_slider',
			'settings'  => 'saitama_theme_options[slider'.$i.'_blank]',
			'type'		=> 'checkbox',
			'priority'	=> $priority,
		) );
    	$priority++;

		$wp_customize->add_setting( 'saitama_theme_options[slider'.$i.'_caption]', array(
			'default'			=> '',
			'sanitize_callback'	=> 'esc_html',
			'capability'        => 'edit_theme_options',
			'type'				=> 'option',
		) );
		$wp_customize->add_control( 'slider'.$i.'_caption', array(
			'label'		=> __('Caption', 'saitama').' '.$i,
			'section'	=> 'saitama_slider',
			'settings'	=> 'saitama_theme_options[slider'.$i.'_caption]',
			'type'		=> 'textarea',
			'priority'	=> $priority,
		) );
    	$priority++;

		$wp_customize->add_setting( 'saitama_theme_options[slider'.$i.'_alt]', array(
			'default'			=> '',
			'sanitize_callback'	=> 'esc_html',
			'capability'        => 'edit_theme_options',
			'type'				=> 'option',
		) );
		$wp_customize->add_control( 'slider'.$i.'_alt', array(
			'label'		=> __('Alt Tag', 'saitama').' '.$i,
			'section'	=> 'saitama_slider',
			'settings'	=> 'saitama_theme_options[slider'.$i.'_alt]',
			'type'		=> 'text',
			'priority'	=> $priority,
		) );
    	$priority++;

	}

}
add_action( 'customize_register', 'saitama_customize_register' );

/*
function saitama_theme_options_keyColor_init() {
	if ( false === saitama_get_theme_options_keyColor() ) {
		add_option( 'saitama_theme_options_keyColor' );
	}

	register_setting(
		'saitama_options_keyColor',
		'website_theme_options_keyColor',
		'saitama_theme_options_keyColor_validate'
	);
}
add_action( 'admin_init', 'saitama_theme_options_keyColor_init' );

function saitama_get_theme_options_keyColor() {
	return get_option( 'saitama_theme_options_keyColor' );
}

function saitama_theme_options_keyColor_validate( $input ) {
	$output = $defaults;
	$output['keyColor'] = $input['keyColor'];
	$output['keyColorDark'] = $input['keyColorDark'];
	return apply_filters( 'saitama_theme_options_keyColor_validate', $output, $input, $defaults );
}
*/

function saitama_sanitize_checkbox($input){
	if($input==true){
		return true;
	} else {
		return false;
	}
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function saitama_customize_preview_js() {
	wp_enqueue_script( 'saitama_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'saitama_customize_preview_js' );

function saitama_custom_keyColor_wphead() {
	$options = get_option( 'saitama_theme_options' );
	if ( !empty($options['keyColor']) ) :
		$keyColor = $options['keyColor'];
?>
<style type="text/css">
a, a:focus, a:hover, a:active,
.blog h2 a:hover,
.blog li a:hover,
.header-v1 .topbar-v1 .top-v1-data li a:hover i,
.header-v1 .navbar-default .navbar-nav > .active > a,
.header-v1 .navbar-default .navbar-nav > li > a:hover,
.header-v1 .navbar-default .navbar-nav > li > a:focus,
.header .navbar-default .navbar-nav > .open > a,
.header .navbar-default .navbar-nav > .open > a:hover,
.header .navbar-default .navbar-nav > .open > a:focus,
.carousel-arrow a.carousel-control:hover,
.breadcrumb li.active, .breadcrumb li a:hover,
.magazine-page h3 a:hover,
.post-comment h3, .blog-item .media h3, .blog-item .media h4.media-heading span a,
.timeline-v1 > li > .timeline-badge i:hover,
.footer a, .copyright a, .footer a:hover, .copyright a:hover {
	color: <?php echo $keyColor; ?>;
}
.header-v1 .navbar-default .navbar-toggle { border-color: <?php echo $keyColor; ?>; }
.header-v1 .navbar-default .navbar-nav > li:hover > a { color: <?php echo $keyColor; ?>; border-bottom-color: <?php echo $keyColor; ?>; }
.header-v1 .dropdown-menu { border-color: <?php echo $keyColor; ?>; border-top: 2px solid <?php echo $keyColor; ?>; }
.headline h2, .headline h3, .headline h4 { border-bottom: 2px solid <?php echo $keyColor; ?>; }
.header .navbar-default .navbar-nav > .active > a { border-color: <?php echo $keyColor; ?>; }
.pagination > .active > a, .pagination > .active > span,
.pagination > .active > a:hover, .pagination > .active > span:hover,
.pagination > .active > a:focus, .pagination > .active > span:focus {
	background-color: <?php echo $keyColor; ?>;
	border-color: <?php echo $keyColor; ?>;
}
.header-v1 .navbar-toggle,
.header-v1 .navbar-default .navbar-toggle:hover,
.header-v1 .navbar-default .navbar-toggle:focus,
.btn-u,
#topcontrol:hover,
ul.blog-tags a:hover,
.blog-post-tags ul.blog-tags a:hover,
.timeline-v2 > li .cbp_tmicon {
	background: <?php echo $keyColor; ?>;
}

<?php
		if ( !empty($options['keyColorDark']) ) :
			$keyColorDark = $options['keyColorDark'];
?>
.btn-u:hover, .btn-u:focus, .btn-u:active, .btn-u.active, .open .dropdown-toggle.btn-u { background: <?php echo $keyColorDark; ?>; }
.pagination li a:hover{ background: <?php echo $keyColorDark; ?>; border-color: <?php echo $keyColorDark; ?>; }
.header-v1 .navbar-toggle:hover { background: <?php echo $keyColorDark; ?> !important; }
<?php endif; ?>
</style>
<?php
	endif;

}
add_action( 'wp_head', 'saitama_custom_keyColor_wphead' );
