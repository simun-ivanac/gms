export class ToggleFormFields {
	constructor({
		formWithToggleableFields,
	}) {
		this.forms = formWithToggleableFields;
		this.HIDE_FIELD_CLASS = 'hide-field';
	}

	init = () => {
		this.forms.forEach((form) => {
			const toggleableField = form.querySelector('.form-group.field-toggleable');

			if (!toggleableField) {
				return;
			}

			const targetFieldSelector = toggleableField.getAttribute('data-toggle-field');
			const targetField = form.querySelector(`.form-group.${targetFieldSelector}`);

			if (!targetField) {
				return;
			}

			const toggleableInputs = toggleableField.querySelectorAll('input[type="radio"]');

			toggleableInputs.forEach((input) => {
				input.addEventListener('change', () => {
					if (input.checked && input.value === '1') {
						targetField.classList.remove(this.HIDE_FIELD_CLASS);
					} else {
						targetField.classList.add(this.HIDE_FIELD_CLASS);
					}
				});
			});
		});
	};
}
