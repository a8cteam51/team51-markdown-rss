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

		// Add the source:markdown element to the comments RSS feed.
		add_action( 'commentrss2_item', array( $this, 'add_comment_source_markdown_element' ) );

		// Add source namespace to the RSS feed.
		add_action( 'rss2_ns', array( $this, 'add_source_namespace' ) );

		// Add source namespace to the comments RSS feed.
		add_action( 'rss2_comments_ns', array( $this, 'add_source_namespace' ) );
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
		$post = get_post();

		if ( ! $post ) {
			return;
		}

		if ( ! empty( $post->post_content ) ) {
			$content = apply_filters( 'the_content', $post->post_content ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		} else {
			return;
		}

		$content   = str_replace( ']]>', ']]&gt;', $content );
		$converter = new HtmlConverter( array( 'strip_tags' => true ) );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Content is wrapped in CDATA with ]]> escaped.
		echo "\t\t<source:markdown><![CDATA[" . $converter->convert( $content ) . "]]></source:markdown>\n";
	}

	/**
	 * Adds the <source:markdown> element to the comments RSS feed.
	 *
	 * @since   1.0.1
	 * @version 1.0.1
	 *
	 * @return  void
	 */
	public function add_comment_source_markdown_element(): void {
		$comment = get_comment();

		if ( ! $comment || empty( $comment->comment_content ) ) {
			return;
		}

		$content = apply_filters( 'comment_text', $comment->comment_content, $comment, array() ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

		$content   = str_replace( ']]>', ']]&gt;', $content );
		$converter = new HtmlConverter( array( 'strip_tags' => true ) );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Content is wrapped in CDATA with ]]> escaped.
		echo "\t\t<source:markdown><![CDATA[" . $converter->convert( $content ) . "]]></source:markdown>\n";
	}

	/**
	 * Output the XML namespace declaration to the feed
	 *
	 * @since   1.0.1
	 * @version 1.0.1
	 *
	 * @return  void
	 */
	public function add_source_namespace(): void {
		echo 'xmlns:source="https://source.scripting.com/"';
	}

	// endregion
}
