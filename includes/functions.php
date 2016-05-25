<?php

if(!function_exists('pbf_wc_get_brand_thumbnail_url')){
    /**
     * Helper function :: pbf_wc_get_brand_thumbnail_url function.
     *
     * @access public
     * @return string
     */
    function pbf_wc_get_brand_thumbnail_url( $brand_id, $size = 'full' ) {
        $thumbnail_id = get_woocommerce_term_meta( $brand_id, 'thumbnail_id', true );

        if ( $thumbnail_id )
            $thumb_src = wp_get_attachment_image_src( $thumbnail_id, $size );
            if ( ! empty( $thumb_src ) ) {
                return current( $thumb_src );
            }
    }    
}


if(!function_exists('pbf_wc_name')){
    function pbf_wc_name(){
        $name = get_option(PBF_WC_DB.'custom_brands_name',true);
        if(empty($name)){$name = "Product Brands";}
        return $name;
    }
}

if(!function_exists('pbf_wc_url_slug')){
    function pbf_wc_url_slug(){
        $slug = get_option(PBF_WC_DB.'custom_url_slug',true);
        if(empty($slug)){$slug = "Product Brands";}
        return sanitize_text_field($slug);
        
    }
}
?>