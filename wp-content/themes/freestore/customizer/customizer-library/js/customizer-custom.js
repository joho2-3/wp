/**
 * Freestore Customizer Custom Functionality
 *
 */
( function( $ ) {
    
    $( window ).load( function() {
        
        // Add allowed donate button
        var freestore_upgrade_button = '<a href="' + upgrade_button.link + '" class="freestore-upgrade-btn" target="_blank">' + upgrade_button.text + '</a>';
        $( '.preview-notice' ).append( freestore_upgrade_button );
        
        //Show / Hide Color selector for slider setting
        var the_slider_select_value = $( '#customize-control-freestore-slider-type select' ).val();
        freestore_customizer_slider_check( the_slider_select_value );
        
        $( '#customize-control-freestore-slider-type select' ).on( 'change', function() {
            var slider_select_value = $( this ).val();
            freestore_customizer_slider_check( slider_select_value );
        } );
        
        function freestore_customizer_slider_check( slider_select_value ) {
            if ( slider_select_value == 'freestore-slider-default' ) {
                $( '#accordion-section-freestore-slider-section #customize-control-freestore-meta-slider-shortcode' ).hide();
                $( '#accordion-section-freestore-slider-section #customize-control-freestore-slider-cats' ).show();
                $( '#accordion-section-freestore-slider-section #customize-control-freestore-slider-size' ).show();
                $( '#accordion-section-freestore-slider-section #customize-control-freestore-slider-linkto-post' ).show();
                $( '#accordion-section-freestore-slider-section #customize-control-freestore-slider-remove-title' ).show();
                $( '#accordion-section-freestore-slider-section #customize-control-freestore-slider-auto-scroll' ).show();
            } else if ( slider_select_value == 'freestore-meta-slider' ) {
                $( '#accordion-section-freestore-slider-section #customize-control-freestore-slider-cats' ).hide();
                $( '#accordion-section-freestore-slider-section #customize-control-freestore-slider-size' ).hide();
                $( '#accordion-section-freestore-slider-section #customize-control-freestore-slider-linkto-post' ).hide();
                $( '#accordion-section-freestore-slider-section #customize-control-freestore-slider-remove-title' ).hide();
                $( '#accordion-section-freestore-slider-section #customize-control-freestore-slider-auto-scroll' ).hide();
                $( '#accordion-section-freestore-slider-section #customize-control-freestore-meta-slider-shortcode' ).show();
            } else {
                $( '#accordion-section-freestore-slider-section #customize-control-freestore-slider-cats' ).hide();
                $( '#accordion-section-freestore-slider-section #customize-control-freestore-slider-size' ).hide();
                $( '#accordion-section-freestore-slider-section #customize-control-freestore-slider-linkto-post' ).hide();
                $( '#accordion-section-freestore-slider-section #customize-control-freestore-slider-remove-title' ).hide();
                $( '#accordion-section-freestore-slider-section #customize-control-freestore-slider-auto-scroll' ).hide();
                $( '#accordion-section-freestore-slider-section #customize-control-freestore-meta-slider-shortcode' ).hide();
            }
        }
        
        //Show / Hide Color selector for blocks layout setting
        var the_body_blocks_select_value = $( '#customize-control-freestore-page-styling select' ).val();
        freestore_customizer_page_style_check( the_body_blocks_select_value );
        
        $( '#customize-control-freestore-page-styling select' ).on( 'change', function() {
            var body_style_select_value = $( this ).val();
            freestore_customizer_page_style_check( body_style_select_value );
        } );
        
        function freestore_customizer_page_style_check( body_style_select_value ) {
            if ( body_style_select_value == 'freestore-page-styling-flat' ) {
                $( '#accordion-section-freestore-site-layout-section #customize-control-freestore-page-styling-color' ).hide();
            } else {
                $( '#accordion-section-freestore-site-layout-section #customize-control-freestore-page-styling-color' ).show();
            }
        }
        
        // Show / Hide Page Featured Image Layout
        var freestore_page_layout_value = $( '#customize-control-freestore-page-fimage-layout select' ).val();
        freestore_page_layout_type_check( freestore_page_layout_value );
        
        $( '#customize-control-freestore-page-fimage-layout select' ).on( 'change', function() {
            var freestore_page_select_value = $( this ).val();
            freestore_page_layout_type_check( freestore_page_select_value );
        });
        
        function freestore_page_layout_type_check( freestore_page_select_value ) {
            if ( freestore_page_select_value == 'freestore-page-fimage-layout-banner' ) {
                $( '#accordion-section-freestore-site-layout-section #customize-control-freestore-page-fimage-size' ).show();
                $( '#accordion-section-freestore-site-layout-section #customize-control-freestore-page-fimage-fullwidth' ).show();
            } else {
                $( '#accordion-section-freestore-site-layout-section #customize-control-freestore-page-fimage-size' ).hide();
                $( '#accordion-section-freestore-site-layout-section #customize-control-freestore-page-fimage-fullwidth' ).hide();
            }
        }
        
        //Show / Hide Color depending on footer selected
        var the_footer_select_value = $( '#customize-control-freestore-footer-layout select' ).val();
        freestore_customizer_footer_check( the_footer_select_value );
        
        $( '#customize-control-freestore-footer-layout select' ).on( 'change', function() {
            var footer_selected_value = $( this ).val();
            freestore_customizer_footer_check( footer_selected_value );
        } );
        
        function freestore_customizer_footer_check( footer_selected_value ) {
            if ( footer_selected_value == 'freestore-footer-layout-standard' ) {
                $( '#accordion-section-colors #customize-control-freestore-footer-bg-color' ).removeClass( 'hide-section' );
                $( '#accordion-section-colors #customize-control-freestore-footer-font-color' ).removeClass( 'hide-section' );
            } else if ( footer_selected_value == 'freestore-footer-layout-none' ) {
                $( '#accordion-section-colors #customize-control-freestore-footer-bg-color' ).addClass( 'hide-section' );
                $( '#accordion-section-colors #customize-control-freestore-footer-font-color' ).addClass( 'hide-section' );
            } else {
                $( '#accordion-section-colors #customize-control-freestore-footer-bg-color' ).addClass( 'hide-section' );
                $( '#accordion-section-colors #customize-control-freestore-footer-font-color' ).removeClass( 'hide-section' );
            }
        }
        
    } );
    
} )( jQuery );