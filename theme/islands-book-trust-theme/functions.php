<?php
/**
 * Islands Book Trust theme functions
 * Standalone block theme.
 */

if ( ! defined( 'IBT_VERSION' ) ) {
	define( 'IBT_VERSION', wp_get_theme()->get( 'Version' ) );
}

/**
 * [IBT-A] Theme setup: core supports
 */
add_action( 'after_setup_theme', function() {
	// Core supports
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' ); // keep wrapper/class in editor

	// Allow wide & full alignment in the block editor
	add_theme_support( 'align-wide' );

	// Add support for comments (kept from your file)
	add_theme_support( 'comments' );

	// Load editor.css & ibt.css in block editor
	add_editor_style( [ 'assets/css/editor.css', 'assets/css/ibt.css' ] );



	// WooCommerce compatibility
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Add html5 markup to all site WP output
	add_theme_support('html5', [
		'comment-form',
		'comment-list',
		'search-form',
		'gallery',
		'caption',
		'style',
		'script'
	]);

} );

// [IBT-B] - Removed

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
 * [IBT-E] Custom Button styles (Button block “Styles” panel)
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
