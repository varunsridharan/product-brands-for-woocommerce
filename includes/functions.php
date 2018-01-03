<?php
global $pbf_options;

$pbf_options = array();

if(!function_exists("pbf_wc_get_db_options")){
    function pbf_wc_get_db_options(){
        global $pbf_options;
        
        if(empty($pbf_options)){
            $pbf_options = get_option("_product_brands_wc",true);
        }
    }
}

if(!function_exists("pbf_wc_option")){
   
    function pbf_wc_option($key,$default = ''){
        global $pbf_options;
        
        pbf_wc_get_db_options();
        
        if(isset($pbf_options[$key])){
            return $pbf_options[$key];
        }
        
        return $default;
    } 
}

if(!function_exists('pbf_wc_name')){
    function pbf_wc_name($plural = false){
        if($plural){
            $name = pbf_wc_option('plural_name',false);
        } else {
            $name = pbf_wc_option('singular_name',false);
        }
            
        if(empty($name)){
            $name = "Product Brand";
        }
        
        return $name;
    } 
}

if(!function_exists('pbf_wc_url_slug')){
    function pbf_wc_url_slug(){
        $slug = pbf_wc_option("url_slug");
        if(empty($slug)){$slug = "product-brands";}
        return sanitize_text_field($slug);
        
    }
}

if(!function_exists("pbf_wc_term_thumbnail_id")){
    function pbf_wc_term_thumbnail_id($term_id = ''){
        
        $return = vsp_get_term_meta($term_id,'_pbf_wc_meta',true);
        if(empty($return)){
            $ex_data = get_woocommerce_term_meta( $term_id, 'thumbnail_id', true );
            if(!empty($ex_data)){
                $save_data = array('thumbnail_id' => $ex_data);
                vsp_update_term_meta($term_id,'_pbf_wc_meta',$save_data);
                delete_woocommerce_term_meta($id,'thumbnail_id',$ex_data);
                return $ex_data;
            }
        }
        return isset($return['thumbnail_id']) ? $return['thumbnail_id'] : false;
    }
}

if(!function_exists('pbf_wc_get_brand_thumbnail_url')){
    
    function pbf_wc_get_brand_thumbnail_url( $brand_id, $size = 'pbf_wc_small' ) {
        if(is_object($brand_id)){
            $brand_id = isset($brand_id->term_id) ? $brand_id->term_id : false;
        }
        
        $thumbnail_id = pbf_wc_term_thumbnail_id( $brand_id );
        if ( $thumbnail_id ){
            $thumb_src = wp_get_attachment_image_src( $thumbnail_id, $size );
            if ( ! empty( $thumb_src ) ) {
                return current( $thumb_src );
            } else {
                return vsp_placeholder_img();
            }
        } else {
            $default = pbf_wc_option('default_image');
            $default = wp_get_attachment_image_src($default,$size);
            return empty($default) ? vsp_placeholder_img() : current($default);
        }
    }    
}


if(!function_exists('pbf_wc_get_brands')){
    function pbf_wc_get_brands( $post_id = 0, $sep = ', ', $before = '', $after = '' ) {
        global $post;

        if ( ! $post_id )
            $post_id = $post->ID;

        return get_the_term_list( $post_id, 'product_brands', $before, $sep, $after );
    }
}