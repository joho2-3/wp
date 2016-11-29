<?php
class CC_TopicArea_Widget extends WP_Widget{
    function __construct() {
        parent::__construct(
            'cc_topicarea_widget',
            'CC_' . __( 'Topic Area', 'cc-addon-pack' ),
            array()
        );
    }
 
    public function widget( $args, $instance ) {
        $options = ccAddonPack_get_option();

        echo $args['before_widget'];

        echo PHP_EOL.'<div class="magizine-news">'.PHP_EOL;
        echo '<div class="row">'.PHP_EOL;

        // PR_1
        echo '<div class="col-md-6 col-sm-6">'.PHP_EOL;
        if ( !empty($options['topic1_img']) ) {
            echo '<div class="magazine-news-img">'.PHP_EOL;
            echo '<a href="' . $options['topic1_link'] . '"><img class="img-responsive" src="' . $options['topic1_img'] . '" alt="' . $options['topic1_title'] . '" /></a>'.PHP_EOL;
            if ( !empty($options['topic1_subtitle']) ) {
                echo '<span class="magazine-badge label-yellow">' . $options['topic1_subtitle'] . '</span>'.PHP_EOL;
            }
            echo '</div>'.PHP_EOL;
        }
        echo '<h3><a href="' . $options['topic1_link'] . '">' . $options['topic1_title'] . '</a></h3>'.PHP_EOL;
        echo '<p>' . nl2br($options['topic1_desc']) . '</p>'.PHP_EOL;
        echo '</div>'.PHP_EOL;

        // PR_2
        echo '<div class="col-md-6 col-sm-6">'.PHP_EOL;
        if ( !empty($options['topic2_img']) ) {
            echo '<div class="magazine-news-img">'.PHP_EOL;
            echo '<a href="' . $options['topic2_link'] . '"><img class="img-responsive" src="' . $options['topic2_img'] . '" alt="' . $options['topic2_title'] . '" /></a>'.PHP_EOL;
            if ( !empty($options['topic2_subtitle']) ) {
                echo '<span class="magazine-badge label-yellow">' . $options['topic2_subtitle'] . '</span>'.PHP_EOL;
            }
            echo '</div>'.PHP_EOL;
        }
        echo '<h3><a href="' . $options['topic2_link'] . '">' . $options['topic2_title'] . '</a></h3>'.PHP_EOL;
        echo '<p>' . nl2br($options['topic2_desc']) . '</p>'.PHP_EOL;
        echo '</div>'.PHP_EOL;

        echo '</div>'.PHP_EOL;
        echo '</div>'.PHP_EOL;
        echo '<hr />'.PHP_EOL;

        echo $args['after_widget'];

    }


    public function form( $instance ){
        echo '<div style="padding:1em 0;">';
        _e( '*It is necessary to set the "Toppage Setting" section in "CC ADDon Pack" page.', 'cc-addon-pack' );
        echo '</div>';
        return $instance;
    }


    function update($new_instance, $old_instance) {
        return $new_instance;
    }

}

add_action( 'widgets_init', create_function( '', 'return register_widget("CC_TopicArea_Widget");' ) );