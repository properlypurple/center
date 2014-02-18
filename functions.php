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
	
//Footer Widget
	add_theme_support( 'genesis-footer-widgets', 2);

// Adding Color Style Options
	add_theme_support( 'genesis-style-selector', array(
		'center-grey' => __( 'Grey', 'center' ),
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
	
	// Remove Unused User Settings
	add_filter( 'user_contactmethods', 'center_contactmethods' );
	add_action( 'admin_init', 'center_remove_user_settings' );

	// Editor Styles
	add_editor_style( 'editor-style.css' );
		
	// Setup Theme Settings
	include_once( CHILD_DIR . '/lib/functions/child-theme-settings.php' );

	// Reposition Genesis Metaboxes
	remove_action( 'admin_menu', 'genesis_add_inpost_seo_box' );
	add_action( 'admin_menu', 'center_add_inpost_seo_box' );


	// Remove Genesis Theme Settings Metaboxes
	add_action( 'genesis_theme_settings_metaboxes', 'center_remove_genesis_metaboxes' );

	// Don't update theme
	add_filter( 'http_request_args', 'center_dont_update_theme', 5, 2 );

	// ** Frontend **		
	
	// Remove Edit link
	add_filter( 'genesis_edit_post_link', '__return_false' );
	
	// Responsive Meta Tag
	add_action( 'genesis_meta', 'center_viewport_meta_tag' );
	
	// Footer
	remove_action( 'genesis_footer', 'genesis_do_footer' );
	add_action( 'genesis_footer', 'center_footer' );

} //end theme setup

// ** Backend Functions ** //

/**
 * Customize Contact Methods
 * @since 1.0.0
 *
 * @author Bill Erickson
 * @link http://sillybean.net/2010/01/creating-a-user-directory-part-1-changing-user-contact-fields/
 *
 * @param array $contactmethods
 * @return array
 */
function center_contactmethods( $contactmethods ) {
	unset( $contactmethods['aim'] );
	unset( $contactmethods['yim'] );
	unset( $contactmethods['jabber'] );
	
	return $contactmethods;
}

/**
 * Remove Use Theme Settings
 * 
 */
function center_remove_user_settings() {
	remove_action( 'show_user_profile', 'genesis_user_options_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_options_fields' );
	remove_action( 'show_user_profile', 'genesis_user_archive_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_archive_fields' );
	remove_action( 'show_user_profile', 'genesis_user_seo_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_seo_fields' );
	remove_action( 'show_user_profile', 'genesis_user_layout_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_layout_fields' );
}

/**
 * Register a new meta box to the post / page edit screen, so that the user can
 * set SEO options on a per-post or per-page basis.
 *
 * @category Genesis
 * @package Admin
 * @subpackage Inpost-Metaboxes
 *
 * @since 0.1.3
 *
 * @see genesis_inpost_seo_box() Generates the content in the meta box
 */
function center_add_inpost_seo_box() {

	if ( genesis_detect_seo_plugins() )
		return;
		
	foreach ( (array) get_post_types( array( 'public' => true ) ) as $type ) {
		if ( post_type_supports( $type, 'genesis-seo' ) )
			add_meta_box( 'genesis_inpost_seo_box', __( 'Theme SEO Settings', 'genesis' ), 'genesis_inpost_seo_box', $type, 'normal', 'default' );
	}

}



/**
 * Remove Genesis Theme Settings Metaboxes
 *
 * @since 1.0.0
 * @param string $_genesis_theme_settings_pagehook
 */
function center_remove_genesis_metaboxes( $_genesis_theme_settings_pagehook ) {
	//remove_meta_box( 'genesis-theme-settings-feeds',      $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-header',     $_genesis_theme_settings_pagehook, 'main' );
	remove_meta_box( 'genesis-theme-settings-nav',        $_genesis_theme_settings_pagehook, 'main' );
	// remove_meta_box( 'genesis-theme-settings-layout',    $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-breadcrumb', $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-comments',   $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-posts',      $_genesis_theme_settings_pagehook, 'main' );
	remove_meta_box( 'genesis-theme-settings-blogpage',   $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-scripts',    $_genesis_theme_settings_pagehook, 'main' );
}

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
 * Viewport Meta Tag for Mobile Browsers
 *
 * @author Bill Erickson
 * @link http://www.billerickson.net/code/responsive-meta-tag
 */
function center_viewport_meta_tag() {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
}

/**
 * Footer 
 *
 */
function center_footer() {
	echo wpautop( genesis_get_option( 'footer', 'child-settings' ) );
}
