<?php
/**
 * Show a grid of thumbnails
 */
?>
<ul class="pbf_wc_brand-thumbnails">
	
	<?php foreach ( $brands as $index => $brand ) : 
		
		$thumbnail = $image_size;

		$image = get_woocommerce_term_meta( $brand->term_id, 'thumbnail_id', true );

		$image_url = wp_get_attachment_image($image,$thumbnail, array('alt' => $brand->name)); 

		if ( ! $thumbnail )
			$thumbnail = woocommerce_placeholder_img_src();
		
		$class = '';
		
		if ( $index == 0 || $index % $columns == 0 )
			$class = 'first';
		elseif ( ( $index + 1 ) % $columns == 0 )
			$class = 'last';
			
		$width = floor( ( ( 100 - ( ( $columns - 1 ) * 2 ) ) / $columns ) * 100 ) / 100;
		?>
		<li class="<?php echo $class; ?>" style="width: <?php echo $width; ?>%;">
			<a href="<?php echo get_term_link( $brand->slug, 'product_brands' ); ?>" title="<?php echo $brand->name; ?>">
				<?php echo $image_url; ?>
			</a>
		</li>

	<?php endforeach; ?>
	
</ul>