export class Tabs {
	constructor({
		tabs,
		tabButtonSelector,
		tabContentSelector,
		tabButtonTargetAttribute,
		tabContentTargetAttribute,
	}) {
		this.tabs = tabs;
		this.tabButtonSelector = tabButtonSelector;
		this.tabContentSelector = tabContentSelector;
		this.tabButtonTargetAttribute = tabButtonTargetAttribute;
		this.tabContentTargetAttribute = tabContentTargetAttribute;
		this.ACTIVE_BUTTON_CLASS = 'active';
		this.ACTIVE_CONTENT_CLASS = 'active';
	}

	init = () => {
		this.tabs.forEach((tab) => {
			const tabButtons = tab.querySelectorAll(`.${this.tabButtonSelector}`);
			const tabContents = tab.parentNode.querySelectorAll(`.${this.tabContentSelector}`);

			tabButtons.forEach((button) => {
				button.addEventListener('click', (event) => {
					event.preventDefault();
					const target = button.getAttribute(`${this.tabButtonTargetAttribute}`);

					tabButtons.forEach((btn) => btn.classList.remove(this.ACTIVE_BUTTON_CLASS));
					button.classList.add(this.ACTIVE_BUTTON_CLASS);

					tabContents.forEach((content) => {
						if (content.getAttribute(`${this.tabContentTargetAttribute}`) === target) {
							content.classList.add(this.ACTIVE_CONTENT_CLASS);
						} else {
							content.classList.remove(this.ACTIVE_CONTENT_CLASS);
						}
					});
				});
			});
		});
	};
}
