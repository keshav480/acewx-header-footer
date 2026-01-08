(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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
	jQuery(document).ready(function($){
		$("#acewx_display_pages").select2({
			placeholder: "Select pages...",
			allowClear: true,
			width: "100%"
		});
	});
	document.addEventListener('click', function(e){
    const toggle = e.target.closest('.menu-toggle');
    if(toggle){
        toggle.closest('.custom-nav-menu').classList.toggle('active');
    }
});
jQuery(document).on('change', '.acewx-toggle-status', function () {
    const checkbox = jQuery(this);
    const postId = checkbox.closest('.acewx-switch').data('post-id');
    const status = checkbox.is(':checked') ? '1' : '0';

    jQuery.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'acewx_toggle_hf_status',
            post_id: postId,
            status: status,
            _ajax_nonce: acewx_admin.nonce
        }
    });
});

})( jQuery );
