<?php

/**
 *
 * File documentation
 *
 * @author: Ruben Catshoek <rcatshoek@student.scalda.nl>
 * @since: 16-10-2017
 * @version 0.1 16-10-2017
 */

class Interactievemap_AdminController {
	/**
	 * This function will prepare all Admin functionality for the plugin
	 */
	static function prepare() {
		// Check that we are in the admin area
		if ( is_admin() ) :
			// Add the sidebar Menu structure
			add_action( 'admin_menu', array( 'Interactievemap_AdminController', 'addMenus' ) );

			add_action( 'admin_enqueue_scripts', array( 'Interactievemap_AdminController', 'load_custom_style' ) );

			add_action('admin_enqueue_scripts', array('Interactievemap_AdminController', 'load_custom_js'));
		endif;
	}

	/**
	 * Add the Menu structure to the Admin sidebar
	 */
	static function addMenus() {
		add_menu_page(
		//string $page_title The text to be displayed in the title tags
		//of the page when the menu is selected
			__( 'Interactieve map Admin', 'interactieve-map' ),
		// string $menu_title The text to be used for the menu
			__( 'Interactieve map', 'interactieve-map' ),
		//string $capability The capability required for this menu to be displayed to the user.
			'manage_options',
		//string $menu_slug The slug name to refer to this menu by (should be unique for this menu)
			'interactieve-map-admin',
		//callback $function The function to be called to output the content for this page.
			array( 'Interactievemap_AdminController', 'adminMenuPage' ),

		//string $icon_url The url to the icon to be used for this menu.
		//* Pass a base64-encoded SVG using a data URI, which will be colored to match the color scheme.
		//This should begin with 'data:image/svg+xml;base64,'.
		//* Pass the name of a Dashicons helper class to use a font icon, e.g. 'dashicons-chart-pie'.
		//* Pass 'none' to leave div.wp-menu-image empty so an icon can be added via CSS.
			'dashicons-location-alt'
		// int $position The position in the menu order this one should appear
		);

		add_submenu_page(
		// string $parent_slug The slug name for the parent menu
		// (or the file name of a standard WordPress admin page)
			'interactieve-map-admin',
			// string $page_title The text to be displayed in the title tags of the page when the menu is selected
			__( 'checkpoint overzicht', 'interactieve-map' ),
			// string $menu_title The text to be used for the menu
			__( 'Checkpoint overzicht', 'interactieve-map' ),
			// string $capability The capability required for this menu to be displayed to the user .
			'manage_options',
			// string $menu_slug The slug name to refer to this menu by (should be unique for this menu)
			'im_admin_checkpoint_overview',
			// callback $function The function to be called to output the content for this page .
			array( 'Interactievemap_AdminController', 'adminSubMenuIMOverview' ) );

		add_submenu_page(
		// string $parent_slug The slug name for the parent menu
		// (or the file name of a standard WordPress admin page)
			'interactieve-map-admin',
			// string $page_title The text to be displayed in the title tags of the page when the menu is selected
			__( 'checkpoint toevoegen', 'interactieve-map' ),
			// string $menu_title The text to be used for the menu
			__( 'Checkpoint toevoegen', 'interactieve-map' ),
			// string $capability The capability required for this menu to be displayed to the user .
			'manage_options',
			// string $menu_slug The slug name to refer to this menu by (should be unique for this menu)
			'im_admin_checkpoint_add',
			// callback $function The function to be called to output the content for this page .
			array( 'Interactievemap_AdminController', 'adminSubMenuIMAdd' ) );
	}

	/**
	 * The main menu page
	 */
	static function adminMenuPage() {
		// Include the view for this menu page.
		include INTERACTIEVE_MAP_PLUGIN_ADMIN_VIEWS_DIR . '/admin_main.php';
	}

	/**
	 * The submenu page to add a checkpoint
	 */
	static function adminSubMenuIMAdd() {
		// include the view for this submenu page.
		include INTERACTIEVE_MAP_PLUGIN_ADMIN_VIEWS_DIR . '/im_admin_checkpoint_add.php';
	}

//	/**
//	 * The submenu page to edit a checkpoint
//	 */
//	static function adminSubMenuIMEdit() {
//		// include the view for this submenu page.
//		include INTERACTIEVE_MAP_PLUGIN_ADMIN_VIEWS_DIR . '/im_admin_checkpoint_edit.php';
//	}

	/**
	 * The submenu page to view checkpoints
	 */
	static function adminSubMenuIMOverview() {
		// include the view for this submenu page.
		include INTERACTIEVE_MAP_PLUGIN_ADMIN_VIEWS_DIR . '/im_admin_checkpoint_overview.php';
	}

	function load_custom_style( $hook ) {
		//If $hook isn't one of these pages it will return and stop enqueuing and registering
		var_dump($hook);
		if ($hook != 'interactieve-map_page_im_admin_checkpoint_overview' &&
			$hook != 'interactieve-map_page_im_admin_checkpoint_add' &&
			$hook != 'interactieve-map_page_im_admin_checkpoint_edit') {
			return;
		}
		// Enqueue the stylsheet into Wordpress
		wp_enqueue_style( 'custom_wp_admin_css', plugins_url( 'interactieve-map/css/stylesheet.css', dirname( FILE ) ) );
	}

	function load_custom_js()
	{
		wp_enqueue_script('custom-js', plugins_url('interactieve-map/includes/jquery.js', dirname(FILE)));
	}
}
