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
		var compareConfig = getCompareConfig();
		var compareIdsFromUrl = getCompareIdsFromUrl(compareConfig);
		var storedCompareIds = getStoredCompareIds(compareConfig);

		if (compareIdsFromUrl.length) {
			setStoredCompareIds(compareIdsFromUrl, compareConfig);
		} else if (isCurrentCompareBasePage(compareConfig) && storedCompareIds.length) {
			window.location.replace(buildCompareUrl(storedCompareIds, compareConfig));
			return;
		}

		var compareCheckboxes = document.querySelectorAll('.compare-checkbox');
		if (compareCheckboxes.length > 0) {
			syncCompareUI(compareConfig);

			compareCheckboxes.forEach(function(checkbox) {
				checkbox.addEventListener('change', function() {
					updateCompareSelectionForCheckbox(checkbox, compareConfig);
				});
			});
		}

		document.querySelectorAll('.compare-shortlist-toggle').forEach(function(toggle) {
			toggle.addEventListener('click', function(event) {
				var productId = sanitizeCompareId(toggle.getAttribute('data-product-id'));
				if (!productId) {
					return;
				}

				var selectedIds = getCurrentCompareIds(compareConfig);

				if (selectedIds.indexOf(productId) !== -1) {
					toggle.setAttribute('href', buildCompareUrl(selectedIds, compareConfig));
					return;
				}

				event.preventDefault();
				selectedIds.push(productId);
				setStoredCompareIds(selectedIds, compareConfig);
				syncCompareUI(compareConfig);
			});
		});

		document.querySelectorAll('.compare-remove-button').forEach(function(removeButton) {
			removeButton.addEventListener('click', function(event) {
				var productId = sanitizeCompareId(removeButton.getAttribute('data-product-id'));
				if (!productId) {
					return;
				}

				event.preventDefault();
				var nextIds = getCurrentCompareIds(compareConfig).filter(function(currentId) {
					return currentId !== productId;
				});

				setStoredCompareIds(nextIds, compareConfig);
				window.location.href = buildCompareUrl(nextIds, compareConfig);
			});
		});

		var compareButton = document.getElementById('compare-selected-button');
		if (!compareButton) {
			syncCompareUI(compareConfig);
			return;
		}

		compareButton.addEventListener('click', function() {
			if (compareButton.disabled) {
				return;
			}

			var selectedIds = getCurrentCompareIds(compareConfig);

			if (selectedIds.length < compareConfig.minSelection) {
				return;
			}

			window.location.href = buildCompareUrl(selectedIds, compareConfig);
		});

		syncCompareUI(compareConfig);
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

	function updateCompareButton(compareIds, compareConfig) {
		var compareButton = document.getElementById('compare-selected-button');

		if (!compareButton) {
			return;
		}

		var checkedItems = Array.isArray(compareIds) ? compareIds : [];
		var minSelection = compareConfig.minSelection;
		var baseLabel = compareButton.getAttribute('data-label') || 'Compare Selected';
		var remaining = minSelection - checkedItems.length;

		if (checkedItems.length === 0) {
			compareButton.textContent = baseLabel;
			compareButton.title = '';
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

	function updateCompareSelectionForCheckbox(checkbox, compareConfig) {
		var productId = sanitizeCompareId(checkbox.value);
		if (!productId) {
			return;
		}

		var selectedIds = getCurrentCompareIds(compareConfig);
		var currentIndex = selectedIds.indexOf(productId);

		if (checkbox.checked && currentIndex === -1) {
			selectedIds.push(productId);
		}

		if (!checkbox.checked && currentIndex !== -1) {
			selectedIds.splice(currentIndex, 1);
		}

		setStoredCompareIds(selectedIds, compareConfig);
		syncCompareUI(compareConfig);
	}

	function syncCompareUI(compareConfig) {
		var selectedIds = getCurrentCompareIds(compareConfig);
		syncCompareCheckboxes(selectedIds);
		syncShortlistButtons(selectedIds, compareConfig);
		updateCompareButton(selectedIds, compareConfig);
	}

	function syncCompareCheckboxes(selectedIds) {
		document.querySelectorAll('.compare-checkbox').forEach(function(checkbox) {
			var productId = sanitizeCompareId(checkbox.value);
			checkbox.checked = !!productId && selectedIds.indexOf(productId) !== -1;
		});
	}

	function syncShortlistButtons(selectedIds, compareConfig) {
		document.querySelectorAll('.compare-shortlist-toggle').forEach(function(toggle) {
			var productId = sanitizeCompareId(toggle.getAttribute('data-product-id'));
			var addLabel = toggle.getAttribute('data-label-add') || 'Add To Shortlist';
			var selectedLabel = toggle.getAttribute('data-label-selected') || 'Shortlisted';
			var fallbackHref = toggle.getAttribute('data-fallback-href') || compareConfig.compareUrl;
			var isSelected = !!productId && selectedIds.indexOf(productId) !== -1;

			toggle.textContent = isSelected ? selectedLabel : addLabel;
			toggle.setAttribute('href', isSelected ? buildCompareUrl(selectedIds, compareConfig) : fallbackHref);
			toggle.setAttribute('aria-pressed', isSelected ? 'true' : 'false');
			toggle.setAttribute('title', isSelected ? 'This machine is already in the compare shortlist.' : 'Add this machine to the compare shortlist.');
			toggle.classList.toggle('ring-2', isSelected);
			toggle.classList.toggle('ring-amber-300', isSelected);
		});
	}

	function getCompareConfig() {
		var compareConfig = window.industrialWelding || {};

		return {
			compareUrl: compareConfig.compareUrl || '/compare/',
			compareQueryKey: compareConfig.compareQueryKey || 'products',
			minSelection: compareConfig.compareMinSelect || 2,
			storageKey: compareConfig.compareStorageKey || 'industrial_welding_compare_shortlist'
		};
	}

	function getCurrentCompareIds(compareConfig) {
		var storedIds = getStoredCompareIds(compareConfig);
		if (storedIds.length) {
			return storedIds;
		}

		return getCompareIdsFromUrl(compareConfig);
	}

	function getCompareIdsFromUrl(compareConfig) {
		try {
			var currentUrl = new URL(window.location.href);
			var rawValue = currentUrl.searchParams.get(compareConfig.compareQueryKey) || currentUrl.searchParams.get('machines') || '';
			return sanitizeCompareIds(rawValue.split(','));
		} catch (error) {
			return [];
		}
	}

	function getStoredCompareIds(compareConfig) {
		if (!window.localStorage) {
			return [];
		}

		try {
			var rawValue = window.localStorage.getItem(compareConfig.storageKey);
			if (!rawValue) {
				return [];
			}

			var parsedValue = JSON.parse(rawValue);
			return sanitizeCompareIds(Array.isArray(parsedValue) ? parsedValue : []);
		} catch (error) {
			return [];
		}
	}

	function setStoredCompareIds(compareIds, compareConfig) {
		if (!window.localStorage) {
			return;
		}

		var sanitizedIds = sanitizeCompareIds(compareIds);

		try {
			if (!sanitizedIds.length) {
				window.localStorage.removeItem(compareConfig.storageKey);
				return;
			}

			window.localStorage.setItem(compareConfig.storageKey, JSON.stringify(sanitizedIds));
		} catch (error) {
			// Ignore storage write failures and keep the non-persistent fallback behavior.
		}
	}

	function sanitizeCompareIds(compareIds) {
		var sanitizedIds = [];

		(compareIds || []).forEach(function(compareId) {
			var sanitizedId = sanitizeCompareId(compareId);

			if (!sanitizedId || sanitizedIds.indexOf(sanitizedId) !== -1) {
				return;
			}

			sanitizedIds.push(sanitizedId);
		});

		return sanitizedIds;
	}

	function sanitizeCompareId(compareId) {
		var normalizedId = String(compareId || '').replace(/[^\d]/g, '');

		if (!normalizedId) {
			return '';
		}

		return parseInt(normalizedId, 10) > 0 ? normalizedId : '';
	}

	function buildCompareUrl(compareIds, compareConfig) {
		try {
			var compareUrl = new URL(compareConfig.compareUrl, window.location.origin);
			compareUrl.searchParams.delete(compareConfig.compareQueryKey);
			compareUrl.searchParams.delete('machines');

			if (compareIds.length) {
				compareUrl.searchParams.set(compareConfig.compareQueryKey, sanitizeCompareIds(compareIds).join(','));
			}

			return compareUrl.toString();
		} catch (error) {
			return compareConfig.compareUrl;
		}
	}

	function isCurrentCompareBasePage(compareConfig) {
		try {
			var currentUrl = new URL(window.location.href);
			var compareUrl = new URL(compareConfig.compareUrl, window.location.origin);

			return currentUrl.pathname === compareUrl.pathname && !getCompareIdsFromUrl(compareConfig).length;
		} catch (error) {
			return false;
		}
	}

	window.addEventListener('load', function() {
		document.body.classList.add('js-loaded');
	});
})();
