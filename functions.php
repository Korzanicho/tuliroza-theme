<?php

/**
 * Loads the StoreFront parent theme stylesheet.
 */

function storefront_child_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

}
add_action( 'wp_enqueue_scripts', 'storefront_child_theme_enqueue_styles' );

/**
 * Note: Do Not alter or remove the code above this text and only add your custom functions below here.
 */


//Usuwa niektóre style 
function remove_wc_style(){
    wp_dequeue_style('storefront-woocommerce-style');
}
add_action('wp_print_styles', 'remove_wc_style');

add_action('wp_enqueue_script','remove_wc_style' , 25);
add_filter('storefront_customizer_enabled' , false);
add_filter('storefront_customizer_css', '__return_false');


function wpcustom_deregister_scripts_and_styles(){
    wp_deregister_style('storefront-woocommerce-style');
    wp_deregister_style('storefront-style');
}
add_action( 'wp_print_styles', 'wpcustom_deregister_scripts_and_styles', 100 );




//Dodaję własne style
function add_custom_styles() {
    wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri() . '/custom-style.css' );
    wp_enqueue_style( 'google-font-a', 'https://fonts.googleapis.com/css?family=Devonshire&amp;subset=latin-ext' );
    wp_enqueue_style( 'google-font-b', 'https://fonts.googleapis.com/css?family=Lato:300i,400,700&amp;subset=latin-ext' );
    wp_enqueue_style( 'fa', 'https://use.fontawesome.com/releases/v5.6.3/css/all.css' );

}
add_action( 'wp_enqueue_scripts', 'add_custom_styles' );

//Usuwanie elementów
function remove_shop_elements() {
//    remove_action('woocommerce_after_shop_loop_item','woocommerce_template_loop_add_to_cart' , 10);
    remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_rating' , 5);
}
add_action( 'init', 'remove_shop_elements' );

//Dodaje nowe znaczniki do karty produktu
function add_product_wrapper() {
    echo '<div class=product-grid-wrapper>';
}
add_action( 'woocommerce_before_shop_loop_item', 'add_product_wrapper' );

//Dodaje nowe znaczniki do karty produktu
function add_product_desc() {
    echo '<div class=product-grid-desc>';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'add_product_desc' );

//Dodaje nowe znaczniki do karty produktu
function add_product_divider() {
    echo '<div class=product-grid-divider></div>';
}
add_action( 'woocommerce_shop_loop_item_title', 'add_product_divider' );

//Sekcja Najnowsze produkty
if ( ! function_exists( 'storefront_recent_products' ) ) {
    /**
	 * Display Recent Products
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @since  1.0.0
	 * @param array $args the product section args.
	 * @return void
	 */
    function storefront_recent_products( $args ) {
        $args = apply_filters(
            'storefront_recent_products_args', array(
                'limit'   => 4,
                'columns' => 4,
                'orderby' => 'date',
                'order'   => 'desc',
                'title'   => __( 'New In', 'storefront' ),
            )
        );

        $shortcode_content = storefront_do_shortcode(
            'products', apply_filters(
                'storefront_recent_products_shortcode_args', array(
                    'orderby'  => esc_attr( $args['orderby'] ),
                    'order'    => esc_attr( $args['order'] ),
                    'per_page' => intval( $args['limit'] ),
                    'columns'  => intval( $args['columns'] ),
                )
            )
        );

        /**
		 * Only display the section if the shortcode returns products
		 */
        if ( false !== strpos( $shortcode_content, 'product' ) ) {
            echo '<section class="storefront-product-section storefront-recent-products" aria-label="' . esc_attr__( 'Recent Products', 'storefront' ) . '">';

            do_action( 'storefront_homepage_before_recent_products' );

            echo '<div class="section-title-outer">';
            echo '<div class="section-title-wrapper">';
            echo '<span class="section-title-separator"><span class="section-title-line"></span></span>';
            echo '<h4 class="section-title">' . wp_kses_post( $args['title'] ) . '</h4>';
            echo '<span class="section-title-separator"><span class="section-title-line"></span></span>';
            echo '</div>';
            echo '</div>';

            do_action( 'storefront_homepage_after_recent_products_title' );

            echo $shortcode_content; // WPCS: XSS ok.

            do_action( 'storefront_homepage_after_recent_products' );

            echo '</section>';
        }
    }
}


