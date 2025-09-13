<?php
/**
 * Islands Book Trust theme functions
 */

if ( ! defined( 'IBT_VERSION' ) ) {
    define( 'IBT_VERSION', wp_get_theme()->get( 'Version' ) );
}

add_action( 'after_setup_theme', function() {
    // Core supports
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'editor-styles' );

    // Allow wide & full alignment in the block editor
    add_theme_support( 'align-wide' );

    // Add support for comments
    add_theme_support( 'comments' );

    // WooCommerce compatibility
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
} );

// ******* REMOVE AFTER DEV *******
// Cache-buster for ibt.css during development.
// filemtime() updates the version whenever the file changes.
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'islands-book-trust',
        get_stylesheet_directory_uri() . '/assets/css/ibt.css',
        array(),
        filemtime( get_stylesheet_directory() . '/assets/css/ibt.css' )
    );
}, 20 );

// Replace the fallback copyright year dynamically in footer.
add_filter( 'render_block', function( $block_content, $block ) {
    if ( $block['blockName'] === 'core/paragraph' && strpos( $block_content, 'bookshop-year' ) !== false ) {
        $year = date( 'Y' );
        $block_content = preg_replace(
            '/<span class="bookshop-year">.*?<\/span>/',
            '<span class="bookshop-year">' . esc_html( $year ) . '</span>',
            $block_content
        );
    }
    return $block_content;
}, 10, 2 );
