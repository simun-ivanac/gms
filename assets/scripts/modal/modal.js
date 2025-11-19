export class Modal {
	constructor({
		message,
		confirmAction,
	}) {
		this.modal = document.getElementById('modal');
		this.closeButtonSelector = 'modal-close';
		this.messageSelector = 'confirmation-message';
		this.confirmButtonDataSelector = 'confirm';
		this.cancelButtonDataSelector = 'cancel';
		this.messageText = message;
		this.confirmAction = confirmAction;
		this.message;
	}

	init = () => {
		const closeBtn = this.modal.querySelector(`.${this.closeButtonSelector}`);
		const confirmBtn = this.modal.querySelector(`.modal-btn[data-event="${this.confirmButtonDataSelector}"]`);
		const cancelBtn = this.modal.querySelector(`.modal-btn[data-event="${this.cancelButtonDataSelector}"]`);
		this.message = this.modal.querySelector(`.${this.messageSelector}`);

		closeBtn.addEventListener('click', () => this.hide());
		cancelBtn.addEventListener('click', () => this.hide());
		confirmBtn.addEventListener('click', () => this.confirmAction());
	};

	show = () => {
		document.body.classList.add('no-scroll');
		this.message.textContent = this.messageText;
		this.modal.classList.add('is-open');
	};

	hide = () => {
		this.modal.classList.remove('is-open');
		this.message.textContent = '';
		document.body.classList.remove('no-scroll');
	};
}
