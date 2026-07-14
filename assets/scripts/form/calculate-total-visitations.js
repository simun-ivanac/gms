export class CalculateTotalVisitations {
	constructor({
		formWithVisitations,
	}) {
		this.forms = formWithVisitations;
	}

	init = () => {
		this.forms.forEach((form) => {
			const numOfWeeklyVisitations = form.querySelector('.form-group.num-of-visitations-per-week');
			const duration = form.querySelector('.form-group.duration');

			if (!numOfWeeklyVisitations || !duration) {
				return;
			}

			const numOfWeeklyVisitationsInput = numOfWeeklyVisitations.querySelector('input');
			const durationInput = duration.querySelector('input');

			if (!numOfWeeklyVisitationsInput || !durationInput) {
				return;
			}

			[numOfWeeklyVisitationsInput, durationInput].forEach((input) => {
				input.addEventListener('input', () => {
					const durationValue = parseInt(durationInput.value);
					const numOfWeeklyVisitationsValue = parseInt(numOfWeeklyVisitationsInput.value);
					let totalVisitations = 0;

					if (!isNaN(Number(durationValue)) && !isNaN(Number(numOfWeeklyVisitationsValue))) {
						const coefficientPerDay = 7 / numOfWeeklyVisitationsValue;
						totalVisitations = durationValue / coefficientPerDay;
					}

					numOfWeeklyVisitations.querySelector('.total-visitations-value').textContent = Math.round(totalVisitations) + ' times';
				});
			});
		});
	};
}
