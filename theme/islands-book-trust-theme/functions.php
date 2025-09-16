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

    // Load editor.css inside the editor iframe (with cache-busting)
    $editor_rel  = 'assets/css/editor.css';
    $editor_path = get_stylesheet_directory() . '/' . $editor_rel;
    $editor_ver  = file_exists( $editor_path ) ? filemtime( $editor_path ) : IBT_VERSION;
    add_editor_style( $editor_rel . '?v=' . $editor_ver );
} );

// ******* REMOVE AFTER DEV *******
// Cache-buster for ibt.css during development.
// filemtime() updates the version whenever the file changes.
add_action( 'wp_enqueue_scripts', function() {
    $css_rel  = 'assets/css/ibt.css';
    $css_path = get_stylesheet_directory() . '/' . $css_rel;
    $css_ver  = file_exists( $css_path ) ? filemtime( $css_path ) : IBT_VERSION;

    wp_enqueue_style(
        'islands-book-trust',
        get_stylesheet_directory_uri() . '/' . $css_rel,
        array(),
        $css_ver
    );
}, 20 );

// Replace the fallback copyright year dynamically in footer.
add_filter( 'render_block', function( $block_content, $block ) {
    if ( isset( $block['blockName'] )
        && $block['blockName'] === 'core/paragraph'
        && strpos( $block_content, 'bookshop-year' ) !== false
    ) {
        $year = date( 'Y' );
        $block_content = preg_replace(
            '/<span class="bookshop-year">.*?<\/span>/',
            '<span class="bookshop-year">' . esc_html( $year ) . '</span>',
            $block_content
        );
    }
    return $block_content;
}, 10, 2 );

// Add Accent button styles (visible in block editor "Styles" for Button)
add_action( 'init', function () {
    register_block_style( 'core/button', array(
        'name'  => 'solid-accent',
        'label' => __( 'Solid Accent', 'ibt' ),
    ) );
    register_block_style( 'core/button', array(
        'name'  => 'outline-accent',
        'label' => __( 'Outline Accent', 'ibt' ),
    ) );
} );
