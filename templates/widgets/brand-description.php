<?php
/**
 * Show a brands description when on a taxonomy page
 */
?>
<?php global $woocommerce; ?>

<?php if ( $thumbnail ) : ?>

	<?php echo '<img src="'.$thumbnail.'" alt="'.$brand->name.'"/>'; ?>

<?php endif; ?>

<?php echo wpautop( wptexturize( term_description() ) ); ?>
