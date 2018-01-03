<?php
if(!defined("ABSPATH")){exit;}

final class Product_Brands_For_WooCommerce extends VSP_Framework {
    protected static $_instance = null;
    
    public static function instance(){
        if(null == self::$_instance){
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    public function __construct() {
        parent::__construct(array(
            'plugin_slug' => PBF_WC_SLUG,
            'db_slug' => PBF_WC_DB,
            'plugin_name' => PBF_WC_NAME,
            'hook_slug' => 'pbf_wc_',
            'version' =>'1.0',
            'settings_page'  => array(
                'show_adds' => false,
                'show_faq' => false,
                'menu_parent' => 'woocommerce',
                'menu_title' => __("Product Brands For WC Settings",PBF_WC_TXT),
                'menu_type' => 'submenu',
                'menu_slug' => 'product-brands-for-woocommerce',
                'menu_capability' => 'manage_woocommerce',
                'framework_title' => __("Product Brands For WooCommerce",PBF_WC_TXT),
                'ajax_save' => false,

                'is_single_page' => false,
                'is_sticky_header' => true,
                'options_name' => '_product_brands_wc',
                'style' => 'simple',
                'status_page' => false, 
                'buttons' => array(
                    'reset' => false,
                    'restore' => false,
                    'save' => __("Save Settings"),
                ), 
            ),
            'addons' => false,
            'plugin_file' => PBF_WC_FILE,
        ));
    }
    
    public function load_required_files(){
        if(vsp_is_request('admin')){
            add_action("update_option__product_brands_wc",array(&$this,'update_flush_dns'));
        }

        vsp_load_file(PBF_WC_INC.'class-register-taxonomy.php');
        vsp_load_file(PBF_WC_INC.'class-settings.php');
        vsp_load_file(PBF_WC_INC.'class-*');
        $this->settings_fields = new Product_Brands_For_WooCommerce_Settings;
    }
    
    public function update_flush_dns(){
        update_option('pbf_wc_flush_permalink','flush_now');
    }

    public function settings_init_before(){
        if(vsp_is_request("admin")){
            vsp_load_file(PBF_WC_INC.'admin/class-admin-metaboxes.php');
        }
    }
    
    public function load_textdomain($file = '',$domain = ''){
        if (PBF_WC_TXT === $domain) { return PBF_WC_LANGUAGE_PATH.'/'.get_locale().'.mo';}
        return $file;
    }
    
    public function init_before(){
        $this->frontend = new Product_Brands_For_WooCommerce_Frontend_Display;
    }
    
    public function add_assets(){
        vsp_load_style("pfb-wc-style",PBF_WC_URL.'assets/css/style.css',PBF_WC_V);
        vsp_load_style('pbf-wc-style');
    }
}