<?php
class CC_FooterNavi_Widget extends WP_Widget{
    function __construct() {
        parent::__construct(
            'cc_footernavi_widget',
            'CC_' . __( 'Footer Navi', 'cc-addon-pack' ),
            array()
        );
    }
 

    public function widget( $args, $instance ) {
        $nav_menu = ! empty( $instance['menu'] ) ? wp_get_nav_menu_object( $instance['menu'] ) : false;
 
        if ( !$nav_menu ) return;
 
        echo $args['before_widget'];

        $menu_args = array(
            'menu'              => $nav_menu,
            'container'         => '',
            'after'             => '<i class="fa fa-angle-right"></i>',
            'items_wrap'        => '<ul class="list-unstyled link-list">%3$s</ul>',
            'echo'              => false,
            'fallback_cb'       => '',
        );

        echo PHP_EOL.'<div class="headline"><h2>';
        if ( isset( $instance['label'] ) && $instance['label'] ) {
            echo $instance['label'];
        } else {
            __( 'Link', 'cc-addon-pack' );
        }
        echo '</h2></div>'.PHP_EOL;

        echo wp_nav_menu( $menu_args );

        echo $args['after_widget'];
    }


    public function form( $instance ){
        $defaults = array(
            'label'     => __( 'Link', 'cc-addon-pack' ),
            'menu'      => ''
        );
        $instance = wp_parse_args( (array) $instance, $defaults );

        $menus = wp_get_nav_menus();
        $url = admin_url( 'nav-menus.php' );
        ?>
        <p class="nav-menu-widget-no-menus-message" <?php if ( ! empty( $menus ) ) { echo ' style="display:none" '; } ?>>
            <?php echo sprintf( __( 'No menus have been created yet. <a href="%s">Create some</a>.' ), esc_attr( $url ) ); ?>
        </p>
        <div class="nav-menu-widget-form-controls" style="padding:1em 0; <?php if ( empty( $menus ) ) { echo 'display:none'; } ?>">
        <label for="<?php echo $this->get_field_id( 'label' );  ?>"><?php _e( 'Title:' ); ?></label><br/>
        <input type="text" id="<?php echo $this->get_field_id( 'label' ); ?>-title" name="<?php echo $this->get_field_name( 'label' ); ?>" value="<?php echo $instance['label']; ?>" />
        <br/><br/>

        <label for="<?php echo $this->get_field_id( 'menu' ); ?>"><?php _e( 'Select Menu:' ); ?></label>
        <select id="<?php echo $this->get_field_id( 'menu' ); ?>" name="<?php echo $this->get_field_name( 'menu' ); ?>">
            <option value="0"><?php _e( '&mdash; Select &mdash;' ); ?></option>
            <?php foreach ( $menus as $menu ) : ?>
                <option value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $instance['menu'], $menu->term_id ); ?>>
                    <?php echo esc_html( $menu->name ); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br/><br/>
        </div>
        <?php
        return $instance;
    }


    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['label'] = $new_instance['label'];
        $instance['menu'] = $new_instance['menu'];
        return $instance;
    }

}

add_action( 'widgets_init', create_function( '', 'return register_widget("CC_FooterNavi_Widget");' ) );