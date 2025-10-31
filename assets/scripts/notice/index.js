/**
 * Remove notices on click.
 */
import domReady from '../dom-ready';

domReady(async() => {
	const notices = document.querySelectorAll('.notice');

	if (!notices.length) {
		return;
	}

	// eslint-disable-next-line
	const { Notice } = await import('./notice');

	new Notice({
		notices,
		removeButtonSelector: 'notice-close',
	}).init();
});
