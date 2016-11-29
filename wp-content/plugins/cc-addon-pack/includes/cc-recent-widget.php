<?php
class CC_Recent_Widget extends WP_Widget{
    function __construct() {
        parent::__construct(
            'cc_recent_widget',
            'CC_' . __( 'Recent Post', 'cc-addon-pack' ),
            array()
        );
    }
 

    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        echo PHP_EOL.'<div class="posts">'.PHP_EOL;
        echo '<div class="headline"><h2>';
        if ( isset( $instance['label'] ) && $instance['label'] ) {
            echo $instance['label'];
        } else {
            _e( 'Recent Post', 'cc-addon-pack' );
        }
        echo '</h2></div>'.PHP_EOL;
        echo '<ul class="list-unstyled latest-list">'.PHP_EOL;

        $count      = ( isset( $instance['count'] ) && $instance['count'] ) ? $instance['count'] : 5;
        $post_type  = ( isset( $instance['post_type'] ) && $instance['post_type'] ) ? $instance['post_type'] : 'post';

        $recent_posts = new WP_Query( array(
            'post_type' => $post_type,
            'posts_per_page' => $count,
            'paged' => 1,
            'orderby' => 'date',
            'order' => 'DESC',
        ) );

        while ( $recent_posts->have_posts() ) : $recent_posts->the_post();
            echo '<li>'.PHP_EOL;
            echo '<a href="' . get_the_permalink() . '">' . get_the_title() . '</a>'.PHP_EOL;
            echo '<small>' . get_the_date() . '</small>'.PHP_EOL;
            echo '</li>'.PHP_EOL;
            echo '</li>'.PHP_EOL;
        endwhile;

        echo '</ul>'.PHP_EOL;
        echo '</div>'.PHP_EOL;

        echo $args['after_widget'];

        wp_reset_postdata();
        wp_reset_query();
    }


    public function form( $instance ){
        $defaults = array(
            'label'     => __( 'Recent Post', 'cc-addon-pack' ),
            'count'     => 5,
            'post_type' => 'post',
        );

        $instance = wp_parse_args( (array) $instance, $defaults );
        ?>
        <div style="padding:1em 0;">
        <label for="<?php echo $this->get_field_id( 'label' );  ?>"><?php _e( 'Title:' ); ?></label><br/>
        <input type="text" id="<?php echo $this->get_field_id( 'label' ); ?>-title" name="<?php echo $this->get_field_name( 'label' ); ?>" value="<?php echo $instance['label']; ?>" />
        <br/><br/>

        <label for="<?php echo $this->get_field_id( 'count' );  ?>"><?php _e( 'Display count','cc-addon-pack' ); ?>:</label><br/>
        <input type="text" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" value="<?php echo $instance['count']; ?>" />
        <br/><br/>

        <label for="<?php echo $this->get_field_id( 'post_type' ); ?>"><?php _e( 'Post type', 'cc-addon-pack' ) ?>:</label><br />
        <input type="text" id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>" value="<?php echo esc_attr( $instance['post_type'] ) ?>" />
        <br/><br/>
        </div>
        <?php
        return $instance;
    }


    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['label']      = $new_instance['label'];
        $instance['count']      = $new_instance['count'];
        $instance['post_type']  = ! empty( $new_instance['post_type'] ) ? strip_tags( $new_instance['post_type'] ) : 'post';
        return $instance;
    }

}

add_action( 'widgets_init', create_function( '', 'return register_widget("CC_Recent_Widget");' ) );