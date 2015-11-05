<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/woocommerce-role-based-price/
 *
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    @TODO
 * @subpackage @TODO
 * @author     Varun Sridharan <varunsridharan23@gmail.com>
 */
if ( ! defined( 'WPINC' ) ) { die; }

class Product_Brands_For_WooCommerce_Admin extends Product_Brands_For_WooCommerce {

    /**
	 * Initialize the class and set its properties.
	 * @since      0.1
	 */
	public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ),99);
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_filter( 'plugin_row_meta', array($this, 'plugin_row_links' ), 10, 2 );
        add_action( 'admin_init', array( $this, 'admin_init' ));
        add_action( 'plugins_loaded', array( $this, 'init' ) );
        add_filter( 'woocommerce_get_settings_pages',  array($this,'settings_page') ); 

	}

    /**
     * Inits Admin Sttings
     */
    public function admin_init(){ 
		new Product_Brands_For_WooCommerce_Admin_Function;
    }
 
    
	/**
	 * Add a new integration to WooCommerce.
	 */
	public function settings_page( $integrations ) {
        foreach(glob(PBF_WC_PATH.'admin/woocommerce-settings*.php' ) as $file){
            $integrations[] = require_once($file);
        }
		return $integrations;
	}
    
    /**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() { 
        if(in_array($this->current_screen() , $this->get_screen_ids())) {
            wp_enqueue_style(PBF_WC_SLUG.'_core_style',plugins_url('css/style.css',__FILE__) , array(), $this->version, 'all' );  
        }
	}
	
    
    /**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {  
        if(in_array($this->current_screen() , $this->get_screen_ids())) {
            wp_enqueue_media();
            wp_enqueue_script(PBF_WC_SLUG.'_core_script', plugins_url('js/script.js',__FILE__), array('jquery'), $this->version, false ); 
        }
 
	}
    
    /**
     * Gets Current Screen ID from wordpress
     * @return string [Current Screen ID]
     */
    public function current_screen(){
       $screen =  get_current_screen();
       return $screen->id;
    }
    
    /**
     * Returns Predefined Screen IDS
     * @return [Array] 
     */
    public function get_screen_ids(){
        $screen_ids = array();
		$screen_ids[] = 'edit-product_brands';
        return $screen_ids;
    }
    
    
    /**
	 * Adds Some Plugin Options
	 * @param  array  $plugin_meta
	 * @param  string $plugin_file
	 * @since 0.11
	 * @return array
	 */
	public function plugin_row_links( $plugin_meta, $plugin_file ) {
		if ( PBF_WC_FILE == $plugin_file ) {
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=wc-settings&tab=products&section=pb_wc'), __('Settings', PBF_WC_TXT) );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', 'http://wordpress.org/plugins/product-brands-for-woocommerce', __('F.A.Q', PBF_WC_TXT) );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', 'https://github.com/technofreaky/product-brands-for-woocommerce', __('View On Github', PBF_WC_TXT) );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', 'https://github.com/technofreaky/product-brands-for-woocommerce', __('Report Issue', PBF_WC_TXT) );
            $plugin_meta[] = sprintf('&hearts; <a href="%s">%s</a>', 'https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=varunsridharan23%40gmail%2ecom&lc=BM&item_name=Product%20Brands%20For%20WooCommerce&button_subtype=services&no_note=0&currency_code=USD&bn=PP%2dBuyNowBF%3abtn_buynowCC_LG%2egif%3aNonHostedGuest', __('Donate', PBF_WC_TXT) );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', 'http://varunsridharan.in/plugin-support/', __('Contact Author', PBF_WC_TXT) );
		}
		return $plugin_meta;
	}	    
}

?>