/**
 * Toggle tabs.
 */
import domReady from '../dom-ready';

domReady(async () => {
	const tabs = document.querySelectorAll('.tabs');

	if (!tabs.length) {
		return;
	}

	// eslint-disable-next-line
	const { Tabs } = await import('./tabs');

	new Tabs({
		tabs,
		tabButtonSelector: 'tab-btn',
		tabContentSelector: 'tab-content',
		tabButtonTargetAttribute: 'data-target',
		tabContentTargetAttribute: 'data-tab',
	}).init();
});
