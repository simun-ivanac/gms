export class Notice {
	constructor({
		notices,
		removeButtonSelector,
	}) {
		this.notices = notices;
		this.removeButtonSelector = removeButtonSelector;
	}

	// eslint-disable-next-line
	init = () => {
		this.notices.forEach((notice) => {
			const removeBtn = notice.querySelector(`.${this.removeButtonSelector}`);

			removeBtn?.addEventListener('click', () => {
				notice.remove();
			});
		});
	};
}
