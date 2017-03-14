<?php

if ( ! defined( 'WPINC' ) ) { die; }
 
class Product_Brands_For_WooCommerce_Function {

	public function __construct(){
		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
		add_filter('woocommerce_get_image_size_pbf_wc_imgsize_small',array($this,'wc_get_imgsize_small'));
		add_filter('woocommerce_get_image_size_pbf_wc_imgsize_medium',array($this,'wc_get_imgsize_medium'));
		add_filter('woocommerce_get_image_size_pbf_wc_imgsize_large',array($this,'wc_get_imgsize_large'));
		add_action( 'after_setup_theme', array( $this, 'set_image_size' ) );
		add_action( 'init', array($this,'product_brands'), 0 );
	}
	
	public function styles() {
	    wp_enqueue_style( 'product-brands-styles', PBF_WC_URL.'css/style.css');
    }
	/**
	 * Returns Product Brands Small Image Size
	 */
	public function wc_get_imgsize_small(){ 
		remove_filter('woocommerce_get_image_size_pbf_wc_imgsize_small',array($this,'wc_get_imgsize_small')); 
		return $this->get_pbf_imgsize('small'); 		
	}
	
	/**
	 * Returns Product Brands medium Image Size
	 */
	public function wc_get_imgsize_medium(){
		remove_filter('woocommerce_get_image_size_pbf_wc_imgsize_medium',array($this,'wc_get_imgsize_medium')); 
		return $this->get_pbf_imgsize('medium'); 		
	}
	
	/**
	 * Returns Product Brands Large Image Size
	 */
	public function wc_get_imgsize_large(){ 
		remove_filter('woocommerce_get_image_size_pbf_wc_imgsize_large',array($this,'wc_get_imgsize_large'));
		return $this->get_pbf_imgsize('large'); 		
	}
	
	
	/**
	 * Gets Product Brands Image Size From DB
	 * @param  [[Type]] $size [[Description]]
	 * @return [[Type]] [[Description]]
	 */
	public function get_pbf_imgsize($size){
		$size = get_option(PBF_WC_DB.'imgsize_'.$size);
		return $size;
	}
	
	
	public function set_image_size(){
		$img_small = $this->get_pbf_imgsize('small');
		$img_medium = $this->get_pbf_imgsize('medium');
		$img_large = $this->get_pbf_imgsize('large');
		add_image_size(PBF_WC_DB.'small', $img_small['width'], $img_small['height'], $img_small['crop'] );
		add_image_size(PBF_WC_DB.'medium', $img_medium['width'], $img_medium['height'], $img_medium['crop'] );
		add_image_size(PBF_WC_DB.'large', $img_large['width'], $img_large['height'], $img_large['crop'] );
	}
	
	
	public function product_brands() {

		$labels = array(
			'name'                       => _x( pbf_wc_name().'s', 'Taxonomy General Name', PBF_WC_TXT ),
			'singular_name'              => _x( pbf_wc_name().'', 'Taxonomy Singular Name', PBF_WC_TXT ),
			'menu_name'                  => __( pbf_wc_name().'s', PBF_WC_TXT ),
			'all_items'                  => __( 'All '.pbf_wc_name().'s', PBF_WC_TXT ),
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
			'capabilities' => array(
      					'manage_terms'=> 'manage_product_brands',
      					'edit_terms'=> 'manage_product_brands',
      					'delete_terms'=> 'manage_product_brands',
      					'assign_terms' => 'read'
    				),
    			'map_meta_cap' => true
		);
        
        $args = apply_filters('pbf_wc_taxonomy_args',$args);
		
		register_taxonomy( 'product_brands', array( 'product' ), $args );
        
        
        $flush = get_option(PBF_WC_DB.'flush_permalink'); 
        if($flush == 'flush_now'){ 
            flush_rewrite_rules();
            delete_option(PBF_WC_DB.'flush_permalink');
        }
	}	
	
} 
