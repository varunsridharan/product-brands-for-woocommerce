<?php
if(!defined("ABSPATH")){
    exit;
}

class Product_Brands_For_WooCommerce_Admin_Metaboxes {

    public function __construct(){
        add_filter('manage_edit-product_brands_columns', array( $this, 'product_cat_columns' ) );
		add_filter('manage_product_brands_custom_column', array( $this, 'product_cat_column' ), 10, 3 );
        add_filter('vsp_taxonomy_fields', array($this,'enable_fields'));
        add_filter('vsp_shortcode_fields',array($this,'enable_shortcodes'));
    }
    
    public function enable_fields($options){
        $options[] = array(
            'id' => '_pbf_wc_meta',
            'taxonomy' => 'product_brands',
            'fields' => array(
                array(
                    'id' => 'thumbnail_id',
                    'type' => 'image',
                    'title' => __("Thumbnail",PBF_WC_TXT),
                    'add_title' => __("Select Brand Logo"),
                )
            ),
        );
        return $options;
    }
    
	public function product_cat_columns( $columns ) {
        return (empty($columns)) ? $columns : array_merge(array('cb' => $columns['cb'],'pbfwc_thumbnail' => __("Image",PBF_WC_TXT)),$columns);
	}
    
	public function product_cat_column( $columns, $column, $id ) {
		if ( 'pbfwc_thumbnail' == $column ) {
            $thumbnail_id = pbf_wc_term_thumbnail_id( $id );
            $image = ($thumbnail_id) ? wp_get_attachment_thumb_url($thumbnail_id) : vsp_placeholder_img();
			$image = str_replace( ' ', '%20', $image );
			$columns .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr__( 'Thumbnail', PBF_WC_TXT ) . '" class="wp-post-image" height="48" width="48" />';
		}

		return $columns;
	}
    
    private function get_pbf_wc(){
        return array(
            array(
                'id' => 'id',
                'title' => __("Product"),
                'type' => 'select',
                'options' => 'posts',
                'class' => 'chosen',
                'query_args'   => array(
                    'post_type'    => 'product',
                    'orderby'      => 'post_date',
                    'order'        => 'DESC',
                    'numberposts' => -1,
                ),
            ),

            array(
                'id' => 'size',
                'title' => __("Image Size"),
                'type' => 'select',
                'class' => 'chosen',
                'options' => array(
                    '' => __("Choose Image Size",PBF_WC_TXT),
                    'small' => __("Small",PBF_WC_TXT),
                    'medium' => __("Medium",PBF_WC_TXT),
                    'large' => __("Large",PBF_WC_TXT),
                    'custom' => __("Custom",PBF_WC_TXT),
                ),
            ),

            array(
                'id' => 'width',
                'title' => __("Image Width"),
                'type' => 'text',
                'dependency'   => array( 'size', '==', 'custom' ),
            ),

            array(
                'id' => 'height',
                'title' => __("Image Height"),
                'type' => 'text',
                'dependency'   => array( 'size', '==', 'custom' ),
            ),

            array(
                'id' => 'class',
                'title' => __("Image Class"),
                'type' => 'text',
            ),
        );
    }
    
    private function get_pbf_wc_thumbnails(){
        return array(
            array(
                'id' => 'show_empty',
                'type' => 'switcher',
                'title' => __("Show Empty ?"),
            ),
            array(
                'id' => 'columns',
                'type' => 'number',
                'title' => __("No of Columns"),
            ),
            array(
                'id' => 'orderby',
                'class' => 'chosen',
                'type' => 'select',
                'title' => __("Order By"),
                'options' => array(
                    'id' => __("ID"),
                    'count' => __("Count"),
                    'name' => __("Name"),
                    'slug' => __("Slug"),
                )   
            ),
            
            array(
                'id' => 'exclude',
                'type' => 'select',
                'options' =>'tags',
                'class' => 'chosen',
                'title' => __("Exclude Brand "),
                'default_option' => __("Select a Brand To Exclude"),
                'query_args' => array(
                    'taxonomies' => 'product_brands',
                ),
            ),
            
            array(
                'id' => 'number',
                'type' => 'number',
                'title' => __("Max Number Item to return"),
                'desc' => __("The maximum number of terms to return. By default all of them are returned."),
            ),
            array(
                'id' => 'fluid_columns',
                'type' => 'switcher',
                'title' => __("Fluid Columns ?"),
            ),
            
        );
    }
    
