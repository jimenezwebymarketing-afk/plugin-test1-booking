<?php
/**
 * Core plugin loader.
 *
 * @package Viator_Product_Template
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Loads template overrides and assets.
 */
class Viator_Product_Template {
    /**
     * Hook registrations.
     */
    public function __construct() {
        add_filter( 'template_include', array( $this, 'filter_product_template' ), 99 );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_action( 'init', array( $this, 'load_textdomain' ) );
    }

    /**
     * Load translation files.
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'viator-product-template',
            false,
            dirname( plugin_basename( VIATOR_PRODUCT_TEMPLATE_PATH . 'viator-product-template.php' ) ) . '/languages'
        );
    }

    /**
     * Swap in the Viator-style template for single product pages.
     *
     * @param string $template Path to the located template.
     * @return string
     */
    public function filter_product_template( $template ) {
        if ( ! $this->should_use_viator_template() ) {
            return $template;
        }

        $custom_template = VIATOR_PRODUCT_TEMPLATE_PATH . 'templates/single-product-viator.php';

        return file_exists( $custom_template ) ? $custom_template : $template;
    }

    /**
     * Enqueue frontend assets for the Viator template.
     */
    public function enqueue_assets() {
        if ( ! $this->should_use_viator_template() ) {
            return;
        }

        wp_enqueue_style(
            'viator-product-template',
            VIATOR_PRODUCT_TEMPLATE_URL . 'assets/css/viator-product-template.css',
            array(),
            '1.0.0'
        );
    }

    /**
     * Determine whether the Viator layout should be applied.
     *
     * @return bool
     */
    private function should_use_viator_template() {
        return function_exists( 'is_product' ) && is_product();
    }
}
