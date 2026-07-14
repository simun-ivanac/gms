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
	const formWwithImageGroup = [...forms].filter((form) => form.querySelector('.form-group.image'));

	if (formWwithImageGroup.length) {
		const { OnFileUpload } = await import('./on-file-upload');

		new OnFileUpload({
			formWwithImageGroup,
			inputSelector: 'form-input',
			imagePreviewSelector: 'image-preview-img',
		}).init();
	}

	// Confirmation on form submit.
	const formRequiresConfirmation = [...forms].filter((form) => form.getAttribute('data-confirmation'));

	if (formRequiresConfirmation.length) {
		const { OnSubmit } = await import('./on-submit');

		new OnSubmit({ formRequiresConfirmation }).init();
	}

	// Toggle form fields.
	const formWithToggleableFields = [...forms].filter((form) => form.querySelector('.form-group.field-toggleable'));

	if (formWithToggleableFields.length) {
		const { ToggleFormFields } = await import('./toggle-form-fields');

		new ToggleFormFields({ formWithToggleableFields }).init();
	}

	// Calculate total visitations in plan.
	const formWithVisitations = [...forms].filter((form) => form.classList.contains('form-plan-data'));

	if (formWithVisitations.length) {
		const { CalculateTotalVisitations } = await import('./calculate-total-visitations');

		new CalculateTotalVisitations({ formWithVisitations }).init();
	}

});
