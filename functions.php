<?php

defined( 'ABSPATH' ) || exit;

use WPCOMSpecialProjects\MarkdownRSS\Plugin;

/**
 * Returns the plugin's main class instance.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  Plugin
 */
function wpcomsp_markdown_get_plugin_instance(): Plugin {
	return Plugin::get_instance();
}
