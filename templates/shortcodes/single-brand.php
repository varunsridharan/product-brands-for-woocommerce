<?php
/**
 * Single Brand
 *
 * @usedby [product_brand]
 */
?>
<a href="<?php echo get_term_link( $term,  'product_brands' ); ?>">
	<img src="<?php echo $thumbnail; ?>" alt="<?php echo $term->name; ?>" class="<?php echo $class; ?>" style="width: <?php echo $width; ?>; height: <?php echo $height; ?>;" />
</a>