<?php
class CC_FooterContact_Widget extends WP_Widget{
    function __construct() {
        parent::__construct(
            'cc_footercontact_widget',
            'CC_' . __( 'Footer Contact Area', 'cc-addon-pack' ),
            array()
        );
    }
 
    public function widget( $args, $instance ) {
        $options = ccAddonPack_get_option();

        echo $args['before_widget'];

        echo PHP_EOL.'<div class="headline"><h2>';
        if ( isset( $instance['label'] ) && $instance['label'] ) {
            echo $instance['label'];
        } else {
            _e( 'Contact Us', 'cc-addon-pack' );
        }
        echo '</h2></div>'.PHP_EOL;

        echo '<address class="md-margin-bottom-40">'.PHP_EOL;

        if ( !empty($options['contact_address']) ) {
            echo nl2br(esc_html($options['contact_address'])) . '<br />'.PHP_EOL;
        }
        if ( !empty($options['contact_tel']) ) {
            echo 'Tel: ' . esc_html($options['contact_tel']) . '<br />'.PHP_EOL;
        }
        if ( !empty($options['contact_fax']) ) {
            echo 'Fax: ' . esc_html($options['contact_fax']) . '<br />'.PHP_EOL;
        }
        if ( !empty($options['contact_email']) ) {
            $mail = antispambot($options['contact_email']);
            echo 'Email: ' . '<a href="' . esc_url('mailto:'.$mail) . '">' . $mail . '</a><br />'.PHP_EOL;
        }

        echo '</address>'.PHP_EOL;

        echo $args['after_widget'];

    }

    public function form( $instance ){
        $defaults = array(
            'label'     => __( 'Contact Us', 'cc-addon-pack' ),
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        ?>

        <br/>
        <label for="<?php echo $this->get_field_id( 'label' );  ?>"><?php _e( 'Title:' ); ?></label><br/>
        <input type="text" id="<?php echo $this->get_field_id( 'label' ); ?>-title" name="<?php echo $this->get_field_name( 'label' ); ?>" value="<?php echo $instance['label']; ?>" />
        <br/><br />
        <?php
        echo '<div style="padding:1em 0;">';
        _e( '*It is necessary to set the "Contact Information" section in "CC ADDon Pack" page.', 'cc-addon-pack' );
        echo '</div>';
        return $instance;
    }


    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['label']      = $new_instance['label'];
        return $instance;
    }

}

add_action( 'widgets_init', create_function( '', 'return register_widget("CC_FooterContact_Widget");' ) );