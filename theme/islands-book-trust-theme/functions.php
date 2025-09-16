<?php
/**
 * Islands Book Trust theme functions
 * Standalone block theme.
 */

if ( ! defined( 'IBT_VERSION' ) ) {
	define( 'IBT_VERSION', wp_get_theme()->get( 'Version' ) );
}

/**
 * [IBT-A] Theme setup: core supports + editor stylesheet (TT5 approach)
 */
add_action( 'after_setup_theme', function() {
	// Core supports
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );

	// Allow wide & full alignment in the block editor
	add_theme_support( 'align-wide' );

	// Add support for comments (kept for parity with your previous file)
	add_theme_support( 'comments' );

	// WooCommerce compatibility
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Load editor.css inside the block editor (TT5 approach)
	add_editor_style( 'assets/css/editor.css' );
} );

/**
 * [IBT-B] Editor assets (safety net): explicitly enqueue editor.css in the block editor
 * This ensures the CSS is present in the Gutenberg iframe even if add_editor_style()
 * is skipped by the environment.
 */
add_action( 'enqueue_block_editor_assets', function () {
	$rel  = 'assets/css/editor.css';
	$path = get_stylesheet_directory() . '/' . $rel;
	$ver  = file_exists( $path ) ? filemtime( $path ) : IBT_VERSION;

	wp_enqueue_style(
		'ibt-editor',
		get_stylesheet_directory_uri() . '/' . $rel,
		array(),
		$ver
	);
}, 20 );

/**
 * [IBT-C] Front-end assets (ibt.css)
 * ******* REMOVE AFTER DEV *******
 * Cache-buster for ibt.css during development (filemtime() auto-bumps version).
 */
add_action( 'wp_enqueue_scripts', function() {
	$rel  = 'assets/css/ibt.css';
	$path = get_stylesheet_directory() . '/' . $rel;
	$ver  = file_exists( $path ) ? filemtime( $path ) : IBT_VERSION;

	wp_enqueue_style(
		'islands-book-trust',
		get_stylesheet_directory_uri() . '/' . $rel,
		array(),
		$ver
	);
}, 20 );

/**
 * [IBT-D] Footer helper: replace fallback copyright year dynamically
 * Looks for <span class="bookshop-year">...</span> inside core/paragraph blocks.
 */
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

/**
 * [IBT-E] Custom Button styles (show up in the Button block “Styles” panel)
 */
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

/**
 * -----------------------------------------------------------------------------
 * DEBUG NOTE (optional):
 * If you still need a visible probe in the editor, you can temporarily uncomment
 * the block below to draw a lime outline around the editor canvas, then remove.
 * -----------------------------------------------------------------------------
 */
// add_action( 'enqueue_block_editor_assets', function () {
// 	wp_register_style( 'ibt-editor-probe', false, array(), IBT_VERSION );
// 	wp_enqueue_style( 'ibt-editor-probe' );
// 	wp_add_inline_style( 'ibt-editor-probe', '.editor-styles-wrapper{outline:3px solid lime !important;}' );
// }, 5 );