//Produkty Wyróżnione
if ( ! function_exists( 'storefront_featured_products' ) ) {
    /**
	 * Display Featured Products
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @since  1.0.0
	 * @param array $args the product section args.
	 * @return void
	 */
    function storefront_featured_products( $args ) {
        $args = apply_filters(
            'storefront_featured_products_args', array(
                'limit'      => 4,
                'columns'    => 4,
                'orderby'    => 'date',
                'order'      => 'desc',
                'visibility' => 'featured',
                'title'      => __( 'We Recommend', 'storefront' ),
            )
        );

        $shortcode_content = storefront_do_shortcode(
            'products', apply_filters(
                'storefront_featured_products_shortcode_args', array(
                    'per_page'   => intval( $args['limit'] ),
                    'columns'    => intval( $args['columns'] ),
                    'orderby'    => esc_attr( $args['orderby'] ),
                    'order'      => esc_attr( $args['order'] ),
                    'visibility' => esc_attr( $args['visibility'] ),
                )
            )
        );

        /**
		 * Only display the section if the shortcode returns products
		 */
        if ( false !== strpos( $shortcode_content, 'product' ) ) {
            echo '<section class="storefront-product-section storefront-featured-products" aria-label="' . esc_attr__( 'Featured Products', 'storefront' ) . '">';

            do_action( 'storefront_homepage_before_featured_products' );

            echo '<div class="section-title-outer">';
            echo '<div class="section-title-wrapper">';
            echo '<span class="section-title-separator"><span class="section-title-line"></span></span>';
            echo '<h4 class="section-title">' . wp_kses_post( $args['title'] ) . '</h4>';
            echo '<span class="section-title-separator"><span class="section-title-line"></span></span>';
            echo '</div>';
            echo '</div>';

            do_action( 'storefront_homepage_after_featured_products_title' );

            echo $shortcode_content; // WPCS: XSS ok.

            do_action( 'storefront_homepage_after_featured_products' );

            echo '</section>';
        }
    }
}

//Popularne Produkty
if ( ! function_exists( 'storefront_popular_products' ) ) {
    /**
	 * Display Popular Products
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @since  1.0.0
	 * @param array $args the product section args.
	 * @return void
	 */
    function storefront_popular_products( $args ) {
        $args = apply_filters(
            'storefront_popular_products_args', array(
                'limit'   => 4,
                'columns' => 4,
                'orderby' => 'rating',
                'order'   => 'desc',
                'title'   => __( 'Fan Favorites', 'storefront' ),
            )
        );

        $shortcode_content = storefront_do_shortcode(
            'products', apply_filters(
                'storefront_popular_products_shortcode_args', array(
                    'per_page' => intval( $args['limit'] ),
                    'columns'  => intval( $args['columns'] ),
                    'orderby'  => esc_attr( $args['orderby'] ),
                    'order'    => esc_attr( $args['order'] ),
                )
            )
        );

        /**
		 * Only display the section if the shortcode returns products
		 */
        if ( false !== strpos( $shortcode_content, 'product' ) ) {
            echo '<section class="storefront-product-section storefront-popular-products" aria-label="' . esc_attr__( 'Popular Products', 'storefront' ) . '">';

            do_action( 'storefront_homepage_before_popular_products' );

            echo '<div class="section-title-outer">';
            echo '<div class="section-title-wrapper">';
            echo '<span class="section-title-separator"><span class="section-title-line"></span></span>';
            echo '<h4 class="section-title">' . wp_kses_post( $args['title'] ) . '</h4>';
            echo '<span class="section-title-separator"><span class="section-title-line"></span></span>';
            echo '</div>';
            echo '</div>';

            do_action( 'storefront_homepage_after_popular_products_title' );

            echo $shortcode_content; // WPCS: XSS ok.

            do_action( 'storefront_homepage_after_popular_products' );

            echo '</section>';
        }
    }
}


//Promocje
if ( ! function_exists( 'storefront_on_sale_products' ) ) {
    /**
	 * Display On Sale Products
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @param array $args the product section args.
	 * @since  1.0.0
	 * @return void
	 */
    function storefront_on_sale_products( $args ) {
        $args = apply_filters(
            'storefront_on_sale_products_args', array(
                'limit'   => 4,
                'columns' => 4,
                'orderby' => 'date',
                'order'   => 'desc',
                'on_sale' => 'true',
                'title'   => __( 'On Sale', 'storefront' ),
            )
        );

        $shortcode_content = storefront_do_shortcode(
            'products', apply_filters(
                'storefront_on_sale_products_shortcode_args', array(
                    'per_page' => intval( $args['limit'] ),
                    'columns'  => intval( $args['columns'] ),
                    'orderby'  => esc_attr( $args['orderby'] ),
                    'order'    => esc_attr( $args['order'] ),
                    'on_sale'  => esc_attr( $args['on_sale'] ),
                )
            )
        );

        /**
		 * Only display the section if the shortcode returns products
		 */
        if ( false !== strpos( $shortcode_content, 'product' ) ) {
            echo '<section class="storefront-product-section storefront-on-sale-products" aria-label="' . esc_attr__( 'On Sale Products', 'storefront' ) . '">';

            do_action( 'storefront_homepage_before_on_sale_products' );

            echo '<div class="section-title-outer">';
            echo '<div class="section-title-wrapper">';
            echo '<span class="section-title-separator"><span class="section-title-line"></span></span>';
            echo '<h4 class="section-title">' . wp_kses_post( $args['title'] ) . '</h4>';
            echo '<span class="section-title-separator"><span class="section-title-line"></span></span>';
            echo '</div>';
            echo '</div>';

            do_action( 'storefront_homepage_after_on_sale_products_title' );

            echo $shortcode_content; // WPCS: XSS ok.

            do_action( 'storefront_homepage_after_on_sale_products' );

            echo '</section>';
        }
    }
}

