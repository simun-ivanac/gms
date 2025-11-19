/**
 * Attach form events.
 */
import domReady from '../dom-ready';

domReady(async () => {
	const forms = document.querySelectorAll('.form');

	if (!forms.length) {
		return;
	}

	// On file upload.
	const withImageGroupPresent = [...forms].filter((form) => form.querySelector('.form-group.image'));

	if (withImageGroupPresent.length) {
		const { OnFileUpload } = await import('./on-file-upload');

		new OnFileUpload({
			withImageGroupPresent,
			inputSelector: 'form-input',
			imagePreviewSelector: 'image-preview-img',
		}).init();
	}

	// Confirmation on form submit.
	const requiresConfirmation = [...forms].filter((form) => form.getAttribute('data-confirmation'));

	if (requiresConfirmation.length) {
		const { OnSubmit } = await import('./on-submit');

		new OnSubmit({ requiresConfirmation }).init();
	}
});
