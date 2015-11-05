<?php
/**
 * Plugin Name:       Product Brands For WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/product-brands-for-woocommerce/
 * Description:       Create, assign and list brands for products, and allow customers to filter by brand.
 * Version:           0.1
 * Author:            Varun Sridharan
 * Author URI:        http://varunsridharan.in
 * Text Domain:       product-brands-for-woocommerce
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt 
 * GitHub Plugin URI: @TODO
 */

if ( ! defined( 'WPINC' ) ) { die; }
 
class Product_Brands_For_WooCommerce {
	/**
	 * @var string
	 */
	public $version = '0.1';

	/**
	 * @var WooCommerce The single instance of the class
	 * @since 2.1
	 */
	protected static $_instance = null;
    
    protected static $functions = null;

    /**
     * Creates or returns an instance of this class.
     */
    public static function get_instance() {
        if ( null == self::$_instance ) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    /**
     * Class Constructor
     */
    public function __construct() {
        $this->define_constant();
        $this->load_required_files();
        $this->init_class();
        add_action( 'init', array( $this, 'init' ));
    }
    
    /**
     * Triggers When INIT Action Called
     */
    public function init(){
        add_action('plugins_loaded', array( $this, 'after_plugins_loaded' ));
        add_filter('load_textdomain_mofile',  array( $this, 'load_plugin_mo_files' ), 10, 2);
    }
    
    /**
     * Loads Required Plugins For Plugin
     */
    private function load_required_files(){ 
		$this->load_files(PBF_WC_PATH.'includes/class-*.php');
       if($this->is_request('admin')){
           $this->load_files(PBF_WC_PATH.'admin/class-*.php');
       } 

    }
    
    /**
     * Inits loaded Class
     */
    private function init_class(){
        new Product_Brands_For_WooCommerce_Activation('pbf-wc','pbf-wc-welcome','welcome-template.php','Welcome To Product Brands Fro WooCommerce',__FILE__);
		self::$functions = new Product_Brands_For_WooCommerce_Function;
        
        if($this->is_request('admin')){
            $this->admin = new Product_Brands_For_WooCommerce_Admin;
        }
		
		if($this->is_request('frontend')){
			new Product_Brands_For_WooCommerce_FrontEnd;
		}
    }
    
     

    protected function load_files($path,$type = 'require'){
        foreach( glob( $path ) as $files ){
            if($type == 'require'){
                require_once( $files );
            } else if($type == 'include'){
                include_once( $files );
            }
        } 
    }
    
    /**
     * Set Plugin Text Domain
     */
    public function after_plugins_loaded(){
        load_plugin_textdomain(PBF_WC_TXT, false, PBF_WC_LANGUAGE_PATH );
    }
    
    /**
     * load translated mo file based on wp settings
     */
    public function load_plugin_mo_files($mofile, $domain) {
        if (PBF_WC_TXT === $domain)
            return PBF_WC_LANGUAGE_PATH.'/'.get_locale().'.mo';

        return $mofile;
    }
    
    /**
     * Define Required Constant
     */
    private function define_constant(){
        $this->define('PBF_WC_NAME','Product Brands For WooCommerce'); # Plugin Name
        $this->define('PBF_WC_SLUG','pb-wc'); # Plugin Slug
		$this->define('PBF_WC_DB','pbf_wc_'); # Plugin Slug
        $this->define('PBF_WC_PATH',plugin_dir_path( __FILE__ )); # Plugin DIR
        $this->define('PBF_WC_LANGUAGE_PATH',PBF_WC_PATH.'languages');
        $this->define('PBF_WC_TXT','product-brands-for-woocommerce'); #plugin lang Domain
        $this->define('PBF_WC_URL',plugins_url('', __FILE__ )); 
        $this->define('PBF_WC_FILE',plugin_basename( __FILE__ ));
        $this->define('PBF_WC_V',$this->version);
    }
    
    /**
	 * Define constant if not already set
	 * @param  string $name
	 * @param  string|bool $value
	 */
    protected function define($key,$value){
        if(!defined($key)){
            define($key,$value);
        }
    }
     
	/**
	 * What type of request is this?
	 * string $type ajax, frontend or admin
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}
    
    
    
}
new Product_Brands_For_WooCommerce;
?>