    private function get_pbf_wc_thumbnails_description(){
        return array(
            array(
                'id' => 'show_empty',
                'type' => 'switcher',
                'title' => __("Show Empty ?"),
            ),
            array(
                'id' => 'columns',
                'type' => 'number',
                'title' => __("No of Columns"),
            ),
            array(
                'id' => 'orderby',
                'class' => 'chosen',
                'type' => 'select',
                'title' => __("Order By"),
                'options' => array(
                    'id' => __("ID"),
                    'count' => __("Count"),
                    'name' => __("Name"),
                    'slug' => __("Slug"),
                )   
            ),
            
            array(
                'id' => 'exclude',
                'type' => 'select',
                'options' =>'tags',
                'class' => 'chosen',
                'title' => __("Exclude Brand "),
                'default_option' => __("Select a Brand To Exclude"),
                'query_args' => array(
                    'taxonomies' => 'product_brands',
                ),
            ),
            
            array(
                'id' => 'number',
                'type' => 'number',
                'title' => __("Max Number Item to return"),
                'desc' => __("The maximum number of terms to return. By default all of them are returned."),
            ),
            
        );
    }
    
    private function get_pbf_wc_brand_list(){
        return array(
            array(
                'id' => 'show_top_links',
                'title' => __("Show Top Links",PBF_WC_TXT),
                'type' => 'switcher',
            ),
            
            array(
                'id' => 'show_empty',
                'title' => __("Show Empty Links",PBF_WC_TXT),
                'type' => 'switcher',
            ),
            
            array(
                'id' => 'show_empty_brands',
                'title' => __("Show Empty Brands",PBF_WC_TXT),
                'type' => 'switcher',
            ),
        );
    }
    
    private function get_pbf_wc_products(){
        return array(
            array(
                'id' => 'per_page',
                'type' => 'number',
                'title' => __("Products Per Page",PBF_WC_TXT),
            ),
            array(
                'id' => 'columns',
                'type' => 'number',
                'title' => __("No of Columns",PBF_WC_TXT),
            ),
            array(
                'id' => 'orderby',
                'title' => __("Order By"),
                'desc' => sprintf(__("More Options Avaiable @ %s",PBF_WC_TXT),'<a href="https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">'.__("OrderBy").'</a>'),
                'type' => 'text',
                'default' => 'title',
            ),
            array(
                'id' => 'order',
                'title' => __("Order"),
                'class' => 'chosen',
                'type' => 'select',
                'options' => array(
                    'ASC' => __("Ascending",PBF_WC_TXT),
                    'DESC' => __('Descending',PBF_WC_TXT),
                ),
            ),
            
            array(
                'id' => 'brand',
                'type' => 'select',
                'options' =>'tags',
                'class' => 'chosen',
                'title' => __("Brands To Show"),
                'desc' => __("From Which Brands Product should display ?"),
                'attributes' => array(
                    'multiple' => 'only-key',
                    'style'    => 'width: 150px; height: 125px;'
                ),
                'query_args' => array(
                    'taxonomies' => 'product_brands',
                    'option_key' => 'slug',
                ),
            ),
            
        );
    }
    
    public function enable_shortcodes($options){
        $options[] = array(
            'title' => __("Product Brands For WooCommerce",PBF_WC_TXT),
            'shortcodes' => array(
                array(
                    'name' => 'pbf_wc',
                    'title' => __("Single",PBF_WC_TXT).' '.pbf_wc_name(),
                    'fields' => $this->get_pbf_wc(),
                ),
                
                array(
                    'name' => 'pbf_wc_thumbnails',
                    'title' => pbf_wc_name(true).' '.__("Thumbnails",PBF_WC_TXT),
                    'fields' => $this->get_pbf_wc_thumbnails(),
                ),
                
                array(
                    'name' => 'pbf_wc_thumbnails_description',
                    'title' => pbf_wc_name(true).' '.__("Thumbnail Description",PBF_WC_TXT),
                    'fields' => $this->get_pbf_wc_thumbnails_description(),
                ),
                array(
                    'name' => 'pbf_wc_brand_list',
                    'title' => pbf_wc_name(true).' '.__("List",PBF_WC_TXT),
                    'fields' => $this->get_pbf_wc_brand_list(),
                ),
                array(
                    'name' => 'pbf_wc_products',
                    'title' => pbf_wc_name(true) .' '.__("Products",PBF_WC_TXT),
                    'fields' => $this->get_pbf_wc_products(),
                )
            ),
        );
        
        return $options;
    }
}

return new Product_Brands_For_WooCommerce_Admin_Metaboxes;