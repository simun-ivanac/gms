/**
 * Replace image preview on new upload.
 */
import domReady from '../dom-ready';

domReady(async () => {
	const imageFormGroups = document.querySelectorAll('.form .form-group.image');

	if (!imageFormGroups.length) {
		return;
	}

	// eslint-disable-next-line
	const { OnFormFileUpload } = await import('./on-form-file-upload');

	new OnFormFileUpload({
		imageFormGroups,
		inputSelector: 'form-input',
		imagePreviewSelector: 'image-preview-img',
	}).init();
});
