export class OnFormFileUpload {
	constructor({
		imageFormGroups,
		inputSelector,
		imagePreviewSelector,
	}) {
		this.imageFormGroups = imageFormGroups;
		this.inputSelector = inputSelector;
		this.imagePreviewSelector = imagePreviewSelector;
	}

	// eslint-disable-next-line
	init = () => {
		this.imageFormGroups.forEach((imageFormGroup) => {
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
