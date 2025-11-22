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
        add_action( 'add_meta_boxes', array( $this, 'register_meta_box' ) );
        add_action( 'save_post_product', array( $this, 'save_meta_box' ), 10, 2 );
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

    /**
     * Register Viator-specific meta fields inside the product editor.
     */
    public function register_meta_box() {
        add_meta_box(
            'viator_product_template_meta',
            __( 'Plantilla estilo Viator', 'viator-product-template' ),
            array( $this, 'render_meta_box' ),
            'product',
            'normal',
            'default'
        );
    }

    /**
     * Render fields for custom Viator data.
     *
     * @param WP_Post $post Current post object.
     */
    public function render_meta_box( $post ) {
        $tagline     = get_post_meta( $post->ID, '_viator_tagline', true );
        $highlights  = get_post_meta( $post->ID, '_viator_highlights', true );
        $guarantees  = get_post_meta( $post->ID, '_viator_guarantees', true );

        wp_nonce_field( 'viator_product_template_save', 'viator_product_template_nonce' );
        ?>
        <p>
            <label for="viator-tagline"><strong><?php esc_html_e( 'Titular corto (hero)', 'viator-product-template' ); ?></strong></label><br />
            <input type="text" id="viator-tagline" name="viator_tagline" class="widefat" value="<?php echo esc_attr( $tagline ); ?>" placeholder="<?php esc_attr_e( 'Ejemplo: Explora lo mejor de la ciudad en un día', 'viator-product-template' ); ?>" />
        </p>
        <p>
            <label for="viator-highlights"><strong><?php esc_html_e( 'Destacados (uno por línea)', 'viator-product-template' ); ?></strong></label><br />
            <textarea id="viator-highlights" name="viator_highlights" class="widefat" rows="4" placeholder="<?php esc_attr_e( "Incluye el museo, Paseo en barco, Degustación local", 'viator-product-template' ); ?>"><?php echo esc_textarea( $highlights ); ?></textarea>
            <span class="description"><?php esc_html_e( 'Si se deja vacío, se usará la descripción corta del producto.', 'viator-product-template' ); ?></span>
        </p>
        <p>
            <label for="viator-guarantees"><strong><?php esc_html_e( 'Garantías (una por línea)', 'viator-product-template' ); ?></strong></label><br />
            <textarea id="viator-guarantees" name="viator_guarantees" class="widefat" rows="3" placeholder="<?php esc_attr_e( 'Reserva ahora y paga después', 'viator-product-template' ); ?>"><?php echo esc_textarea( $guarantees ); ?></textarea>
            <span class="description"><?php esc_html_e( 'Si se deja vacío, se mostrarán las garantías predeterminadas.', 'viator-product-template' ); ?></span>
        </p>
        <?php
    }

    /**
     * Save Viator meta fields.
     *
     * @param int     $post_id Post ID.
     * @param WP_Post $post    Post object.
     */
    public function save_meta_box( $post_id, $post ) {
        if ( ! isset( $_POST['viator_product_template_nonce'] ) || ! wp_verify_nonce( $_POST['viator_product_template_nonce'], 'viator_product_template_save' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( 'product' !== $post->post_type ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        $tagline    = isset( $_POST['viator_tagline'] ) ? sanitize_text_field( wp_unslash( $_POST['viator_tagline'] ) ) : '';
        $highlights = isset( $_POST['viator_highlights'] ) ? implode( "\n", array_filter( array_map( 'sanitize_text_field', array_map( 'trim', explode( "\n", wp_unslash( $_POST['viator_highlights'] ) ) ) ) ) ) : '';
        $guarantees = isset( $_POST['viator_guarantees'] ) ? implode( "\n", array_filter( array_map( 'sanitize_text_field', array_map( 'trim', explode( "\n", wp_unslash( $_POST['viator_guarantees'] ) ) ) ) ) ) : '';

        update_post_meta( $post_id, '_viator_tagline', $tagline );
        update_post_meta( $post_id, '_viator_highlights', $highlights );
        update_post_meta( $post_id, '_viator_guarantees', $guarantees );
    }
}