//Bestsellery
if ( ! function_exists( 'storefront_best_selling_products' ) ) {
    /**
	 * Display Best Selling Products
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @since 2.0.0
	 * @param array $args the product section args.
	 * @return void
	 */
    function storefront_best_selling_products( $args ) {
        $args = apply_filters(
            'storefront_best_selling_products_args', array(
                'limit'   => 4,
                'columns' => 4,
                'orderby' => 'popularity',
                'order'   => 'desc',
                'title'   => esc_attr__( 'Best Sellers', 'storefront' ),
            )
        );

        $shortcode_content = storefront_do_shortcode(
            'products', apply_filters(
                'storefront_best_selling_products_shortcode_args', array(
                    'per_page' => intval( $args['limit'] ),
                    'columns'  => intval( $args['columns'] ),
                    'orderby'  => esc_attr( $args['orderby'] ),
                    'order'    => esc_attr( $args['order'] ),
                )
            )
        );

        /**
		 * Only display the section if the shortcode returns products
		 */
        if ( false !== strpos( $shortcode_content, 'product' ) ) {
            echo '<section class="storefront-product-section storefront-best-selling-products" aria-label="' . esc_attr__( 'Best Selling Products', 'storefront' ) . '">';

            do_action( 'storefront_homepage_before_best_selling_products' );

            echo '<div class="section-title-outer">';
            echo '<div class="section-title-wrapper">';
            echo '<span class="section-title-separator"><span class="section-title-line"></span></span>';
            echo '<h4 class="section-title">' . wp_kses_post( $args['title'] ) . '</h4>';
            echo '<span class="section-title-separator"><span class="section-title-line"></span></span>';
            echo '</div>';
            echo '</div>';

            do_action( 'storefront_homepage_after_best_selling_products_title' );

            echo $shortcode_content; // WPCS: XSS ok.

            do_action( 'storefront_homepage_after_best_selling_products' );

            echo '</section>';
        }
    }
}


//Nagłówek strony
if ( ! function_exists( 'storefront_homepage_header' ) ) {
    /**
	 * Display the page header without the featured image
	 *
	 * @since 1.0.0
	 */
    function storefront_homepage_header() {
        edit_post_link( __( 'Edit this section', 'storefront' ), '', '', '', 'button storefront-hero__button-edit' );
?>
<div class="entry-header-outer">
    <header class="entry-header">
        <?php
            the_title( '<h1 class="entry-title">', '</h1>' );
        ?>
    </header><!-- .entry-header --> 
</div>
<?php
    }
}

//Zmiana kolejności elementów headera
//Usuwanie elementów
function reorder_header_elements() {
    remove_action('storefront_header','storefront_secondary_navigation' , 30);
    remove_action('storefront_header','storefront_primary_navigation' , 50);
    remove_action('storefront_header','storefront_header_cart' , 60);
    remove_action('storefront_header','storefront_header_container' , 0);
    remove_action('storefront_header','storefront_header_container_close' , 41);
    add_action('storefront_header','storefront_header_cart' , 50);
    add_action('storefront_header','storefront_primary_navigation' , 60);
}
add_action( 'init', 'reorder_header_elements' );



//KOSZYK
if ( ! function_exists( 'storefront_cart_link' ) ) {
    function storefront_cart_link() {
?>
<a href="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" title="<?php _e( 'View your shopping cart', 'storefront' ); ?>">
   <span class="cart-icon"><i class="fa fa-shopping-cart"></i></span>
    <span class="count"><?php echo wp_kses_data( sprintf( '%d', WC()->cart->get_cart_contents_count() ) );?></span>
</a>
<?php
                                    }
}

