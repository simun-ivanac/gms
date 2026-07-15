export class CalculateTotalVisitations {
	constructor({
		formWithVisitations,
	}) {
		this.forms = formWithVisitations;
	}

	init = () => {
		this.forms.forEach((form) => {
			const durationEl = form.querySelector('.form-group.duration');
			const numOfVisitationsEl = form.querySelector('.num-of-visitations');
			const timePeriodEl = form.querySelector('.time-period');
			const totalVisitationsEl = form.querySelector('.total-visitations');

			if (!durationEl || !numOfVisitationsEl || !timePeriodEl || !totalVisitationsEl) {
				return;
			}

			const numOfVisitationsInput = numOfVisitationsEl.querySelector('input');
			const durationInput = durationEl.querySelector('input');
			const timePeriodSelect = timePeriodEl.querySelector('select');

			if (!durationInput || !numOfVisitationsInput || !timePeriodSelect) {
				return;
			}

			// Initial calculation of total visitations.
			const initResult = this.calculateTotalVisitations(
				durationInput.value,
				numOfVisitationsInput.value,
				timePeriodSelect.value
			);

			totalVisitationsEl.querySelector('.total-visitations-value').textContent = initResult + ' times';

			// Calculate total visitations on change.
			[durationInput, numOfVisitationsInput, timePeriodSelect].forEach((el) => {
				el.addEventListener('input', () => {
					const result = this.calculateTotalVisitations(
						durationInput.value,
						numOfVisitationsInput.value,
						timePeriodSelect.value
					);

					totalVisitationsEl.querySelector('.total-visitations-value').textContent = result + ' times';
				});
			});
		});
	};

	calculateTotalVisitations = (durationVal, numOfVisitationsVal, timePeriodVal) => {
		const duration = parseInt(durationVal);
		const numOfVisitations = parseInt(numOfVisitationsVal);
		const timePeriod = timePeriodVal;
		let totalVisitations = 0;

		if (isNaN(Number(duration)) || isNaN(Number(numOfVisitations))) {
			return totalVisitations;
		}

		if (timePeriod === 'in-total') {
			return numOfVisitations;
		}

		const coefficientPerDay = this.calculateCoefficientPerDay(timePeriod, numOfVisitations);
		totalVisitations = duration / coefficientPerDay;

		return Math.round(totalVisitations);
	};

	calculateCoefficientPerDay = (timePeriod, numOfVisitations) => {
		let timePeriodInNumbers = 0;

		switch (timePeriod) {
			case 'daily':
				timePeriodInNumbers = 1;
				break;
			case 'weekly':
				timePeriodInNumbers = 7;
				break;
			case 'monthly':
				timePeriodInNumbers = 30;
				break;
		}

		return timePeriodInNumbers / numOfVisitations;
	};

}
