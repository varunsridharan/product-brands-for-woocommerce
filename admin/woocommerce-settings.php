<?php
/**
 * WooCommerce General Settings
 *
 * @author      WooThemes
 * @category    Admin
 * @package     WooCommerce/Admin
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Product_Brands_For_WooCommerce_Settings' ) ) :

/**
 * WC_Admin_Settings_General
 */
class Product_Brands_For_WooCommerce_Settings extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() { 
		add_filter( 'woocommerce_get_sections_products', array( $this, 'add_section' ) );
		add_filter( 'woocommerce_get_settings_products',  array( $this , 'get_settings'), 10, 2 );  
        add_action( 'woocommerce_settings_save_products', array( $this, 'save' ) );
	}

	/**
	 * Adds Settings SUB Menu Under Products
	 */
	public function add_section($sections){ 
		$sections['pb_wc'] = __(pbf_wc_name(), PBF_WC_TXT );
		return $sections;
		
	}
	
	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings($settings = '', $current_section = '') {
		if( $current_section == 'pb_wc'){
			$settings = array( 
                
                
				array( 
					'title' => __( pbf_wc_name().' Shortcode', PBF_WC_TXT ), 
					'type' => 'title', 
					'desc' => __( 'Use this shortcode to get product brand image any where <code>[pbf_wc]</code> <br/>
						<strong> Shortcode Vars : </strong> <br/>
						<strong> Product ID : </strong> use <code>[pbf_wc]</code> to get automaticly product id or use  <code>[pbf_wc id=\'23\']</code>
						<strong> Image Size : </strong> small, medium, large <code> [pbf_wc size=\'small\']</code> <br/>
						<strong> Custom Style :  </strong> To Disable Custom Style Use  <code> [pbf_wc style=\'false\']</code> <br/>
					
					', PBF_WC_TXT ), 
					'id' => 'product_bands_shortcode_start' 
				),
				array( 
					'type' => 'sectionend', 
					'id' => 'product_bands_shortcode_end'
				),
				array( 
					'title' => __( pbf_wc_name().' Settings', PBF_WC_TXT ), 
					'type' => 'title', 
					'desc' => '', 
					'id' => 'product_bands_settings_start' 
				),
                
                array(
					'title'    => __( 'Singular Custom Name', PBF_WC_TXT ),
					'desc'     => __( 'Enter a Rename Product Brands into your own requirement ', PBF_WC_TXT ),
					'id'       => PBF_WC_DB.'custom_brands_name', 
					'default'  => 'Product Brand',
					'type'     => 'text',  
					'autoload' => true
				), 
                
                array(
					'title'    => __( 'Plural Custom Name', PBF_WC_TXT ),
					'desc'     => __( 'Enter a Rename Product Brands into your own requirement ', PBF_WC_TXT ),
					'id'       => PBF_WC_DB.'custom_brands_name_plural', 
					'default'  => 'Product Brands',
					'type'     => 'text',  
					'autoload' => true
				), 
                
                array(
					'title'    => __( 'Custom URL Slug', PBF_WC_TXT ),
					'desc'     => __( 'Your custom url slug to replace product_brands from url', PBF_WC_TXT ),
					'id'       => PBF_WC_DB.'custom_url_slug', 
					'default'  => 'product_brands',
					'type'     => 'text',  
					'autoload' => true
				), 
                
                
				array(
					'title'    => __( 'Where To Show', PBF_WC_TXT ),
					'desc'     => __( 'If you select manual then you can use shortcode', PBF_WC_TXT ),
					'id'       => PBF_WC_DB.'whereto_show', 
					'default'  => 'all',
					'type'     => 'select',
					'class'    => 'wc-enhanced-select',
					'options' => array(
						'single_title' => __('Product Title',PBF_WC_TXT), 
						'single_price' => __("Product Price",PBF_WC_TXT),
						'single_excerpt' => __("Product excerpt",PBF_WC_TXT),
						'single_addtocart' => __("Product Add To Cart",PBF_WC_TXT),
						'custom' => __('Manual',PBF_WC_TXT),
					), 
					'autoload' => false
				), 
				
				array(
					'title'    => __( 'Position', PBF_WC_TXT ),
					'desc'     =>  __( 'Position Dose Not Apply For Manual', PBF_WC_TXT ),
					'id'       => PBF_WC_DB.'img_position', 
					'default'  => 'all',
					'type'     => 'select',
					'class'    => 'wc-enhanced-select',
					'options' => array(
						'before' => __('Before',PBF_WC_TXT),
						'after' => __('After',PBF_WC_TXT)
					), 
					'autoload' => false
				), 
				
				 
				
				array(
					'title'    => __( 'Image Size', PBF_WC_TXT ),
					'desc'     =>  __( '', PBF_WC_TXT ),
					'id'       => PBF_WC_DB.'img_size', 
					'default'  => 'all',
					'type'     => 'select',
					'class'    => 'wc-enhanced-select',
					'options' => array(
						PBF_WC_DB.'small' => __('Small',PBF_WC_TXT),
						PBF_WC_DB.'medium' => __('Medium',PBF_WC_TXT),
						PBF_WC_DB.'large' => __('Large',PBF_WC_TXT)
					), 
					'autoload' => false
				), 
				
				array(
					'title'    => __( 'HTML Template', PBF_WC_TXT ),
					'desc'     =>  __( 'HTML Template For Brand Image . <br/> Use <code> {brand_images} </code> to get image tag.. <br/> do not add img html tag.. which will be auto generated.', PBF_WC_TXT ),
					'id'       => PBF_WC_DB.'html_template', 
					'default'  => '<div class="{default_class} " >{brand_images}</div> ',
					'css' => 'width:75%;',
					'type'     => 'textarea',  
					'autoload' => false,
					'desc_tip' =>  false,
				), 
				
				array(
					'title'    => __( 'Custom Style', PBF_WC_TXT ),
					'desc'     =>  __( 'Custom Styling For Product Brand Images <br/> please do not add <code>style</code> opening / closing tags. which will be auto generated', PBF_WC_TXT ),
					'id'       => PBF_WC_DB.'custom_style', 
					'default'  => '',
					'css' => 'width:75%;',
					'type'     => 'textarea',  
					'autoload' => false,
					'desc_tip' =>  false,
				), 
				
				array( 
					'type' => 'sectionend', 
					'id' => 'product_bands_settings_end'
				),
				array(
					'title' => __( pbf_wc_name().' Images', PBF_WC_TXT ),
					
					'desc' 	=> sprintf( __( 'These settings affect the display and dimensions of images in your catalog - the display on the front-end will still be affected by CSS styles. After changing these settings you may need to <a href="%s">regenerate your thumbnails</a>.', PBF_WC_TXT ), 'http://wordpress.org/extend/plugins/regenerate-thumbnails/' ),
					'type' 	=> 'title',
					'id' 	=> 'product_bands_image_settings_start'
				),

				array(
					'title'    => __( 'Small Size', PBF_WC_TXT ), 
					'id'       => PBF_WC_DB.'imgsize_small',
					'css'      => '',
					'type'     => 'image_width',
					'default'  => array(
						'width'  => '100',
						'height' => '100',
						'crop'   => 1
					),
					'desc_tip' =>  true,
				),

				array(
					'title'    => __( 'Medium Size', PBF_WC_TXT ), 
					'id'       => PBF_WC_DB.'imgsize_medium',
					'css'      => '',
					'type'     => 'image_width',
					'default'  => array(
						'width'  => '200',
						'height' => '200',
						'crop'   => 1
					),
					'desc_tip' =>  true,
				),

				array(
					'title'    => __( 'Large Size', PBF_WC_TXT ), 
					'id'       => PBF_WC_DB.'imgsize_large',
					'css'      => '',
					'type'     => 'image_width',
					'default'  => array(
						'width'  => '350',
						'height' => '350',
						'crop'   => 1
					),
					'desc_tip' =>  true,
				), 

				array(
					'type' 	=> 'sectionend',
					'id' 	=> 'product_bands_image_settings_end'
				)



			);
		}
		return $settings;
	}
 

	/**
	 * Save settings
	 */
	public function save() {
        add_option(PBF_WC_DB.'flush_permalink','flush_now');
		$settings = $this->get_settings();
		WC_Admin_Settings::save_fields( $settings );
        
	}

}

endif;

return new Product_Brands_For_WooCommerce_Settings();