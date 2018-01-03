<?php
if(!defined("ABSPATH")){exit;}

class Product_Brands_For_WooCommerce_Settings {
    public function __construct(){
        add_filter("pbf_wc_settings_pages",array(&$this,'add_pages'));
        add_filter("pbf_wc_settings_sections",array(&$this,'add_sections'));
        add_filter("pbf_wc_settings_fields",array(&$this,'add_fields'));
    }
    
    public function add_pages($pages){
        $pages['general'] = array('name' => 'general','title' => __("General",PBF_WC_TXT));
        return $pages;
    }
    
    public function add_sections($secs){
        $secs['general/general'] = array('name' => 'general','title' => __("Name Settings"));
        $secs['general/display'] = array('name' => 'display','title' => __("Output / Display"));
        $secs['general/image-sizes'] = array('name' => 'image-sizes','title' => __("Image Sizes"));
        return $secs;
    }
    
    public function add_fields($fields){
        $fields['general/general'][] = array(
            'id' => 'singular_name',
            'type' => 'text',
            'title' => __("Singular Custom Name",PBF_WC_TXT),
            'desc' => __("Enter a Rename Product Brands into your own requirement",PBF_WC_TXT),
            'default' => 'Product Brand',
        );
        
        $fields['general/general'][] = array(
            'id' => 'plural_name',
            'type' => 'text',
            'default' => 'Product Brands',
            'title' => __("Plural Custom Name",PBF_WC_TXT),
            'desc' => __("Enter a Rename Product Brands into your own requirement",PBF_WC_TXT),
        );
        
        $fields['general/general'][] = array(
            'id' => 'url_slug',
            'type' => 'text',
            'default' => 'product_brands',
            'title' => __("Custom URL Slug",PBF_WC_TXT),
            'desc' => __("Your custom url slug to replace product_brands from url",PBF_WC_TXT),
        );
        
        $fields['general/display'][] = array(
            'id' => 'brand_place',
            'title' => __("Where To Show",PBF_WC_TXT),
            'desc' => __("If you select manual then you can use shortcode",PBF_WC_TXT),
            'type' => 'select',
            'default' => 'single_addtocart',
            'options' => array(
                'single_title' => __('Product Title',PBF_WC_TXT), 
                'single_price' => __("Product Price",PBF_WC_TXT),
                'single_excerpt' => __("Product excerpt",PBF_WC_TXT),
                'single_addtocart' => __("Product Add To Cart",PBF_WC_TXT),
                'custom' => __('Manual',PBF_WC_TXT),
            ),
            'class' => 'select2',
            'style' => 'min-width:450px; width:450px;',
        );
        
        $fields['general/display'][] = array(
            'id' => 'brand_position',
            'title' => __("Position",PBF_WC_TXT),
            'type' => 'select',
            'default' => 'after',
            'options' => array(
                'before' => __('Before',PBF_WC_TXT), 
                'after' => __("After",PBF_WC_TXT),
            ),
            'class' => 'select2',
            'style' => 'min-width:450px; width:450px;',
            'dependency' => array('brand_place' ,'!=','custom'),
        );
        
        $fields['general/display'][] = array(
            'id' => 'brand_imagesize',
            'title' => __("Image Size",PBF_WC_TXT),
            'type' => 'select',
            'default' => 'after',
            'options' => array(
                'small' => __('Small',PBF_WC_TXT), 
                'medium' => __("Medium",PBF_WC_TXT),
                'large' => __("Large",PBF_WC_TXT),
            ),
            'class' => 'select2',
            'style' => 'min-width:450px; width:450px;',
        );
        
        $fields['general/display'][] = array(
            'id' => 'brand_pagelink',
            'title' => __("Link To Brand Page",PBF_WC_TXT),
            'type' => 'switcher',
            'default' => true,
            'desc' => __("Adds Link To Brand Term Page with brand image ?",PBF_WC_TXT),
        );
        
        $fields['general/display'][] = array(
            'id' => 'brand_showmeta',
            'title' => __("Show In Product Meta ",PBF_WC_TXT),
            'type' => 'switcher',
            'default' => true,
            'after' => '<br/><br/>'.__("If enabled this will look like this in single product page").'<br/><img src="'.PBF_WC_URL.'assets/img/info1.jpg"/>',
            'desc' => __("Adds Link To Brand Term Page with brand image ?",PBF_WC_TXT),
        );
        
        
        $imgdesc = __("These settings affect the display and dimensions of images in your catalog – the display on the front-end will still be affected by CSS styles. After changing these settings you may need to %s regenerate your thumbnails %s.");
        $imgdesc = sprintf($imgdesc,'<a href="http://wordpress.org/extend/plugins/regenerate-thumbnails/">','</a>');
        
        $fields['general/image-sizes'][] = array(
            'type' => 'content',
            'content' => $imgdesc,
        );
        
        $fields['general/image-sizes'][] = array(
            'id' => 'small_imagesize',
            'title' => __("Small Image Size",PBF_WC_TXT),
            'default' => array(
                'width' =>'100',
                'height' => '100',
                'crop' => 'on',
            ),
            'type' => 'image_size',
        );
        
        $fields['general/image-sizes'][] = array(
            'id' => 'medium_imagesize',
            'title' => __("Medium Image Size",PBF_WC_TXT),
            'default' => array(
                'width' =>'200',
                'height' => '200',
                'crop' => 'on',
            ),
            'type' => 'image_size',
        );
        
        $fields['general/image-sizes'][] = array(
            'id' => 'large_imagesize',
            'title' => __("Large Image Size",PBF_WC_TXT),
            'default' => array(
                'width' =>'350',
                'height' => '350',
                'crop' => 'on',
            ),
            'type' => 'image_size',
        );

        $fields['general/image-sizes'][] = array(
            'id' => 'default_image',
            'title' => __("Default Brand Image",PBF_WC_TXT),
            'desc' => __("This Image Will Be Used If No Image Selected For Brand",PBF_WC_TXT),
            'type' => 'image',
        );
        
        
        return $fields;
    }
}