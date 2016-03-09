<?php

if ( ! defined( 'WPINC' ) ) { die; }
 
class Product_Brands_For_WooCommerce_FrontEnd {
	public function __construct(){
		add_shortcode('pbf_wc' , array($this,'manage_shortcode'));
		add_shortcode('pbf_wc_list' , array($this,'manage_list_shortcode'));
		add_shortcode('pbf_wc_grid' , array($this,'manage_grid_shortcode'));
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
		
		$single_product_summary = array('single_title','single_price','single_excerpt','single_addtocart',);
		
		if(in_array($this->wheretoshow,$single_product_summary)){
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
		
		if($this->wheretoshow == 'single_price'){
			$return = 9;
			if($this->position == 'after'){ $return = 11; }
		}
		
		if($this->wheretoshow == 'single_excerpt'){
			$return = 19;
			if($this->position == 'after'){ $return = 21; }
		}
		
		if($this->wheretoshow == 'single_addtocart'){
			$return = 29;
			if($this->position == 'after'){ $return = 31; }
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
		foreach($terms as $term){ $term_id[] = $term->term_id; }
		foreach($term_id as $termid){
			$image = get_woocommerce_term_meta( $termid, 'thumbnail_id', true );
			if(!empty($image)){
				$image_url = wp_get_attachment_image($image,$image_size); 
				$image_urls .= $image_url;
			}
		} 
		$html_search = array('{default_class}','{brand_images}');
		$html_replace = array($default_class,$image_urls);
		$html_output = str_replace($html_search,$html_replace,$html_template);
		$html_output .= $custom_style;
		return $html_output;
	}
					  
	public function manage_shortcode( $atts ) {		
		$atts = shortcode_atts( array('id' => null,'size' => 'small','style' => true,), $atts, 'pbf_wc' ); 
		if($atts['id'] == null){
			global $product,$post;
			$atts['id'] = $post->ID;
		}
		
		if($atts['size'] != null){$atts['size'] = PBF_WC_DB.$atts['size'];}
		if($atts['style'] == 'false'){$atts['style'] = false;}
		return $this->get_product_brand_image($atts['id'],$atts['size'],$atts['style'],' pbf_container_shorcode');
	}

	public function manage_list_shortcode($atts){
		extract( shortcode_atts( array(
		      'show_empty' 		=> true,
		      'hide_empty'		=> 0,
		      'orderby'			=> 'name',
		      'exclude'			=> '',
		      'number'			=> '',
	     ), $atts ) );
		
	    $exclude = array_map( 'intval', explode(',', $exclude) );
	    $order = $orderby == 'name' ? 'asc' : 'desc';
		
		$brands = get_terms('product_brands', array( 'hide_empty' => $hide_empty, 'orderby' => $orderby, 'exclude' => $exclude, 'number' => $number, 'order' => $order ));
		$product_brands = array();
		foreach ( $brands as $term ) {

			$term_letter = substr( $term->slug, 0, 1 );

			if ( ctype_alpha( $term_letter ) ) {

				foreach ( range( 'a', 'z' ) as $i )
					if ( $i == $term_letter ) {
						$product_brands[ $i ][] = $term;
						break;
					}

			} else {
				$product_brands[ '0-9' ][] = $term;
			}

		}

		ob_start();

		wc_get_template( 'brands-a-z.php', array(
			'terms'				=> $brands,
			'index'				=> array_merge( range( 'a', 'z' ), array( '0-9' ) ),
			'product_brands'	=> $product_brands,
			'show_empty'		=> $show_empty,
			'show_top_links'	=> true
		), 'woocommerce/product_brands', untrailingslashit( plugin_dir_path( dirname( __FILE__ ) ) ) . '/templates/' );

		return ob_get_clean();		
	}
	
	public function manage_grid_shortcode( $atts ) {

		extract( shortcode_atts( array(
		      'show_empty' 		=> true,
		      'columns'			=> 4,
		      'hide_empty'		=> 0,
		      'orderby'			=> 'name',
		      'exclude'			=> '',
		      'number'			=> '',
			  'image_size' => get_option(PBF_WC_DB.'img_size',true),
	     ), $atts ) );

	    $exclude = array_map( 'intval', explode(',', $exclude) );
	    $order = $orderby == 'name' ? 'asc' : 'desc';

		$brands = get_terms( 'product_brands', array( 'hide_empty' => $hide_empty, 'orderby' => $orderby, 'exclude' => $exclude, 'number' => $number, 'order' => $order ) );

		
		if ( ! $brands )
			return;

		ob_start();

		wc_get_template( 'brand-thumbnails.php', array(
			'brands'	=> $brands,
			'columns'	=> $columns,
			'image_size' => $image_size,
		),'woocommerce/product_brands', untrailingslashit( plugin_dir_path( dirname( __FILE__ ) ) ) . '/templates/' );

		return ob_get_clean();
	}
}
?>