//Menu mobilne
if ( ! function_exists( 'storefront_primary_navigation' ) ) {
    /**
	 * Display Primary Navigation
	 *
	 * @since  1.0.0
	 * @return void
	 */
    function storefront_primary_navigation() {
?>
<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_html_e( 'Primary Navigation', 'storefront' ); ?>">
    <button class="menu-toggle" aria-controls="site-navigation" aria-expanded="false"><span><i class="fas fa-bars"> </i><?php echo esc_attr( apply_filters( 'storefront_menu_toggle_text', __( 'Menu', 'storefront' ) ) ); ?></span></button>
    <?php
                                              wp_nav_menu(
                                                  array(
                                                      'theme_location'  => 'primary',
                                                      'container_class' => 'primary-navigation',
                                                  )
                                              );

                                              wp_nav_menu(
                                                  array(
                                                      'theme_location'  => 'handheld',
                                                      'container_class' => 'handheld-navigation',
                                                  )
                                              );
    ?>
</nav><!-- #site-navigation -->
<?php
                                             }
}



//Ograniczenie widgetów stopki


    function limit_footer_widgets() {
        if(!is_product() && !is_page('Start')){   
        remove_action('storefront_footer','storefront_footer_widgets' , 10);
        }       
        if(!is_page('Start')){   
            remove_action('storefront_before_content','storefront_header_widget_region' , 10);
        }
    }

add_action( 'get_header', 'limit_footer_widgets' );



//Własny pasek widgetów stopki
function footerbottom_widget_bar(){   
    register_sidebar( array(
        'name' => __( 'Footer Middle', 'storefront-child-theme-master' ),
        'id' => 'footermiddle',
        'description' => __( 'Widgets in this area will be shown on all posts and pages.', 'theme-slug' ),
        'before_widget' => '<aside id="%1$s" class="footermiddle-widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="footermiddle-widgettitle">',
        'after_title'   => '</h3>',
    ) );
    
    register_sidebar( array(
        'name' => __( 'Footer Bottom', 'storefront-child-theme-master' ),
        'id' => 'footerbottom',
        'description' => __( 'Widgets in this area will be shown on all posts and pages.', 'theme-slug' ),
        'before_widget' => '<aside id="%1$s" class="footerbottom-widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="footerbottom-widgettitle">',
        'after_title'   => '</h3>',
    ) );
}
add_action('widgets_init','footerbottom_widget_bar');

//Usuwanie domyślnych informacji ze stopki
function remove_copy_info(){
    remove_action('storefront_footer','storefront_credit',20);
}
add_action('init','remove_copy_info');

//Usuwanie Breadcrumb
function remove_breadcrumb(){
    remove_action('storefront_before_content','woocommerce_breadcrumb',10);
}
add_action('init','remove_breadcrumb');




//Kolejność elementów na stronie produktu
function single_product_right_column(){
    remove_action('woocommerce_single_product_summary','woocommerce_template_single_price',10);
    remove_action('woocommerce_single_product_summary','woocommerce_template_single_excerpt',20);
    add_action('woocommerce_single_product_summary','woocommerce_template_single_excerpt');
    add_action('woocommerce_single_product_summary','woocommerce_template_single_price');
}
add_action('init','single_product_right_column');

//Powiązane produkty na stronie produku
function relocate_related_products(){
    remove_action('woocommerce_after_single_product_summary','woocommerce_output_related_products',20);
    add_action('woocommerce_after_single_product','woocommerce_output_related_products');
}
add_action('init','relocate_related_products');

function woocommerce_output_related_products(){
    woocommerce_related_products(4,2);
}


//Usuwanie Breadcrumb
function remove_category_sorting(){
    remove_action('woocommerce_after_shop_loop','woocommerce_result_count',20);
    remove_action('woocommerce_after_shop_loop','woocommerce_catalog_ordering',10);
}
add_action('init','remove_category_sorting');

//Baner - usuwanie kolumny z widgetu
if ( ! function_exists( 'storefront_header_widget_region' ) ) {

    function storefront_header_widget_region() {
        if ( is_active_sidebar( 'header-1' ) ) {
?>
<div class="header-widget-region" role="complementary">
        <?php dynamic_sidebar( 'header-1' ); ?>
</div>
<?php
                                               }
    }
}

/*Wycinanie miniaturek*/
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 215, 215, false);

add_filter( 'woocommerce_get_image_size_thumbnail', function( $size ) {
    return array(
        'width'  => 215,
        'height' => 215,
        'crop'   => 0,
    );
} );

add_filter( 'woocommerce_get_image_size_shop_thumbnail', function( $size ) {
    return array(
        'width'  => 215,
        'height' => 215,
        'crop'   => 0,
    );
} );