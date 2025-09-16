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

    // NOTE: We intentionally do NOT call add_editor_style() here,
    // to avoid "added to iframe incorrectly" console warnings.
} );

// Inject editor.css into the editor iframe the modern way (no warnings).
add_filter( 'block_editor_settings_all', function( $settings ) {
    $rel  = 'assets/css/editor.css';
    $path = get_stylesheet_directory() . '/' . $rel;
    $href = get_stylesheet_directory_uri() . '/' . $rel . '?v=' . ( file_exists( $path ) ? filemtime( $path ) : IBT_VERSION );

    // Append as a proper iframe style entry.
    $settings['styles'][] = array( 'href' => $href );
    return $settings;
}, 10 );

// Safety: remove any leftover editor enqueues that might mirror into the iframe.
add_action( 'enqueue_block_editor_assets', function() {
    foreach ( array( 'ibt-editor', 'ibt-editor-css' ) as $handle ) {
        wp_dequeue_style( $handle );
        wp_deregister_style( $handle );
    }
}, 100 );

// ******* REMOVE AFTER DEV *******
// Cache-buster for ibt.css during development.
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
