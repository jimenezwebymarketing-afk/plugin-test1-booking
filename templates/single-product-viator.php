<?php
/**
 * Custom single product template inspired by Viator.
 *
 * @package Viator_Product_Template
 */

defined( 'ABSPATH' ) || exit;

global $product;

get_header( 'shop' );
?>
<div class="viator-product-shell">
    <?php do_action( 'woocommerce_before_main_content' ); ?>

    <?php while ( have_posts() ) : the_post(); ?>
        <div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'viator-product', $product ); ?>>
            <div class="viator-product__hero">
                <div class="viator-product__media">
                    <?php woocommerce_show_product_images(); ?>
                </div>

                <div class="viator-product__summary">
                    <div class="viator-product__breadcrumb">
                        <?php woocommerce_breadcrumb(); ?>
                    </div>

                    <h1 class="viator-product__title"><?php the_title(); ?></h1>

                    <div class="viator-product__meta">
                        <?php woocommerce_template_single_rating(); ?>
                        <?php if ( $product && $product->get_review_count() ) : ?>
                            <span class="viator-product__reviews-count">
                                (<?php echo esc_html( $product->get_review_count() ); ?>)
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="viator-product__tagline">
                        <?php echo wp_kses_post( wp_trim_words( $product->get_short_description(), 40 ) ); ?>
                    </div>

                    <div class="viator-product__price">
                        <?php woocommerce_template_single_price(); ?>
                    </div>

                    <div class="viator-product__actions">
                        <?php woocommerce_template_single_add_to_cart(); ?>
                    </div>

                    <ul class="viator-product__guarantees">
                        <li>✓ Reserva ahora y paga después</li>
                        <li>✓ Cancelación gratuita disponible</li>
                        <li>✓ Productos verificados</li>
                    </ul>
                </div>
            </div>

            <div class="viator-product__content">
                <section class="viator-product__section viator-product__highlights">
                    <h2><?php esc_html_e( 'Destacados', 'viator-product-template' ); ?></h2>
                    <?php echo apply_filters( 'the_content', $product->get_short_description() ); ?>
                </section>

                <div class="viator-product__details-grid">
                    <section class="viator-product__section">
                        <h3><?php esc_html_e( 'Descripción', 'viator-product-template' ); ?></h3>
                        <?php the_content(); ?>
                    </section>

                    <section class="viator-product__section">
                        <h3><?php esc_html_e( 'Información adicional', 'viator-product-template' ); ?></h3>
                        <?php wc_display_product_attributes( $product ); ?>
                    </section>
                </div>

                <section class="viator-product__section viator-product__reviews">
                    <h3><?php esc_html_e( 'Opiniones de viajeros', 'viator-product-template' ); ?></h3>
                    <?php comments_template(); ?>
                </section>
            </div>
        </div>
    <?php endwhile; // end of the loop. ?>

    <?php do_action( 'woocommerce_after_main_content' ); ?>
</div>
<?php
get_footer( 'shop' );
?>
