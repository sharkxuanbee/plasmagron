/**
 * Industrial Welding Theme JavaScript
 *
 * @package Industrial_Welding
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        var mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        var mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuToggle && mobileMenu) {
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
        if (compareButton) {
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
    });

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
