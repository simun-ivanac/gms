/**
 * Remove notices on click.
 */
import domReady from '../dom-ready';

domReady(async() => {
	const notices = document.querySelectorAll('.notice');

	if (!notices.length) {
		return;
	}

	const { Notice } = await import('./notice');

	new Notice({
		notices,
		removeButtonSelector: 'notice-close',
	}).init();
});
