<?php
/**
 * Custom single product template inspired by Viator.
 *
 * @package Viator_Product_Template
 */

defined( 'ABSPATH' ) || exit;

global $product;

$tagline     = get_post_meta( get_the_ID(), '_viator_tagline', true );
$highlights  = get_post_meta( get_the_ID(), '_viator_highlights', true );
$guarantees  = get_post_meta( get_the_ID(), '_viator_guarantees', true );

$highlights_list = array_filter( array_map( 'trim', explode( "\n", (string) $highlights ) ) );
$guarantees_list = array_filter( array_map( 'trim', explode( "\n", (string) $guarantees ) ) );

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
                        <?php
                        if ( ! empty( $tagline ) ) {
                            echo esc_html( $tagline );
                        } else {
                            echo wp_kses_post( wp_trim_words( $product->get_short_description(), 40 ) );
                        }
                        ?>
                    </div>

                    <div class="viator-product__price">
                        <?php woocommerce_template_single_price(); ?>
                    </div>

                    <div class="viator-product__actions">
                        <?php woocommerce_template_single_add_to_cart(); ?>
                    </div>

                    <ul class="viator-product__guarantees">
                        <?php if ( ! empty( $guarantees_list ) ) : ?>
                            <?php foreach ( $guarantees_list as $item ) : ?>
                                <li>✓ <?php echo esc_html( $item ); ?></li>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <li>✓ <?php esc_html_e( 'Reserva ahora y paga después', 'viator-product-template' ); ?></li>
                            <li>✓ <?php esc_html_e( 'Cancelación gratuita disponible', 'viator-product-template' ); ?></li>
                            <li>✓ <?php esc_html_e( 'Productos verificados', 'viator-product-template' ); ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="viator-product__content">
                <section class="viator-product__section viator-product__highlights">
                    <h2><?php esc_html_e( 'Destacados', 'viator-product-template' ); ?></h2>
                    <?php if ( ! empty( $highlights_list ) ) : ?>
                        <ul>
                            <?php foreach ( $highlights_list as $highlight ) : ?>
                                <li><?php echo esc_html( $highlight ); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <?php echo apply_filters( 'the_content', $product->get_short_description() ); ?>
                    <?php endif; ?>
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
