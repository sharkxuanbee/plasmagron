/**
 * Industrial Welding Theme JavaScript
 *
 * @package Industrial_Welding
 */

(function() {
	'use strict';

	document.addEventListener('DOMContentLoaded', function() {
		initMobileMenu();
		initMachineTabs();
		initCompareButtons();
		initFinder();
	});

	function initMobileMenu() {
		var mobileMenuToggle = document.getElementById('mobile-menu-toggle');
		var mobileMenu = document.getElementById('mobile-menu');

		if (!mobileMenuToggle || !mobileMenu) {
			return;
		}

		mobileMenuToggle.addEventListener('click', function() {
			var isExpanded = mobileMenuToggle.getAttribute('aria-expanded') === 'true';

			mobileMenuToggle.setAttribute('aria-expanded', !isExpanded);
			mobileMenu.classList.toggle('hidden');
			document.body.classList.toggle('overflow-hidden', !isExpanded);

			var icon = mobileMenuToggle.querySelector('svg');
			if (icon) {
				if (!isExpanded) {
					icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
				} else {
					icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>';
				}
			}
		});
	}

	function initMachineTabs() {
		var machineTabs = document.querySelectorAll('[data-tab]');
		var tabContents = document.querySelectorAll('[data-tab-content]');

		machineTabs.forEach(function(tab) {
			tab.addEventListener('click', function() {
				var target = this.getAttribute('data-tab');

				machineTabs.forEach(function(currentTab) {
					currentTab.classList.remove('active', 'text-yellow-500', 'border-yellow-500');
					currentTab.classList.add('text-gray-400');
				});

				this.classList.add('active', 'text-yellow-500', 'border-yellow-500');
				this.classList.remove('text-gray-400');

				tabContents.forEach(function(content) {
					content.classList.add('hidden');
				});

				var targetContent = document.querySelector('[data-tab-content="' + target + '"]');
				if (targetContent) {
					targetContent.classList.remove('hidden');
				}
			});
		});
	}

	function initCompareButtons() {
		var compareCheckboxes = document.querySelectorAll('.compare-checkbox');
		if (compareCheckboxes.length > 0) {
			updateCompareButton();

			compareCheckboxes.forEach(function(checkbox) {
				checkbox.addEventListener('change', function() {
					updateCompareButton();
				});
			});
		}

		var compareButton = document.getElementById('compare-selected-button');
		if (!compareButton) {
			return;
		}

		compareButton.addEventListener('click', function() {
			if (compareButton.disabled) {
				return;
			}

			var selectedIds = getSelectedCompareIds();

			if (!selectedIds.length) {
				return;
			}

			var compareConfig = window.industrialWelding || {};
			var compareUrl = compareButton.getAttribute('data-base-url') || compareConfig.compareUrl || '/compare/';
			var compareQueryKey = compareConfig.compareQueryKey || 'products';
			var separator = compareUrl.indexOf('?') === -1 ? '?' : '&';

			window.location.href = compareUrl + separator + compareQueryKey + '=' + selectedIds.join(',');
		});
	}

	function initFinder() {
		var form = document.querySelector('[data-finder-form]');
		if (!form) {
			return;
		}

		var steps = Array.prototype.slice.call(form.querySelectorAll('[data-finder-step]'));
		var progress = form.querySelector('[data-finder-progress]');
		var status = form.querySelector('[data-finder-status]');
		var currentIndex = getInitialFinderStepIndex(steps);

		function showStep(index) {
			currentIndex = Math.max(0, Math.min(index, steps.length - 1));

			steps.forEach(function(step, stepIndex) {
				var isActive = stepIndex === currentIndex;
				step.classList.toggle('hidden', !isActive);
				step.classList.toggle('finder-step--active', isActive);
			});

			if (progress) {
				progress.textContent = (currentIndex + 1) + ' / ' + steps.length;
			}

			clearFinderStatus();
		}

		function clearFinderStatus() {
			if (status) {
				status.textContent = '';
				status.classList.add('hidden');
			}

			steps.forEach(function(step) {
				step.classList.remove('finder-step--invalid');
			});
		}

		function setFinderStatus(message, step) {
			clearFinderStatus();

			if (step) {
				step.classList.add('finder-step--invalid');
			}

			if (status) {
				status.textContent = message;
				status.classList.remove('hidden');
			}
		}

		steps.forEach(function(step, stepIndex) {
			var radioInputs = step.querySelectorAll('input[type="radio"]');
			var nextButton = step.querySelector('[data-finder-next]');
			var prevButton = step.querySelector('[data-finder-prev]');

			radioInputs.forEach(function(input) {
				input.addEventListener('change', function() {
					clearFinderStatus();
				});
			});

			if (nextButton) {
				nextButton.addEventListener('click', function() {
					if (stepHasChoices(step) && !stepHasSelection(step)) {
						setFinderStatus('Choose one option before moving to the next question.', step);
						return;
					}

					showStep(stepIndex + 1);
				});
			}

			if (prevButton) {
				prevButton.addEventListener('click', function() {
					showStep(stepIndex - 1);
				});
			}
		});

		form.addEventListener('submit', function(event) {
			var currentStep = steps[currentIndex];

			if (currentStep && stepHasChoices(currentStep) && !stepHasSelection(currentStep)) {
				event.preventDefault();
				setFinderStatus('Choose one option before showing recommendations.', currentStep);
			}
		});

		showStep(currentIndex);
	}

	function stepHasChoices(step) {
		return !!step.querySelector('input[type="radio"]');
	}

	function stepHasSelection(step) {
		return !!step.querySelector('input[type="radio"]:checked');
	}

	function getInitialFinderStepIndex(steps) {
		for (var i = 0; i < steps.length; i += 1) {
			if (stepHasChoices(steps[i]) && !stepHasSelection(steps[i])) {
				return i;
			}
		}

		return steps.length ? steps.length - 1 : 0;
	}

	function updateCompareButton() {
		var checkedItems = getSelectedCompareIds();
		var compareButton = document.getElementById('compare-selected-button');

		if (!compareButton) {
			return;
		}

		var compareConfig = window.industrialWelding || {};
		var minSelection = compareConfig.compareMinSelect || 2;
		var baseLabel = compareButton.getAttribute('data-label') || 'Compare Selected';
		var remaining = minSelection - checkedItems.length;

		if (checkedItems.length === 0) {
			compareButton.textContent = baseLabel;
		} else if (checkedItems.length < minSelection) {
			compareButton.textContent = baseLabel + ' (' + checkedItems.length + '/' + minSelection + ')';
			compareButton.title = 'Select ' + remaining + ' more machine' + (remaining > 1 ? 's' : '') + ' to compare';
		} else {
			compareButton.textContent = baseLabel + ' (' + checkedItems.length + ')';
			compareButton.title = '';
		}

		if (checkedItems.length >= minSelection) {
			compareButton.disabled = false;
			compareButton.classList.remove('opacity-50', 'cursor-not-allowed');
		} else {
			compareButton.disabled = true;
			compareButton.classList.add('opacity-50', 'cursor-not-allowed');
		}
	}

	function getSelectedCompareIds() {
		var selectedIds = [];

		document.querySelectorAll('.compare-checkbox:checked').forEach(function(checkbox) {
			selectedIds.push(checkbox.value);
		});

		return selectedIds;
	}

	window.addEventListener('load', function() {
		document.body.classList.add('js-loaded');
	});
})();
