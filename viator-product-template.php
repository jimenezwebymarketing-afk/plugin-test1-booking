<?php
/**
 * Plugin Name: Viator Product Template for WooCommerce
 * Description: Displays WooCommerce products using a Viator-inspired layout without requiring theme overrides.
 * Version: 1.0.0
 * Author: OpenAI Assistant
 * License: GPL-2.0-or-later
 * Text Domain: viator-product-template
 */

if ( ! defined( 'ABSPATH' ) ) {
exit;
}

define( 'VIATOR_PRODUCT_TEMPLATE_PATH', plugin_dir_path( __FILE__ ) );
define( 'VIATOR_PRODUCT_TEMPLATE_URL', plugin_dir_url( __FILE__ ) );
require_once VIATOR_PRODUCT_TEMPLATE_PATH . 'includes/class-viator-product-template.php';

new Viator_Product_Template();
