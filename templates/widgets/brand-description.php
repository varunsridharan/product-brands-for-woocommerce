<?php
/**
 * Show a brands description when on a taxonomy page
 */
?>
<?php global $woocommerce; ?>

<?php if ( $thumbnail ) : ?>

	<img src="<?php echo $thumbnail; ?>" alt="Thumbnail" class="wp-post-image aligncenter" />

<?php endif; ?>

<?php echo wpautop( wptexturize( term_description() ) ); ?>
