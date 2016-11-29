<?php
class CC_SNS_Widget extends WP_Widget{
    function __construct() {
        parent::__construct(
            'cc_sns_widget',
            'CC_' . __( 'SNS Button', 'cc-addon-pack' ),
            array()
        );
    }
 
    public function widget( $args, $instance ) {
        $options = ccAddonPack_get_option();

        echo $args['before_widget'];

        if ( !empty($options['fburl']) ) {
            echo '<div class="margin-bottom-20">'.PHP_EOL;
            echo '<a href="' . esc_attr($options['fburl']) . '"><button class="btn rounded btn-block btn-lg btn-facebook-inversed"><i class="fa fa-facebook"></i> Facebook</button></a>'.PHP_EOL;
            echo '</div>'.PHP_EOL;
        }

        if ( !empty($options['twId']) ) {
            echo '<div>'.PHP_EOL;
            echo '<a href="https://twitter.com/' . esc_attr($options['twId']) . '"><button class="btn rounded btn-block btn-lg btn-twitter-inversed"><i class="fa fa-twitter"></i> Twitter</button></a>'.PHP_EOL;
            echo '</div>'.PHP_EOL;
        }

        echo $args['after_widget'];
    }

    public function form( $instance ){
        echo '<div style="padding:1em 0;">';
        _e( '*It is necessary to set the "SNS Information" section in "CC Addon Pack" page.', 'cc-addon-pack' );
        echo '</div>';
        return $instance;
    }


    function update($new_instance, $old_instance) {
        return $new_instance;
    }

}

add_action( 'widgets_init', create_function( '', 'return register_widget("CC_SNS_Widget");' ) );