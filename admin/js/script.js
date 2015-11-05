/**
 * Callback function for the 'click' event of the 'Set Footer Image'
 * anchor in its meta box.
 *
 * Displays the media uploader for selecting an image.
 *
 * @since 0.1.0
 */
function renderMediaUploader($) {
    'use strict';
 
    var file_frame, image_data;
 
    /**
     * If an instance of file_frame already exists, then we can open it
     * rather than creating a new instance.
     */
   // if ( undefined !== file_frame ) {
   //     file_frame = '';
   // }
 //
    /**
     * If we're this far, then an instance does not exist, so we need to
     * create our own.
     *
     * Here, use the wp.media library to define the settings of the Media
     * Uploader. We're opting to use the 'post' frame which is a template
     * defined in WordPress core and are initializing the file frame
     * with the 'insert' state.
     *
     * We're also not allowing the user to select more than one image.
     */
    file_frame = wp.media.frames.file_frame = wp.media({ 
        title: 'Product Brand Image Selector',
		button: {
			 text: 'Set Brand Image'
		}, 
        multiple: false
    });
 
    /**
     * Setup an event handler for what to do when an image has been
     * selected.
     *
     * Since we're using the 'view' state when initializing
     * the file_frame, we need to make sure that the handler is attached
     * to the insert event.
     */
    file_frame.on( 'select', function() {
 
		var json = file_frame.state().get( 'selection' ).first().toJSON();

		// First, make sure that we have the URL of an image to display
		if ( 0 > jQuery.trim( json.url.length ) ) {	
			return;
		}
		jQuery('#product-brand-image-id').val(json.id);
		// After that, set the properties of the image and display it
		jQuery( '#product-brand-image-container' )
			.children( 'img' )
			.attr( 'src', json.url )
			.attr( 'alt', json.caption )
			.attr( 'title', json.title )
			.show()
			.parent()
			.removeClass( 'hidden' );

		// Next, hide the anchor responsible for allowing the user to select an image
		jQuery( '#product-brand-image-container' )
			.prev()
			.hide();
		jQuery( '#product-brand-image-container' )
			.next()
			.show();
    });
 
    // Now display the actual file_frame
    file_frame.open();
 
}

/**
 * Callback function for the 'click' event of the 'Remove Footer Image'
 * anchor in its meta box.
 *
 * Resets the meta box by hiding the image and by hiding the 'Remove
 * Footer Image' container.
 *
 * @param    object    $    A reference to the jQuery object
 * @since    0.2.0
 */
function resetUploadForm( $ ) {
    'use strict';
 jQuery('#product-brand-image-id').val('');
    // First, we'll hide the image
    $( '#product-brand-image-container' )
        .children( 'img' )
        .hide();
 
    // Then display the previous container
    $( '#product-brand-image-container' )
        .prev()
        .show();
 
    // Finally, we add the 'hidden' class back to this anchor's parent
    $( '#product-brand-image-container' )
        .next()
        .hide()
        .addClass( 'hidden' );
 
}
 
(function( $ ) {
    'use strict';
 
    $(function() {
        $( '#set-product-brand-image' ).on( 'click', function( evt ) {
            // Stop the anchor's default behavior
            evt.preventDefault();
            // Display the media uploader
            renderMediaUploader();
 
        });
		
		$( '#remove-product-brand-image' ).on( 'click', function( evt ) {
			// Stop the anchor's default behavior
			evt.preventDefault();
			// Remove the image, toggle the anchors
			resetUploadForm( $ );
		});
    });
 
})( jQuery );