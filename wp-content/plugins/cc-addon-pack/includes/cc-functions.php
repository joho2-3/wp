<?php
/**
 * Disabled Emoji
 */
function ccAddonPack_disabled_emoji() {
    $options = ccAddonPack_get_option();

	if ( isset( $options['disabled_emoji'] ) && $options['disabled_emoji'] ) {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );	
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	}

}
add_action( 'init', 'ccAddonPack_disabled_emoji' );

/**
 * Output Favicon Tag
 */
function ccAddonPack_set_favicon_tag() {
    $options = ccAddonPack_get_option();

    if ( !empty($options['favicon']) ) {
		echo '<link rel="shortcut icon" href="' . esc_attr($options['favicon']) . '" type="image/x-icon"/>'."\n";
	}
}
add_action( 'wp_head', 'ccAddonPack_set_favicon_tag' );

/**
 * Set Default Thumbnail Image
 */
function ccAddonPack_default_thumbnail_image( $html, $post_id, $post_thumbnail_id, $size, $attr) {
    if ( !empty($html) ) return $html;

    $options = ccAddonPack_get_option();
    if ( !empty($options['default_thumbnail']) ) {
	    $post_thumbnail_id = $options['default_thumbnail'];

	    $html = wp_get_attachment_image( $post_thumbnail_id, $size, false, $attr );
	    return $html;
	 }
}
add_filter( 'post_thumbnail_html', 'ccAddonPack_default_thumbnail_image', 10, 5 );

/**
 * Output Google Analytics Tag
 */
function ccAddonPack_set_ga_tag() {
    $options = ccAddonPack_get_option();

	if ( !empty($options['gaId']) ) :
		$url = site_url();
		$url = str_replace( array('http://', 'https://'), '', $url );
?>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-<?php echo $options['gaId']; ?>', '<?php echo $url; ?>');
ga('send', 'pageview');
</script>
<?php
	endif;
}
add_action( 'wp_head', 'ccAddonPack_set_ga_tag' );

/**
 * Add Meta Keyword Field
 */
function ccAddonPack_add_meta_keyword_field() {
    $options = ccAddonPack_get_option();

	if ( !$options['active_meta_keyword'] ) return;

	$post_types = get_post_types( array(),'objects' );
	foreach ( $post_types as $post ) {
		if ( $post->_builtin ) { continue; }
		if ( ! $post->public ) { continue; }
		add_meta_box( 'cc-meta-keyword', __( 'Meta Keywords', 'cc-addon-pack' ), 'ccAddonPack_insert_metaKeyword', $post->name, 'normal', 'high' );
	}
	add_meta_box( 'cc-meta-keyword', __( 'Meta Keywords', 'cc-addon-pack' ), 'ccAddonPack_insert_metaKeyword', 'page', 'normal', 'high' );
	add_meta_box( 'cc-meta-keyword', __( 'Meta Keywords', 'cc-addon-pack' ), 'ccAddonPack_insert_metaKeyword', 'post', 'normal', 'high' );
}
add_action( 'admin_menu', 'ccAddonPack_add_meta_keyword_field' );

/**
 * Add Meta Keyword Field In Edit Page
 */
function ccAddonPack_insert_metaKeyword() {
  global $post;
  echo '<input type="hidden" name="noncename_custom_field_metaKey" id="noncename_custom_field_metaKey" value="'.wp_create_nonce(plugin_basename(__FILE__)).'" />';
  echo '<label class="hidden" for="ccAddonPack_metaKeyword">'.__('Meta Keywords', 'cc-addon-pack').'</label><input type="text" name="ccAddonPack_metaKeyword" size="50" value="'.get_post_meta($post->ID, 'ccAddonPack_metaKeyword', true).'" />';
  echo '<p>'.__('To distinguish between individual keywords, please enter a , delimiter (optional).', 'cc-addon-pack').'</p>';
}

