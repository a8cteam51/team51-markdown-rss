import { createHooks } from '@wordpress/hooks';
import domReady from '@wordpress/dom-ready';

window.wpcomsp_markdown = window.wpcomsp_markdown || {};
window.wpcomsp_markdown.hooks = createHooks();

domReady( () => {
	window.wpcomsp_markdown.hooks.doAction( 'editor.ready' );
} );
