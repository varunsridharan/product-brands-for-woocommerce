<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @wordpress-plugin
 * Plugin Name:  Product Brands For WooCommerce
 * Plugin URI:   https://wordpress.org/plugins/product-brands-for-woocommerce/
 * Description:  Create, assign and list brands for products, and allow customers to filter by brand.
 * Version:           1.0
 * Author:            Varun Sridharan
 * Author URI:        http://varunsridharan.in
 * Text Domain:       product-brands-for-woocommerce
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt 
 * Domain Path: languages/
 */
define('PBF_WC_DEPEN','woocommerce/woocommerce.php');
define('PBF_WC_NAME', 'Product Brands For WooCommerce'); # Plugin Name
define('PBF_WC_SLUG', 'product-brands-for-woocommerce'); # Plugin Slug
define('PBF_WC_TXT',  'product-brands-for-woocommerce'); #plugin lang Domain
define('PBF_WC_DB', 'pbf_wc_');
define('PBF_WC_V','1.0'); # Plugin Version

define('PBF_WC_FILE',plugin_basename( __FILE__ ));
define('PBF_WC_PATH',plugin_dir_path( __FILE__ )); # Plugin DIR
define('PBF_WC_FRAMEWORK',PBF_WC_PATH.'vs-framework/'); # Plugin DIR
define('PBF_WC_INC',PBF_WC_PATH.'includes/'); # Plugin INC Folder
define('PBF_WC_LANGUAGE_PATH',PBF_WC_PATH.'languages/'); # Plugin Language Folder
define('PBF_WC_ADMIN',PBF_WC_INC.'admin/'); # Plugin Admin Folder

define('PBF_WC_URL',plugins_url('', __FILE__ ).'/');  # Plugin URL
define('PBF_WC_CSS',PBF_WC_URL.'includes/css/'); # Plugin CSS URL
define('PBF_WC_IMG',PBF_WC_URL.'includes/img/'); # Plugin IMG URL
define('PBF_WC_JS',PBF_WC_URL.'includes/js/'); # Plugin JS URL

require_once(PBF_WC_PATH.'vsp-framework/vsp-init.php');
require_once(PBF_WC_INC.'functions.php');

if(function_exists("vsp_mayby_framework_loader")){
    vsp_mayby_framework_loader(PBF_WC_PATH);
}

if(is_admin()){
    register_activation_hook( __FILE__, 'pbf_wc_activate_plugin' );
    register_deactivation_hook( __FILE__, 'pbf_wc_deactivate_plugin' );
    register_deactivation_hook( PBF_WC_DEPEN, 'pbf_wc_dependency_deactivate' );
   
    if(!function_exists("pbf_wc_activate_plugin")){ 
        function pbf_wc_activate_plugin(){
            $is_old = get_option("_product_brands_wc",false);
            if($is_old === false){
                $new_options = array('singular_name' => '','plural_name' => '','url_slug' => '','brand_place' => '','brand_position' => '','brand_imagesize' => 'small','html_template' => '','custom_style' => '','small_imagesize' => '','medium_imagesize' => '','large_imagesize' => '','brand_pagelink' => true,'brand_showmeta' => true,'default_image' => '',);
                $options = array('pbf_wc_custom_brands_name' => 'singular_name','pbf_wc_custom_brands_name_plural' => 'plural_name','pbf_wc_custom_url_slug' => 'url_slug','pbf_wc_whereto_show' => 'brand_place','pbf_wc_img_position' => 'brand_position','pbf_wc_img_size' => 'brand_imagesize','pbf_wc_html_template' => 'html_template','pbf_wc_custom_style' => 'custom_style','pbf_wc_imgsize_small' => 'small_imagesize','pbf_wc_imgsize_medium' => 'medium_imagesize','pbf_wc_imgsize_large' => 'large_imagesize',);

                foreach($options as $option => $k){
                    $new_val = get_option($option,false);
                    if($new_val !== false){
                        $new_options[$k] = $new_val;
                    }
                    
                    if(!in_array($option,array('pbf_wc_html_template','pbf_wc_custom_style'))){
                        delete_option($option);
                    }
                }
                
                add_option("_product_brands_wc",$new_options);
            }
            update_option('pbf_wc_flush_permalink','flush_now');            
        }
    }
    
    if(!function_exists("pbf_wc_deactivate_plugin")){ function pbf_wc_deactivate_plugin(){} }
    
    if(!function_exists("pbf_wc_dependency_deactivate")){ function pbf_wc_dependency_deactivate(){} }
}

add_action("vsp_framework_loaded",'pbf_wc_loader');

function pbf_wc_loader(){
    if(!vsp_wc_active()){
        $msg = sprintf(__("%s Requires WooCommerce To Be Installed & Activated."),'<strong>'.PBF_WC_NAME.'</strong>');
        vsp_notice_error($msg,'pbf_wc_active_issue',1,array(),array('on_ajax' => false));
        return;
    }
    
    require_once(PBF_WC_PATH.'bootstrap.php');
    Product_Brands_For_WooCommerce::instance();
}

add_action('plugins_loaded', 'pbf_wc_txt_loader');

if(!function_exists("pbf_wc_txt_loader")){
    function pbf_wc_txt_loader(){
        load_plugin_textdomain(PBF_WC_TXT, false, PBF_WC_LANGUAGE_PATH );
    }
}