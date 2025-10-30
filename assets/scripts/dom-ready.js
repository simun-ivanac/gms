/**
 * Specify a function to execute when the DOM is fully loaded.
 *
 * @param {Callback} callback A function to execute after the DOM is ready.
 *
 * @example
 * ```js
 * import domReady from '../dom-ready';
 *
 * domReady(() => {
 * 	// Do something after DOM loads.
 * });
 * ```
 *
 * @return {void}
 */
export default function domReady(callback) {
	if (typeof document === 'undefined') {
		return;
	}

	if (
		document.readyState === 'complete' || // DOMContentLoaded + Images/Styles/etc loaded, so we call directly.
		document.readyState === 'interactive' // DOMContentLoaded fires at this point, so we call directly.
	) {
		// eslint-disable-next-line
		return void callback();
	}

	// DOMContentLoaded has not fired yet, delay callback until then.
	document.addEventListener('DOMContentLoaded', callback);
}
