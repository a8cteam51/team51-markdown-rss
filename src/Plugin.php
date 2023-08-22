<?php

namespace WPCOMSpecialProjects\MarkdownRSS;

use League\HTMLToMarkdown\HtmlConverter;

defined( 'ABSPATH' ) || exit;

/**
 * Main plugin class.
 *
 * @since   1.0.0
 * @version 1.0.0
 */
class Plugin {
	// region MAGIC METHODS

	/**
	 * Plugin constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	protected function __construct() {
		/* Empty on purpose. */
	}

	/**
	 * Prevent cloning.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  void
	 */
	private function __clone() {
		/* Empty on purpose. */
	}

	/**
	 * Prevent unserializing.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  void
	 */
	public function __wakeup() {
		/* Empty on purpose. */
	}

	// endregion

	// region METHODS

	/**
	 * Returns the singleton instance of the plugin.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  Plugin
	 */
	public static function get_instance(): self {
		static $instance = null;

		if ( null === $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Initializes the plugin components.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  void
	 */
	public function initialize(): void {
		// Add the source:markdown element to the RSS feed.
		add_action( 'rss2_item', array( $this, 'add_source_markdown_element' ) );
	}

	// endregion

	// region HOOKS

	/**
	 * Adds the <source:markdown> element to the RSS feed.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  void
	 */
	public function add_source_markdown_element(): void {
		$content = get_the_content_feed();

		$converter = new HtmlConverter(
			array(
				'strip_tags' => true
			)
		);

		printf( '<source:markdown><![CDATA[%s]]></source:markdown>', $converter->convert( $content ) );
	}

	// endregion
}
