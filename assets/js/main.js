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

                machineTabs.forEach(function(t) {
                    t.classList.remove('active', 'text-yellow-500', 'border-yellow-500');
                    t.classList.add('text-gray-400');
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
    });

    function updateCompareButton() {
        var checkboxes = document.querySelectorAll('.compare-checkbox:checked');
        var compareButton = document.getElementById('compare-selected-button');
        
        if (compareButton) {
            if (checkboxes.length >= 2) {
                compareButton.disabled = false;
                compareButton.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                compareButton.disabled = true;
                compareButton.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }
    }

    window.addEventListener('load', function() {
        document.body.classList.add('js-loaded');
    });

})();
