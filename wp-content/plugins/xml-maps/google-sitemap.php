<?php
/*
Plugin Name: Xml Sitemap
Plugin URI: http://jmplugins.weebly.com
Description: The plugin will generate an XML sitemap of your WordPress website and ping the most popular search engines: Google, Bing, and Ask.com.
Version: 1.3
Author: Jason Martens
License: GPL2
*/


function build()
{
        require(dirname(__FILE__).'/sitemap.class.php');
        $s = new sitemap;
        $s->build();
}
add_action('admin_menu', 'sitemap_create_menu');


add_action('${new_status}_$post->post_type','build',100,1);
add_action('sm_build_cron', 'build',100,1);

function sitemap_create_menu() {
	add_options_page('Sitemap', 'Xml Sitemap Settings', 'administrator', __FILE__, 'sitemap_settings_page','', __FILE__);
	add_action( 'admin_init', 'register_mysettings' );
}


function register_mysettings() {	
	$settings = array(
			  'priority' =>  array('Homepage' => '1.0','Posts' => '0.8','Pages' => '0.8','Categories' => '0.6','Archives' => '0.8','Tags' => '0.6','Author' => '0.3'),
			  'filename' => 'sitemap',
			  'zip' => true,
			  'google' => true,
			  'ask' => true,
			  'bing' => true
			  );
        foreach($settings as $setting => $value){
            register_setting( 'sitemap-settings-group', $setting );
	    if(get_option($setting) == ''){
		update_option( $setting, $value );
	    }
        }     
}

function sitemap_settings_page() {        
    if(!empty($_POST) and isset($_POST['save'])){
	$priority = array();
	$excl_array = array('option_page','action','_wpnonce','_wp_http_referer'); 
	foreach($_POST as $i => $v){
		if(!in_array($i,$excl_array))$priority[$i] = $v;
	}
	
	update_option( 'priority', $priority );
    }  
?>
<div class="wrap">
<h2>Xml Sitemap</h2>
<?php
wp_nonce_field('update-options');

if(isset($_POST['build'])){
  require(dirname(__FILE__).'/sitemap.class.php');
  $sitemap = new sitemap();
  echo $sitemap->build();
}else{
	$filename = get_option('filename');
	if(file_exists(ABSPATH.'/'.$filename.'.xml')){
	$lastbuild = filemtime(ABSPATH.'/'.$filename.'.xml');
	echo '<p><strong>Your sitemap has built:</strong>'.date_i18n('Y.m.d h:i:s',$lastbuild).'</p>';
	}
}
?>
    <h2>Actions</h2>
    <div class="inside">
	<form action="" method="post">
		<p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Build Sitemap') ?>" name="build"/>
    </p>
	</form>
    </div>

<form method="post" action="">
    <?php settings_fields( 'sitemap-settings-group' ); ?>

    <h2>Settings</h2>
    <div class="inside">
	<ul>
		<li><label>Gzip sitemap? <input type="checkbox" name="zip"
		<?php if(get_option('zip'))echo ' checked="checked" ' ?>
		/></label></li>
		<li><label>Ping Google? <input type="checkbox" name="ping"
		<?php if(get_option('google'))echo ' checked="checked" ' ?>
		/></label></li>
		<li><label>Ping Ask.com? <input type="checkbox" name="ping"
		<?php if(get_option('ask'))echo ' checked="checked" ' ?>
		/></label></li>
		<li><label>Ping Bing? <input type="checkbox" name="ping"
		<?php if(get_option('bing'))echo ' checked="checked" ' ?>
		/></label></li>
		
		<li><label>Filename <input type="text" name="ping" value="<?php echo get_option('filename'); ?>" /></label></li>
	</ul>
    </div>

    <h2>Priority</h2>
    <div class="inside">
        <ul>
            <?php
            $priority = get_option('priority');
            foreach($priority as $v => $i){
                $a = (float)0.0;
                echo '<li><label><select name="'.$v.'">';
               for($a=0.0; $a<=1.0; $a+=0.1) {
                    //$a = number_format($a,".","");
		    $ov = number_format($a,1,".","");
		    echo '<option value="'.$ov.'"';
                    if($ov == $i)echo ' selected="selected" ';
                    echo '>'.$ov.'</option>';
                    
                }
                echo "</select>$v</label></li>";
                
            }
            ?>
        </ul>
    </div>

    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" name="save"/>
    </p>

</form>
</div>
<?php } ?>