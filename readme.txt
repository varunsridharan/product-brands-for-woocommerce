=== Product Brands For WooCommerce ===
Contributors: varunms,arnis.arbidans
Author URI: http://varunsridharan.in/
Plugin URL: https://wordpress.org/plugins/product-brands-for-woocommerce/
Tags: WooCommerce,wc,products,brands,product brand,product brands,wc brands,wc product brands
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=36Y7KSYPF7KTU
Requires at least: 3.0
Tested up to: 5.0
WC requires at least: 1.0
WC tested up to: 2.8
Stable tag: 0.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html 

Create, assign and list brands for products, and allow customers to filter by brand.

== Description ==
= Create, assign and list product brands =

The brands extension for WooCommerce allows you to create brands for your shop; each brand can be named, described and assigned an image. Brands can then be:

* Displayed as thumbnails on a page using a shortcode
* Assigned to a product
* Used to filter products using a special layered nav widget

= Features =
* Automated / Manual Embbed 
* Configurable Position
* Configurable Image Size
* Custom HTML Template
* Custom Styling  

= Options =
**Settings Menu**
` WooCommerce Settings => Products => Product Brands `

**Brands Menu**
` WooCommerce Products => Brands `

= Available Shortcode =
* `[pbf_wc]` For Listing Brand Image For Single Product
* `[pbf_wc_list]` For Listing All Brands In One Page Like A-Z Filter
* `[pbf_wc_grid]` For Listing All Brands In One Page With Thier Brand Image

= [pbf_wc] Options =
Use this shortcode to get product brand image any where `[pbf_wc]` 
* **Product ID :** use `[pbf_wc]` to get automaticly product id or use  `[pbf_wc id='23']`
* **Image Size :** small, medium, large `[pbf_wc size='small']` 
* **Remove Custom Style :** To Disable Custom Style Use  `[pbf_wc style='false']`


= [pbf_wc_list] Options =
* **hide_empty :** Hide A Brand If No Products Linked `[pbf_wc_list hide_empty='true']`
* **orderby :**  Order Brands By **asc** OR **desc** `[pbf_wc_list orderby='asc']`
* **exclude :**  Exclude Brands by entering the brand ids `[pbf_wc_list exclude='1,2,3']`

= [pbf_wc_grid] Options =
* **hide_empty :** Hide A Brand If No Products Linked `[pbf_wc_list hide_empty='true']`
* **orderby :**  Order Brands By **asc** OR **desc** `[pbf_wc_list orderby='asc']`
* **exclude :**  Exclude Brands by entering the brand ids `[pbf_wc_list exclude='1,2,3']`
* **columns :**  Set Custom Coloum For Display `[pbf_wc_grid columns='3']`



== Screenshots ==
1. Settings Page
2. Adding Product Brands
3. Setting Product Brands For Product

== Installation ==

= Minimum Requirements =

* WordPress 3.8 or greater
* PHP version 5.2.4 or greater
* MySQL version 5.0 or greater

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't need to leave your web browser. To do an automatic install of Product Brands For WooCommerce, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type "Product Brands For WooCommerce"  and click Search Plugins. Once you've found our plugin you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by simply clicking "Install Now"

= Manual installation =

The manual installation method involves downloading our plugin and uploading it to your Web Server via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

1. Installing alternatives:
 * via Admin Dashboard:
 * Go to 'Plugins > Add New', search for "Product Brands For WooCommerce", click "install"
 * OR via direct ZIP upload:
 * Upload the ZIP package via 'Plugins > Add New > Upload' in your WP Admin
 * OR via FTP upload:
 * Upload `product-brands-for-woocommerce` folder to the `/wp-content/plugins/` directory
 
2. Activate the plugin through the 'Plugins' menu in WordPress
 

== Changelog == 
= 0.5 =
* Fixed Issue with settings page. (Still showing old settings page fixed).
* Minor Bug Fix Done.

= 0.4 = 
* Fixed Minor Bugs
* Added Feature To Rename `Product Brands`

= 0.3 =
* Added Widgets
* Added WC Filter Widgets

= 0.2 =
* Added 2 New Shortcode `pbf_wc_list` AND `pbf_wc_grid`
* Added 2 New Templates
* Fixed Bugs
* Updated To Latest WP & WC

= 0.1 =
* Base Version
