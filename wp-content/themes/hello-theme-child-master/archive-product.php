<?php
/**
 * The template for displaying product archive pages.
 */

get_header();

if ( have_posts() ) {
    ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <header class="woocommerce-products-header">
                <?php
                // Muestra las migas de pan
                woocommerce_breadcrumb();
                ?>
                <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
                <?php
                do_action( 'woocommerce_archive_description' );
                ?>
            </header>
            <ul class="products columns-4">
                <?php
                while ( have_posts() ) {
                    the_post();
                    $product = wc_get_product( get_the_ID() );
                    ?>
                    <li <?php wc_product_class(); ?>>
                        <a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                            <?php
                            if ( has_post_thumbnail() ) {
                                the_post_thumbnail( 'woocommerce_thumbnail' );
                            } else {
                                echo '<img src="' . wc_placeholder_img_src() . '" alt="Placeholder" width="300" height="300" />';
                            }
                            ?>
                            <div class="product-details">
                                <span class="sku"><?php echo esc_html( $product->get_sku() ); ?></span>
                                <h2 class="woocommerce-loop-product__title"><?php the_title(); ?></h2>
                                <?php
                                // Agregar la descripción del producto
                                echo '<div class="product-description">' . get_the_excerpt() . '</div>';
                                ?>
                                <div class="product-meta">
                                    <?php
                                    // Muestra las categorías
                                    echo wc_get_product_category_list( $product->get_id(), ', ', '<div class="product-categories">', '</div>' );
                                    ?>
                                    <div class="price-add-to-cart">
                                        <span class="price"><?php echo $product->get_price_html(); ?></span>
                                        <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" aria-label="<?php echo esc_attr( $product->add_to_cart_description() ); ?>" rel="nofollow"><?php echo esc_html( $product->add_to_cart_text() ); ?></a>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </main>
    </div>
    <?php
}

do_action( 'woocommerce_after_shop_loop' );

get_footer();
?>
