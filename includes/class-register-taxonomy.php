<?php
if(!defined("ABSPATH")){exit;}

class Product_Brands_For_WooCommerce_Register_Taxonomy{
    
    public function __construct(){
		add_action( 'init', array($this,'product_brands'), 0 );
	}
    
    public function product_brands() {

		$labels = array(
			'name'                       => _x( pbf_wc_name(true), 'Taxonomy General Name', PBF_WC_TXT ),
			'singular_name'              => _x( pbf_wc_name().'', 'Taxonomy Singular Name', PBF_WC_TXT ),
			'menu_name'                  => __( pbf_wc_name(true), PBF_WC_TXT ),
			'all_items'                  => __( 'All '.pbf_wc_name(true), PBF_WC_TXT ),
			'parent_item'                => __( 'Parent '.pbf_wc_name(), PBF_WC_TXT ),
			'parent_item_colon'          => __( 'Parent '.pbf_wc_name().' :', PBF_WC_TXT ),
			'new_item_name'              => __( 'New '.pbf_wc_name().' Name', PBF_WC_TXT ),
			'add_new_item'               => __( 'Add New '.pbf_wc_name(), PBF_WC_TXT ),
			'edit_item'                  => __( 'Edit '.pbf_wc_name(), PBF_WC_TXT ),
			'update_item'                => __( 'Update '.pbf_wc_name(), PBF_WC_TXT ),
			'view_item'                  => __( 'View '.pbf_wc_name(), PBF_WC_TXT ),
			'separate_items_with_commas' => __( 'Separate '.pbf_wc_name().' With Commas', PBF_WC_TXT ),
			'add_or_remove_items'        => __( 'Add or remove '.pbf_wc_name(), PBF_WC_TXT ),
			'choose_from_most_used'      => __( 'Choose from the most used', PBF_WC_TXT ),
			'popular_items'              => __( 'Popular '.pbf_wc_name(), PBF_WC_TXT ),
			'search_items'               => __( 'Search '.pbf_wc_name(), PBF_WC_TXT ),
			'not_found'                  => __( 'Not Found', PBF_WC_TXT ),
		);
		
		$labels = apply_filters('pbf_wc_tax_labels',$labels);
		
        $rewrite = array(
            'slug'                       => pbf_wc_url_slug(),
            'with_front'                 => true,
            'hierarchical'               => true,
        );

		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
            'rewrite'                    => $rewrite,
		);
        
        $args = apply_filters('pbf_wc_taxonomy_args',$args);
		
		register_taxonomy( 'product_brands', array( 'product' ), $args );
        
        
        $flush = get_option('pbf_wc_flush_permalink');
        if($flush == 'flush_now'){ 
            flush_rewrite_rules();
            delete_option('pbf_wc_flush_permalink');
        }
	}
}

return new Product_Brands_For_WooCommerce_Register_Taxonomy;