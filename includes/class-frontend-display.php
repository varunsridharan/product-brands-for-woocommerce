<?php

if(!defined("ABSPATH")){exit;}

class Product_Brands_For_WooCommerce_Frontend_Display {
    
    public function __construct(){
        add_action( 'after_setup_theme', array( $this, 'set_image_size' ) );
        add_action( 'wp', array( $this, 'body_class' ) );
        add_action( 'widgets_init', array( $this, 'init_widgets' ) );
        add_filter( 'template_include', array( $this, 'template_loader' ) );
        add_filter( 'loop_shop_post_in', array( $this, 'woocommerce_brands_layered_nav_init' ) );
        
        add_shortcode( 'pbf_wc', array( $this, 'output_product_brand' ) );
		add_shortcode( 'pbf_wc_thumbnails', array( $this, 'output_product_brand_thumbnails' ) );
		add_shortcode( 'pbf_wc_thumbnails_description', array( $this, 'output_product_brand_thumbnails_description' ) );
		add_shortcode( 'pbf_wc_brand_list', array( $this, 'output_product_brand_list' ) );
		add_shortcode( 'pbf_wc_products', array( $this, 'output_brand_products' ) );
        
        if(pbf_wc_option("brand_showmeta")){
            add_action( 'woocommerce_product_meta_end', array( $this, 'show_brand' ) );    
        }

        $this->setup_autoshow();
    }
    
    public function woocommerce_brands_layered_nav_init( $filtered_posts ) {

		if ( is_active_widget( false, false, 'woocommerce_brand_nav', true ) && ! is_admin() ) {

			if ( ! empty( $_GET[ 'filter_product_brands' ] ) ) {

				$terms 	= array_map( 'intval', explode( ',', $_GET[ 'filter_product_brands' ] ) );

				if ( sizeof( $terms ) > 0 ) {
					$matched_products = get_posts(
						array(
							'post_type'     => 'product',
							'numberposts'   => -1,
							'post_status'   => 'publish',
							'fields'        => 'ids',
							'no_found_rows' => true,
							'tax_query'     => array(

								'relation' => 'AND',
								array(
									'taxonomy' => 'product_brands',
									'terms'    => $terms,
									'field'    => 'id'
								)
							)
						)
					);

					$filtered_posts = array_merge( $filtered_posts, $matched_products );

					if ( sizeof( $filtered_posts ) == 0 ) {
						$filtered_posts = $matched_products;
					} else {
						$filtered_posts = array_intersect( $filtered_posts, $matched_products );
					}

				}

			}

		}

		return (array) $filtered_posts;
	}
    
    public function setup_autoshow(){
        $whereToShow = pbf_wc_option('brand_place');
        if($whereToShow === 'custom') {return;}
        $posd = $this->position_data();
        $position = pbf_wc_option("brand_position");
        $pos = isset($posd[$whereToShow][$position]) ? $posd[$whereToShow][$position] : 0;
        if(isset($posd[$whereToShow])){
            add_action('woocommerce_single_product_summary',array(&$this,'render_brand_data'),$pos);
        }
    }
    
    public function position_data(){
        return array(
            'single_title' => array(
                'before' => 4,
                'after' => 6
            ),
            'single_price' => array(
                'before' => 9,
                'after' => 11,
            ),
            'single_excerpt' => array(
                'before' => 19,
                'after' => 21,
            ),
            'single_addtocart' => array(
                'before' => 29,
                'after' => 31,
            )
        );
    }
    
	public function get_pbf_imgsize($size){
		$size = pbf_wc_option($size."_imagesize");
        if(!is_array($size)){
            return array('width' => '','height' => '','crop' => '');
        }
		return $size;
	}
    
	public function set_image_size(){
		$ims = $this->get_pbf_imgsize('small');
		$imm = $this->get_pbf_imgsize('medium');
		$iml = $this->get_pbf_imgsize('large');
        
        add_image_size('pbf_wc_small', $ims['width'], $ims['height'], $ims['crop'] );
		add_image_size('pbf_wc_medium', $imm['width'], $imm['height'], $imm['crop'] );
		add_image_size('pbf_wc_large', $iml['width'], $iml['height'], $iml['crop'] );
	}
    
