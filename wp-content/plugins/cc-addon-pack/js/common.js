/*-------------------------------------------*/
/* メディアアップローダー
/*-------------------------------------------*/
jQuery(document).ready(function($){
    var custom_uploader;

    jQuery('.media_btn').click(function(e) {
        media_target = jQuery(this).attr('id').replace(/media_/g,'#');
        e.preventDefault();
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        custom_uploader = wp.media({
            title: 'Choose Image',
            library: {
                type: 'image'
            },
            button: {
                text: 'Choose Image'
            },
            multiple: false,
        });
        custom_uploader.on('select', function() {
            var images = custom_uploader.state().get('selection');
            images.each(function(file){
                if ( media_target == '#default_thumbnail' ) {
                    jQuery('#default_thumbnail_image').html('<img src="'+file.attributes.sizes.thumbnail.url+'" />');
                    jQuery(media_target).attr('value', file.toJSON().id );
                } else {
                    jQuery(media_target).attr('value', file.toJSON().url );
                }
            });
        });
        custom_uploader.open();
    });

});