function ccAddonPack_save_meta_keyword( $post_id ) {
	$metaKeyword = isset( $_POST['noncename_custom_field_metaKey'] ) ? htmlspecialchars( $_POST['noncename_custom_field_metaKey'] ) : null;

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;

	if ( ! wp_verify_nonce( $metaKeyword, plugin_basename( __FILE__ ) ) ) return $post_id;

	if ( 'page' == $_POST['ccAddonPack_metaKeyword'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) { return $post_id; }
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) { return $post_id; }
	}

	$data = $_POST['ccAddonPack_metaKeyword'];

	if ( get_post_meta( $post_id, 'ccAddonPack_metaKeyword' ) == '' ) {
		add_post_meta( $post_id, 'ccAddonPack_metaKeyword', $data, true );
	} elseif ( $data != get_post_meta( $post_id, 'ccAddonPack_metaKeyword', true ) ) {
		update_post_meta( $post_id, 'ccAddonPack_metaKeyword', $data );
	} elseif ( $data == '' ) {
		delete_post_meta( $post_id, 'ccAddonPack_metaKeyword', get_post_meta( $post_id, 'ccAddonPack_metaKeyword', true ) );
	}
}
add_action( 'save_post' , 'ccAddonPack_save_meta_keyword' );

/**
 * Add Meta Description Field
 */
function ccAddonPack_add_meta_description_field() {
	$options = ccAddonPack_get_option();

	if ( !$options['active_meta_description'] ) return;

	$post_types = get_post_types( array(),'objects' );
	foreach ( $post_types as $post ) {
		if ( $post->_builtin ) { continue; }
		if ( ! $post->public ) { continue; }
		add_meta_box( 'cc-meta-description', __( 'Meta Description', 'cc-addon-pack' ), 'ccAddonPack_insert_metaDesc', $post->name, 'normal', 'high' );
	}
	add_meta_box( 'cc-meta-description', __( 'Meta Description', 'cc-addon-pack' ), 'ccAddonPack_insert_metaDesc', 'page', 'normal', 'high' );
	add_meta_box( 'cc-meta-description', __( 'Meta Description', 'cc-addon-pack' ), 'ccAddonPack_insert_metaDesc', 'post', 'normal', 'high' );
}
add_action( 'admin_menu', 'ccAddonPack_add_meta_description_field' );

/**
 * Add Meta Description Field In Edit Page
 */
function ccAddonPack_insert_metaDesc() {
  global $post;
  echo '<input type="hidden" name="noncename_custom_field_metaDesc" id="noncename_custom_field_metaDesc" value="'.wp_create_nonce(plugin_basename(__FILE__)).'" />';
  echo '<label class="hidden" for="ccAddonPack_metaDescription">'.__('Meta Description', 'cc-addon-pack').'</label><textarea cols="80" rows="3" name="ccAddonPack_metaDescription">'.get_post_meta($post->ID, 'ccAddonPack_metaDescription', true).'</textarea>';
}

function ccAddonPack_save_meta_description( $post_id ) {
	$metaDescription = isset( $_POST['noncename_custom_field_metaDesc'] ) ? htmlspecialchars( $_POST['noncename_custom_field_metaDesc'] ) : null;

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;

	if ( ! wp_verify_nonce( $metaDescription, plugin_basename( __FILE__ ) ) ) return $post_id;

	if ( 'page' == $_POST['ccAddonPack_metaDescription'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) { return $post_id; }
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) { return $post_id; }
	}

	$data = $_POST['ccAddonPack_metaDescription'];

	if ( get_post_meta( $post_id, 'ccAddonPack_metaDescription' ) == '' ) {
		add_post_meta( $post_id, 'ccAddonPack_metaDescription', $data, true );
	} elseif ( $data != get_post_meta( $post_id, 'ccAddonPack_metaDescription', true ) ) {
		update_post_meta( $post_id, 'ccAddonPack_metaDescription', $data );
	} elseif ( $data == '' ) {
		delete_post_meta( $post_id, 'ccAddonPack_metaDescription', get_post_meta( $post_id, 'ccAddonPack_metaDescription', true ) );
	}
}
add_action( 'save_post' , 'ccAddonPack_save_meta_description' );

/**
 * Output Meta Keyword Tag
 */