    public function render_brand_data(){
        global $post;
        echo $this->render_product_brand_image($post->ID);
    }
    
    public function render_product_brand_image($post_id,$image_size = '',$style = true,$class = ''){
        $terms = wp_get_post_terms($post_id,'product_brands');
        $image_size = (empty($image_size)) ? pbf_wc_option("brand_imagesize") : $image_size;
        $default_classes = 'pbf_container pbf_container_'.$post_id.' '.$class;
        $image_urls = array();
        $html_output = '';
        $default_image = pbf_wc_option('default_image');
        
        $enable_link = pbf_wc_option('brand_pagelink');
        if(!is_wp_error($terms)){
            foreach($terms as $tid){
                $data = vsp_get_term_meta($tid->term_id,'_pbf_wc_meta',true);
                if(is_array($data) && isset($data['thumbnail_id'])){
                    $id = $data['thumbnail_id'];
                    $before = $after = '';
                    if($enable_link === true){
                        $link = get_term_link($tid->term_id,$tid->taxonomy);
                        $before = sprintf('<a href="%s" title="%s" >',$link,$tid->name);
                        $after  = '</a>';
                    }
                    
                    $image = wp_get_attachment_image($id,'pbf_wc_'.$image_size);
                    if(empty($image)){
                        $image = wp_get_attachment_image($default_image,'pbf_wc_'.$image_size);
                    }
                    
                    $image_urls[] = $before.$image.$after;
                }
            }
        }
        
        $image_urls = array_filter($image_urls);
        if(!empty($image_urls)){
            $html_output = implode(' ',$image_urls);
        }
        
        return $html_output;
    }
    
    public function body_class(){
        if(is_tax('product_brands')){
            add_filter('body_class',array(&$this,'add_body_class'));
        }
    }
    
    public function add_body_class($class){
        $class[] = 'woocommerce';
        $class[] = 'woocommerce-page';
        return $class;
    }
    
    public function show_brand() {
		global $post;

		if ( is_singular( 'product' ) ) {
			$brand_count = sizeof( get_the_terms( $post->ID, 'product_brands' ) );

			$taxonomy = get_taxonomy( 'product_brands' );
			$labels   = $taxonomy->labels;
            
			echo pbf_wc_get_brands( $post->ID, ', ', ' <span class="posted_in">' . sprintf( _n( '%1$s: ', '%2$s: ', $brand_count ), $labels->singular_name, $labels->name ), '</span>' );
		}
	}
    
    public function init_widgets() {
		require_once( PBF_WC_INC.'widgets/class-wc-widget-brand-description.php' );
		require_once( PBF_WC_INC.'widgets/class-wc-widget-brand-nav.php' );
		require_once( PBF_WC_INC.'widgets/class-wc-widget-brand-thumbnails.php' );

        register_widget( 'WC_Widget_Brand_Description' );
		register_widget( 'WC_Widget_Brand_Nav' );
		register_widget( 'WC_Widget_Brand_Thumbnails' );
	}
    
    /**
	 * template_loader
	 *
	 * Handles template usage so that we can use our own templates instead of the themes.
	 *
	 * Templates are in the 'templates' folder. woocommerce looks for theme
	 * overides in /theme/woocommerce/ by default
	 *
	 * For beginners, it also looks for a woocommerce.php template first. If the user adds
	 * this to the theme (containing a woocommerce() inside) this will be used for all
	 * woocommerce templates.
	 */
	public function template_loader( $template ) {
		$find = array( 'woocommerce.php' );
		$file = '';
		if ( is_tax( 'product_brands' ) ) {
			$term = get_queried_object();
			$file   = 'taxonomy-' . $term->taxonomy . '.php';
			$find[] = 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
			$find[] = 'woocommerce/'.'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
			$find[] = $file;
			$find[] = 'woocommerce/'.$file;
		}

		if ( $file ) {
			$template = locate_template( $find );
			if ( ! $template ) $template = PBF_WC_PATH . '/templates/' . $file;
		}
		return $template;
	}
    
