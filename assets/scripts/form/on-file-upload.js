export class OnFileUpload {
	constructor({
		withImageGroupPresent,
		inputSelector,
		imagePreviewSelector,
	}) {
		this.forms = withImageGroupPresent;
		this.inputSelector = inputSelector;
		this.imagePreviewSelector = imagePreviewSelector;
	}

	init = () => {
		this.forms.forEach((form) => {
			const imageFormGroup = form.querySelector('.form-group.image');

			if (!imageFormGroup) {
				return;
			}

			const inputEl = imageFormGroup.querySelector(`.${this.inputSelector}`);
			const imagePreviewEl = imageFormGroup.querySelector(`.${this.imagePreviewSelector}`);

			inputEl?.addEventListener('change', (event) => {
				if (event.target.files.length > 0) {
					imagePreviewEl.src = URL.createObjectURL(event.target.files[0]);
				}
			});
		});
	};
}
