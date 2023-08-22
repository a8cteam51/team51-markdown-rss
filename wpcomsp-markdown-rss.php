<?php
/**
 * The Markdown RSS bootstrap file.
 *
 * @since       1.0.0
 * @version     1.0.0
 * @author      WordPress.com Special Projects
 * @license     GPL-3.0-or-later
 *
 * @noinspection    ALL
 *
 * @wordpress-plugin
 * Plugin Name:             Markdown RSS
 * Plugin URI:              https://wpspecialprojects.wordpress.com
 * Description:
 * Version:                 1.0.0
 * Requires at least:       6.2
 * Tested up to:            6.2
 * Requires PHP:            8.0
 * Author:                  WordPress.com Special Projects
 * Author URI:              https://wpspecialprojects.wordpress.com
 * License:                 GPL v3 or later
 * License URI:             https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:             wpcomsp-markdown-rss
 * Domain Path:             /languages
 * WC requires at least:    7.4
 * WC tested up to:         7.4
 **/

defined( 'ABSPATH' ) || exit;

// Define plugin constants.
function_exists( 'get_plugin_data' ) || require_once ABSPATH . 'wp-admin/includes/plugin.php';
define( 'WPCOMSP_MARKDOWN_METADATA', get_plugin_data( __FILE__, false, false ) );

define( 'WPCOMSP_MARKDOWN_BASENAME', plugin_basename( __FILE__ ) );
define( 'WPCOMSP_MARKDOWN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPCOMSP_MARKDOWN_URL', plugin_dir_url( __FILE__ ) );

// Load plugin translations so they are available even for the error admin notices.
add_action(
	'init',
	static function () {
		load_plugin_textdomain(
			WPCOMSP_MARKDOWN_METADATA['TextDomain'],
			false,
			dirname( WPCOMSP_MARKDOWN_BASENAME ) . WPCOMSP_MARKDOWN_METADATA['DomainPath']
		);
	}
);

// Load the autoloader.
if ( ! is_file( WPCOMSP_MARKDOWN_PATH . '/vendor/autoload.php' ) ) {
	add_action(
		'admin_notices',
		static function () {
			$message      = __( 'It seems like <strong>Markdown RSS</strong> is corrupted. Please reinstall!', 'wpcomsp-team51-markdown-rss' );
			$html_message = wp_sprintf( '<div class="error notice wpcomsp-team51-markdown-rss-error">%s</div>', wpautop( $message ) );
			echo wp_kses_post( $html_message );
		}
	);
	return;
}
require_once WPCOMSP_MARKDOWN_PATH . '/vendor/autoload.php';

// Initialize the plugin if system requirements check out.
$wpcomsp_markdown_requirements = validate_plugin_requirements( WPCOMSP_MARKDOWN_BASENAME );
define( 'WPCOMSP_MARKDOWN_REQUIREMENTS', $wpcomsp_markdown_requirements );

if ( $wpcomsp_markdown_requirements instanceof WP_Error ) {
	add_action(
		'admin_notices',
		static function () use ( $wpcomsp_markdown_requirements ) {
			$html_message = wp_sprintf( '<div class="error notice wpcomsp-team51-markdown-rss-error">%s</div>', $wpcomsp_markdown_requirements->get_error_message() );
			echo wp_kses_post( $html_message );
		}
	);
} else {
	require_once WPCOMSP_MARKDOWN_PATH . 'functions.php';
	add_action( 'plugins_loaded', array( wpcomsp_markdown_get_plugin_instance(), 'initialize' ) );
}
