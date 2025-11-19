export class OnSubmit {
	constructor({
		requiresConfirmation,
	}) {
		this.forms = requiresConfirmation;
	}

	init = async () => {
		const { Modal } = await import('../modal/modal');

		this.forms.forEach((form) => {
			form.addEventListener('submit', (event) => {
				event.preventDefault();

				const confirmAction = () => form.submit();
				const message = this.getMessage(form);

				const modal = new Modal({ message, confirmAction });
				modal.init();
				modal.show();
			});

		});
	};

	getEvent = (form) => {
		return form.getAttribute('data-event') ?? 'null';
	};

	getMessage = (form) => {
		const event = this.getEvent(form);
		let message;

		switch (event) {
			case 'send-password-reset-link':
				message = 'Password reset link will be sent to the user\'s email address.';
				break;
			case 'activate':
				message = 'User will be activated.';
				break;
			case 'deactivate':
				message = 'User will be deactivated.';
				break;
			case 'delete':
				message = 'User will be deleted permanently. This action is irreversible.';
				break;
			default:
				message = 'Are you sure you want to do this?';
				break;
		}

		return message;
	};
}
