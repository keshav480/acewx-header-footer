(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
// jQuery(document).ready(function ($) {
//     $('.acewx-hf-menu-toggle').on('click', function () {
//         if ($(window).width() > 768) return; 
//         const isOpen = $('body').toggleClass('sidebar-open').hasClass('sidebar-open');
//         $(this).attr('aria-expanded', isOpen ? 'true' : 'false');
//     });
//     $('.sidebar-close-menu-sidebar').on('click', function () {
//        close_acewx_sidebar();
//     });
// 	$('.sidebar-overlay').on('click', function () {
//        close_acewx_sidebar();
//     });
// 	function close_acewx_sidebar(){
// 		 $('body').removeClass('sidebar-open');
//         $('.acewx-hf-menu-toggle').attr('aria-expanded', 'false');
// 	}
	
// 	// open menu on click of sidebar menu icon 
// 	$('.mobile-sidebar ul.menu li.menu-item-has-children > a > span.acewx-menu-item-icon').click(function(e){
// 		e.preventDefault(); 
// 		var $parentLi = $(this).closest('li.menu-item-has-children');
// 		$parentLi.toggleClass('active');
// 		$parentLi.children('.sub-menu').slideToggle();
// 	});
// });

	jQuery(document).ready(function ($) {
		function closeAllSubmenus() {
			$('#custom-nav-menu-sidebar li.menu-item-has-children')
				.removeClass('active')
				.children('.sub-menu')
				.slideUp(0);
		}
		function openCurrentMenuPath() {
			$('#custom-nav-menu-sidebar .current-menu-item, #custom-nav-menu-sidebar .current-menu-ancestor')
				.addClass('active')
				.children('.sub-menu')
				.slideDown(0);
		}

		$('.acewx-hf-menu-toggle').on('click', function () {
			$('#custom-nav-menu-sidebar').toggleClass('active');
		});
		$('#sidebar-close-menu-sidebar').on('click', function () {
			$('#custom-nav-menu-sidebar').removeClass('active');
		});
		$('#custom-nav-menu-sidebar ul.menu li.menu-item-has-children > a > span.acewx-menu-item-icon').click(function(e){
			e.preventDefault(); 
			var parentLi = $(this).closest('li.menu-item-has-children');
			parentLi.toggleClass('active');
			parentLi.children('.sub-menu').slideToggle();
		});
	});
	$(document).on('click', function (e) {
			if (
				!$(e.target).closest('#custom-nav-menu-sidebar').length &&
				!$(e.target).closest('.acewx-hf-menu-toggle').length
			) {
				$('#custom-nav-menu-sidebar').removeClass('active');
			}
	});
    $('#custom-nav-menu-sidebar').on('click', function (e) {
        e.stopPropagation();
		
    });


})( jQuery );