    /**
	 * Loop over found products.
	 *
	 * @access public
	 * @param  array $query_args
	 * @param  array $atts
	 * @param  string $loop_name
	 * @return string
	 */
	public function product_loop( $query_args, $atts, $loop_name ) {
		global $woocommerce_loop;
		$products                    = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $query_args, $atts ) );
		$columns                     = absint( $atts['columns'] );
		$woocommerce_loop['columns'] = $columns;
        
		ob_start();
		if ( $products->have_posts() ) : ?>
			<?php do_action( "woocommerce_shortcode_before_{$loop_name}_loop" ); ?>
			<?php woocommerce_product_loop_start(); ?>
				<?php while ( $products->have_posts() ) : $products->the_post(); ?>
					<?php wc_get_template_part( 'content', 'product' ); ?>
				<?php endwhile; // end of the loop. ?>
			<?php woocommerce_product_loop_end(); ?>
			<?php do_action( "woocommerce_shortcode_after_{$loop_name}_loop" ); ?>
		<?php endif;
		woocommerce_reset_loop();
		wp_reset_postdata();
		return '<div class="woocommerce columns-' . $columns . '">' . ob_get_clean() . '</div>';
	}

	/**
	 * output_product_brand function.
	 *
	 * @access public
	 */
	public function output_product_brand( $atts ) {
		global $post;

		extract( shortcode_atts( array(
			'width'   => '',
			'height'  => '',
            'size'    => 'small',
			'class'   => 'aligncenter',
			'id' => ''
		), $atts ) );

        $size = ($size === 'custom')  ? '' : $size;
		if ( ! $id && ! $post )
			return;

		if ( ! $id )
			$id = $post->ID;

        $size = ltrim($size,'pbf_wc_');
        $size = 'pbf_wc_'.$size;
		$brands = wp_get_post_terms( $id, 'product_brands', array( "fields" => "ids" ) );
		$output = null;

		if ( count( $brands ) > 0 ) {
			ob_start();
			foreach( $brands as $brand ) {
				$thumbnail = pbf_wc_get_brand_thumbnail_url( $brand ,$size);

				if ( $thumbnail ) {
					$term = get_term_by( 'id', $brand, 'product_brands' );

					if ( $width || $height ) {
						$width = $width ? $width : 'auto';
						$height = $height ? $height : 'auto';
					}

					wc_get_template( 'shortcodes/single-brand.php', array(
						'term'      => $term,
						'width'     => $width,
						'height'    => $height,
						'thumbnail' => $thumbnail,
						'class'     => $class
					), 'woocommerce-brands', PBF_WC_PATH. '/templates/' );
				}
			}
			$output = ob_get_clean();
		}

		return $output;
	}

	/**
	 * output_product_brand_list function.
	 *
	 * @access public
	 * @return void
	 */
	public function output_product_brand_list( $atts ) {

		extract( shortcode_atts( array(
			'show_top_links'    => true,
			'show_empty'        => true,
			'show_empty_brands' => false
		), $atts ) );

		if ( $show_top_links === "false" )
			$show_top_links = false;

		if ( $show_empty === "false" )
			$show_empty = false;

		if ( $show_empty_brands === "false" )
			$show_empty_brands = false;

		$product_brands = array();
		$terms          = get_terms( 'product_brands', array( 'hide_empty' => ( $show_empty_brands ? false : true ) ) );

		foreach ( $terms as $term ) {

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

		wc_get_template( 'shortcodes/brands-a-z.php', array(
			'terms'          => $terms,
			'index'          => array_merge( range( 'a', 'z' ), array( '0-9' ) ),
			'product_brands' => $product_brands,
			'show_empty'     => $show_empty,
			'show_top_links' => $show_top_links
		), 'woocommerce-brands', untrailingslashit( plugin_dir_path( dirname( __FILE__ ) ) ) . '/templates/' );

		return ob_get_clean();
	}

	/**
	 * output_product_brand_thumbnails function.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return void
	 */
	public function output_product_brand_thumbnails( $atts ) {

		extract( shortcode_atts( array(
			'show_empty'    => true,
			'columns'       => 4,
			'hide_empty'    => 0,
			'orderby'       => 'name',
			'exclude'       => '',
			'number'        => '',
			'fluid_columns' => false
		 ), $atts ) );

		$exclude = array_map( 'intval', explode(',', $exclude) );
		$order = $orderby == 'name' ? 'asc' : 'desc';
        
		if ( 'true' == $show_empty || '1' == $show_empty ) {
			$hide_empty = false;
		} else {
			$hide_empty = true;
		}

		$brands = get_terms( 'product_brands', array( 'hide_empty' => $hide_empty, 'orderby' => $orderby, 'exclude' => $exclude, 'number' => $number, 'order' => $order ) );

		if ( ! $brands )
			return;

		ob_start();

		wc_get_template( 'widgets/brand-thumbnails.php', array(
			'brands'        => $brands,
			'columns'       => $columns,
			'fluid_columns' => $fluid_columns
		), 'woocommerce-brands', PBF_WC_PATH.'/templates/' );

		return ob_get_clean();
	}

	/**
	 * output_product_brand_thumbnails_description function.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return void
	 */
	public function output_product_brand_thumbnails_description( $atts ) {

		extract( shortcode_atts( array(
			'show_empty' => true,
			'columns'    => 1,
			'hide_empty' => 0,
			'orderby'    => 'name',
			'exclude'    => '',
			'number'     => ''
		 ), $atts ) );

		$exclude = array_map( 'intval', explode(',', $exclude) );
		$order = $orderby == 'name' ? 'asc' : 'desc';

		$brands = get_terms( 'product_brands', array( 'hide_empty' => $hide_empty, 'orderby' => $orderby, 'exclude' => $exclude, 'number' => $number, 'order' => $order ) );

		if ( ! $brands )
			return;

		ob_start();

		wc_get_template( 'widgets/brand-thumbnails-description.php', array(
			'brands'  => $brands,
			'columns' => $columns
		), 'woocommerce-brands', PBF_WC_PATH . '/templates/' );

		return ob_get_clean();
	}

	/**
	 * output_brand_products function.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return void
	 */
	public function output_brand_products( $atts ) {

		$atts = shortcode_atts( array(
			'per_page' => '12',
			'columns'  => '4',
			'orderby'  => 'title',
			'order'    => 'desc',
			'brand'    => '',
			'operator' => 'IN'
		), $atts );

		if ( ! $atts['brand'] ) {
			return '';
		}
        

		$ordering_args = WC()->query->get_catalog_ordering_args( $atts['orderby'], $atts['order'] );
		$meta_query    = WC()->query->get_meta_query();
		$query_args    = array(
			'post_type'            => 'product',
			'post_status'          => 'publish',
			'ignore_sticky_posts'  => 1,
			'orderby'              => $ordering_args['orderby'],
			'order'                => $ordering_args['order'],
			'posts_per_page'       => $atts['per_page'],
			'meta_query'           => $meta_query,
			'tax_query'            => array(
				array(
					'taxonomy'     => 'product_brands',
					'terms'        => array_map( 'sanitize_title', explode(',',$atts['brand'] )),
					'field'        => 'slug',
					'operator'     => $atts['operator']
				)
			)
		);

		if ( isset( $ordering_args['meta_key'] ) ) {
			$query_args['meta_key'] = $ordering_args['meta_key'];
		}
        
        $return = $this->product_loop( $query_args, $atts, 'product_cat' );
		WC()->query->remove_ordering_args();
		return $return;
	}

}