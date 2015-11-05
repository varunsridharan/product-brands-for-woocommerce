<?php

if ( ! defined( 'WPINC' ) ) { die; }
 
class Product_Brands_For_WooCommerce_FrontEnd {
	public function __construct(){
		add_shortcode('pbf_wc' , array($this,'manage_shortcode'));
		$this->wheretoshow = get_option(PBF_WC_DB.'whereto_show',true);
		$this->position = get_option(PBF_WC_DB.'img_position',true);
		$this->set_area();
	}
 
	
	/**
	 * Adds Filters Based on Settings
	 */
	public function set_area(){
		if($this->wheretoshow == 'custom'){return true;}
		$positon = $this->get_position();
		
		if($this->wheretoshow == 'single_title'){
			add_action( 'woocommerce_single_product_summary', array($this,'add_brand_image_filter'), $positon ); 			
		}
		
	}
	
	/**
	 * Adds Filter Position Based On Settings 
	 */
	public function get_position(){
		$return = 0;
		
		if($this->wheretoshow == 'custom'){return true;}
		
		if($this->wheretoshow == 'single_title'){
			$return = 4;
			if($this->position == 'after'){ $return = 6; }
		}
		return $return;
	}
	
	/**
	 * Adds Product Brand Image Via Filters
	 */
	public function add_brand_image_filter(){
		global $product,$post;
		$post_id = $post->ID;
		echo $this->get_product_brand_image($post_id); 
	}
		
		
	
	public function get_product_brand_image($productId,$image_size = '',$add_style = true,$custom_class = ''){
		$terms = wp_get_post_terms( $productId, 'product_brands' );
		$html_template = get_option(PBF_WC_DB.'html_template',true);
		$custom_style = '';
		if(empty($image_size)){$image_size = get_option(PBF_WC_DB.'img_size',true);}

		if($add_style){
			$custom_style = get_option(PBF_WC_DB.'custom_style',true);
			$custom_style = '<style>'.$custom_style.'</style>';
		}		
		
		$default_class = 'pbf_container pbf_container_'.$productId.' '.$custom_class;
		$image_urls = '';
		$term_id = array();
		foreach($terms as $term){
			$term_id[] = $term->term_id;
		}
		
		foreach($term_id as $termid){
			$image = get_woocommerce_term_meta( $termid, 'thumbnail_id', true );
			if(!empty($image)){
				$image_url = wp_get_attachment_image_src($image,$image_size); 
				
				$image_urls .= '<img src="'.$image_url[0].'" />';
			}
			
		} 
		$html_search = array('{default_class}','{brand_img_url}');
		$html_replace = array($default_class,$image_urls);
		$html_output = str_replace($html_search,$html_replace,$html_template);
		$html_output .= $custom_style;
		return $html_output;
	}
	
					  
					  
	function manage_shortcode( $atts ) {		
		$atts = shortcode_atts( array('id' => null,'size' => 'small','style' => true,), $atts, 'pbf_wc' ); 
		if($atts['id'] == null){
			global $product,$post;
			$atts['id'] = $post->ID;
		}
		
		if($atts['size'] != null){$atts['size'] = PBF_WC_DB.$atts['size'];}
		if($atts['style'] == 'false'){$atts['style'] = false;}
		return $this->get_product_brand_image($atts['id'],$atts['size'],$atts['style'],' pbf_container_shorcode');
	}
}
?>