jQuery(function($){
    console.log("Init Beatnik Promo");
    // Set all variables to be used in scope
    var beatnik_media_frame,
        metaBox = $('#beatnik_promo_box.postbox'), // Your meta box id here
        addImgLinks = metaBox.find('.upload-beatnik_image');
        deleteImgLinks = metaBox.find(".delete-beatnik_image");

    // ADD IMAGE LINK
    addImgLinks.on( 'click', function( e ) {
        e.preventDefault();
        // If the media beatnik_media_frame already exists, reopen it.
        if ( beatnik_media_frame ) {
            beatnik_media_frame.open();
            // return;
        } else {
            // Create a new media beatnik_media_frame
            beatnik_media_frame = wp.media({
                title: 'Select or Upload Media Of Your Chosen Persuasion',
                button: {
                    text: 'Use this media'
                },
                multiple: false  // Set to true to allow multiple files to be selected
            });
        }
        // When an image is selected in the media beatnik_media_frame...
        beatnik_media_frame.off( 'select' );
        beatnik_media_frame.on( 'select', function() {
            var parent_container = $(e.currentTarget).parents(".beatnik_tile");
            var target = parent_container.data("tile_target");
            var id = parent_container.data("tile_id");
            console.log({ id });
            var addImgLink = parent_container.find('.upload-beatnik_' + target + '_logo'),
                delImgLink = parent_container.find( '.delete-beatnik_' + target + '_logo'),
                imgContainer = parent_container.find( '.beatnik_' + target + '_img_container'),
                imgIdInput = parent_container.find( '.beatnik_' + target + '_img_id' );
            console.log({ id, target });
            // Get media attachment details from the beatnik_media_frame state
            var attachment = beatnik_media_frame.state().get('selection').first().toJSON();
            // Send the attachment URL to our custom image input field.
            imgContainer.append( '<img src="'+ attachment.url +'" alt="" style="max-width:300px;"/>' );
            // Send the attachment id to our hidden input
            imgIdInput.val( attachment.id );
            // Hide the add image link
            addImgLink.addClass( 'hidden' );
            // Unhide the remove image link
            delImgLink.removeClass( 'hidden' );
        });
        // Finally, open the modal on click
        beatnik_media_frame.open();
    });

    // DELETE IMAGE LINK
    metaBox.on('click', ".delete-beatnik_sponsor_logo", function( e ) {
        e.preventDefault();
        var parent_container = $(e.currentTarget).parents(".beatnik_tile");
        var id = parent_container.data("tile_id");
        var addImgLink = parent_container.find('.upload-beatnik_sponsor_logo'),
            delImgLink = parent_container.find( '.delete-beatnik_sponsor_logo'),
            imgContainer = parent_container.find( '.beatnik_sponsor_img_container'),
            imgIdInput = parent_container.find( '.beatnik_sponsor_img_id' );
        // Clear out the preview image
        imgContainer.empty();
        // Un-hide the add image link
        addImgLink.removeClass( 'hidden' );
        // Hide the delete image link
        delImgLink.addClass( 'hidden' );
        // Delete the image id from the hidden input
        imgIdInput.val( '' );
    });

    metaBox.on('click', ".delete-beatnik_background_img", function( e ) {
        e.preventDefault();
        console.log("delete-beatnik_background_img");
        var parent_container = $(e.currentTarget).parents(".beatnik_tile");
        var id = parent_container.data("tile_id");
        var addImgLink = parent_container.find('.upload-beatnik_background_img'),
            delImgLink = parent_container.find( '.delete-beatnik_background_img'),
            imgContainer = parent_container.find( '.beatnik_background_img_container'),
            imgIdInput = parent_container.find( '.beatnik_background_img_id' );
        // Clear out the preview image
        imgContainer.empty();
        // Un-hide the add image link
        addImgLink.removeClass( 'hidden' );
        // Hide the delete image link
        delImgLink.addClass( 'hidden' );
        // Delete the image id from the hidden input
        imgIdInput.val( '' );
    });

    metaBox.on('click', ".delete-beatnik_blurb_img", function( e ) {
        e.preventDefault();
        console.log("delete-beatnik_blurb_img");
        var parent_container = $(e.currentTarget).parents(".beatnik_tile");
        var id = parent_container.data("tile_id");
        var addImgLink = parent_container.find('.upload-beatnik_blurb_img'),
            delImgLink = parent_container.find( '.delete-beatnik_blurb_img'),
            imgContainer = parent_container.find( '.beatnik_blurb_img_container'),
            imgIdInput = parent_container.find( '.beatnik_blurb_img_id' );
        // Clear out the preview image
        imgContainer.empty();
        // Un-hide the add image link
        addImgLink.removeClass( 'hidden' );
        // Hide the delete image link
        delImgLink.addClass( 'hidden' );
        // Delete the image id from the hidden input
        imgIdInput.val( '' );
    });
});