function ccAddonPack_set_meta_keyword() {
	$options = ccAddonPack_get_option();

	if ( !$options['active_meta_keyword'] ) return;

	$post_id = get_the_ID();

	if ( empty($post_id) ) {
		$key = $options['common_keywords'];

	} else {
		$keys = array();
		$common = $options['common_keywords'];
		$page = get_post_meta( $post_id, 'ccAddonPack_metaKeyword', true );
		if ( $common ) $keys[] = $common;
		if ( $page ) $keys[] = $page;
		$key = implode(',', $keys);

	}

	if ( empty($key) ) return;

	echo '<meta name="keywords" content="' . esc_attr($key) . '" />'."\n";

}
add_action( 'wp_head', 'ccAddonPack_set_meta_keyword' );

/**
 * Output Meta Description Tag
 */
function ccAddonPack_set_meta_description() {
	$options = ccAddonPack_get_option();

	if ( !$options['active_meta_description'] ) return;

	$post_id = get_the_ID();

	if ( empty($post_id) ) {
		$desc = $options['common_description'];

	} else {
		$desc = get_post_meta( $post_id, 'ccAddonPack_metaDescription', true );
		if ( empty($desc) ) $desc = $options['common_description'];
	}

	if ( empty($desc) ) return;

	echo '<meta name="description" content="' . esc_attr($desc) . '" />'."\n";
}
add_action( 'wp_head', 'ccAddonPack_set_meta_description' );

function ccAddonPack_set_fb() {
	$options = ccAddonPack_get_option();

	if ( !$options['active_og'] ) return;	

	$title = ccAddonPack_rtn_title();
	$link = ( is_home() || is_front_page() ) ? home_url() : get_permalink();
	$desc = ccAddonPack_rtn_description();

	echo '<meta property="og:site_name" content="'.get_bloginfo( 'name' ).'" />'."\n";
	echo '<meta property="og:url" content="'.$link.'" />'."\n";
	echo '<meta property="og:title" content="'.$title.'" />'."\n";
	echo '<meta property="og:description" content="'.$desc.'" />'."\n";

	if ( is_home() || is_front_page() ) {
		echo '<meta property="og:type" content="website" />'."\n";

		if ( isset( $options['ogimg'] ) && $options['ogimg'] ) {
			echo '<meta property="og:image" content="'.esc_url($options['ogimg']).'" />'."\n";
		}

	} else {
		echo '<meta property="og:type" content="article" />'."\n";

		if ( has_post_thumbnail() ) {
			$img_id = get_post_thumbnail_id();
			$img_url = wp_get_attachment_image_src( $img_id,'large', true );
			echo '<meta property="og:image" content="'.$img_url[0].'" />'."\n";

		} elseif ( isset( $options['ogimg'] ) && $options['ogimg'] ) {
			echo '<meta property="og:image" content="'.esc_url($options['ogimg']).'" />'."\n";

		}

	}

}
add_action( 'wp_head', 'ccAddonPack_set_fb' );

function ccAddonPack_rtn_title() {
	$sep = ' | ';
	$title = get_bloginfo('name');

	if ( is_home() || is_front_page() )	{
		$title .= $sep.get_bloginfo('description');
	} elseif ( is_archive() ) {
		$title = get_the_archive_title().$sep.$title;
	} elseif ( is_page() || is_single() ) {
		$title = get_the_title().$sep.$title;
	}

	return $title;
}

function ccAddonPack_rtn_description() {
	global $wp_query;
	$post = $wp_query->get_queried_object();
	$desc = '';

	if ( is_page() || is_single() ) {
		$desc = ($post->post_excerpt) ? $post->post_excerpt : mb_substr( strip_tags( $post->post_content ), 0, 240 );
	} else {
		$desc = get_bloginfo( 'description' );
	}

	return $desc;
}

function ccAddonPack_rtn_header_text() {
	$options = ccAddonPack_get_option();

	$html = '<ul class="list-inline top-v1-contacts">';
	if ( !empty($options['contact_email']) ) {
		$html .= '<li><i class="fa fa-envelope"></i> Email: <a href="mailto:' . esc_html($options['contact_email']) . '">' . esc_html($options['contact_email']) . '</a></li>';
	}
	if ( !empty($options['contact_tel']) ) {
		$html .= '<li><i class="fa fa-phone"></i> TEL: ' . esc_html($options['contact_tel']) . '</li>';
	}
	$html .= '</ul>';

	return $html;
}
add_filter( 'cc_header_text', 'ccAddonPack_rtn_header_text' );