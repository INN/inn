jQuery(document).ready( function($) {

	// Adding classes to header social for CSS hiding
	$('#header-social i').each( function() {
		$(this).closest('li').addClass( $(this).attr('class') + '-parent' );
	});

	// Visibility toggling, mostly for member contact forms
	$('.toggle').on('click', function() {
		target = $(this).data('toggler');
		$( target ).slideToggle('fast');
	});

	//hide the member contact form, this is a hack to get around weird paupress sizing
	$('.toggle').trigger('click');

	// Submitting links for membership directory states
	$('.member-nav select').on('change', function() {
		window.location = $(this).val();
	});

});
