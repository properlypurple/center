<?php
/**
 * Functions
 * /


/ * 
 * This setup function attaches all of the site-wide functions 
 * to the correct hooks and filters. All the functions themselves
 * are defined below this setup function.
 */

add_action('genesis_setup','child_theme_setup', 15);
function child_theme_setup() {
	
	define( 'CHILD_THEME_VERSION', filemtime( get_stylesheet_directory() . '/style.css' ) );
	define( 'CHILD_THEME_NAME', 'center' );

	// ** Backend **	
	
	// Image Sizes
	// add_image_size( 'center_featured', 400, 100, true );
	
	//html5
	add_theme_support( 'html5');

	// Structural Wraps
	add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'inner', 'footer-widgets', 'footer' ) );

	// Menus
	add_theme_support( 'genesis-menus', array( 'primary' => 'Primary Navigation Menu' ) );
	
	
	// Add support for custom header
	add_theme_support( 'custom-header', array(
		'width'           => 600,
		'height'          => 128,
		'header-selector' => '.site-title a',
		'header-text'     => false,
		'flex-height'     => true,
	) );
	
	// Add support for custom background
	add_theme_support( 'custom-background' );
	
	//Footer Widget
	add_theme_support( 'genesis-footer-widgets', 2);

	// Adding Color Style Options
	add_theme_support( 'genesis-style-selector', array(
		'center-dark' => __( 'Dark', 'center' )
	) );

	// Sidebars
	unregister_sidebar( 'sidebar-alt' );
	unregister_sidebar( 'sidebar' );
	genesis_register_sidebar( array(
		'id'			=> 'home-top',
		'name'			=> __( 'Home Top', 'CHILD_THEME_NAME' ),
		'description'	=> __( 'This is the home top widget area.', 'CHILD_THEME_NAME' ),
	) );


	// Remove Unused Page Layouts
	genesis_unregister_layout( 'content-sidebar-sidebar' );
	genesis_unregister_layout( 'sidebar-sidebar-content' );
	genesis_unregister_layout( 'sidebar-content-sidebar' );
	genesis_unregister_layout( 'sidebar-content' );
	genesis_unregister_layout( 'content-sidebar' );
	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
	

	// Editor Styles
	add_editor_style( 'editor-style.css' );
		
	// Setup Theme Settings
	include_once( CHILD_DIR . '/lib/functions/child-theme-settings.php' );

	// Don't update theme
	add_filter( 'http_request_args', 'center_dont_update_theme', 5, 2 );

	// ** Frontend **
	
	// Footer
	remove_action( 'genesis_footer', 'genesis_do_footer' );
	add_action( 'genesis_footer', 'center_footer' );

} //end theme setup

// ** Backend Functions ** //

/**
 * Don't Update Theme
 * @since 1.0.0
 *
 * If there is a theme in the repo with the same name, 
 * this prevents WP from prompting an update.
 *
 * @author Mark Jaquith
 * @link http://markjaquith.wordpress.com/2009/12/14/excluding-your-plugin-or-theme-from-update-checks/
 *
 * @param array $r, request arguments
 * @param string $url, request url
 * @return array request arguments
 */

function center_dont_update_theme( $r, $url ) {
	if ( 0 !== strpos( $url, 'http://api.wordpress.org/themes/update-check' ) )
		return $r; // Not a theme update request. Bail immediately.
	$themes = unserialize( $r['body']['themes'] );
	unset( $themes[ get_option( 'template' ) ] );
	unset( $themes[ get_option( 'stylesheet' ) ] );
	$r['body']['themes'] = serialize( $themes );
	return $r;
}

// ** Frontend Functions ** //

/**
 * Footer
 */
function center_footer() {
	echo wpautop( genesis_get_option( 'footer', 'child-settings' ) );
}
