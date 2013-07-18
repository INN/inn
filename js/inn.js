jQuery(document).ready( function($) {

	//dropdown enhancements: clickable plusses for mobile!
	$("#mobile-nav .menu-mobile > li > .sub-menu, .footer-menu .menu > li > .sub-menu").each( function() {
		$(this).prev('a').before('<i class="menu-toggle icon-plus"></i>');
	});
	$(".menu-toggle").on('click', function(e) {
		var $this = $(this);
		if ( $this.hasClass('icon-plus') ) {
			$this.siblings('.sub-menu:hidden').slideDown('fast');
		} else {
			$this.siblings('.sub-menu:visible').slideUp('fast');
		}
		$this.toggleClass('icon-plus icon-minus');
		e.stopPropagation();
	});

	//dropdown hovering
	$("#top-nav .menu > li").has('.sub-menu').on( 'hover', function() {
		if ( ! $(this).hasClass('active-tree') ) {	//don't toggle body class when on active
			$('body').toggleClass('global-hovered');
		}
	});

	//dropdown permanence
	if ( $("#top-nav .menu .current-menu-item").length ) {
		$('body').addClass('global-active');
		$("#top-nav .current-menu-item").closest( 'ul.menu > li' ).addClass('active-tree');
	}

	//phone-level nav
	function moveMobileNav( width ) {
		if ( width > 540 ) {
			//move the .menu-mobile back
			$(".global-nav-bg + .menu-mobile").appendTo('#mobile-nav');
		} else {
			//move .menu-mobile onto its own
			$("#mobile-nav > .menu-mobile").insertAfter('.global-nav-bg');
		}
	}

	$("#mobile-nav > i").on('click', function() {
		//only for phones
		$('body').toggleClass('menu-mobile-open');

		if ( $('.global-nav-bg + .menu-mobile').length ) {
			if ( $('body.menu-mobile-open').length ) {
				//open it
				$('.menu-mobile:hidden').show();
				$('#mobile-nav').addClass('hover');
			} else {
				$('.menu-mobile:visible').hide();
				$('#mobile-nav').removeClass('hover');
			}
		}
	});

	//trigger moving phone-level nav
	$(window).on('resize orientationchange', function() {
		var winwidth = $(this).width();
		moveMobileNav( winwidth );
	});
	$(window).trigger('resize');

	//search behaviors
	$("a.icon-search").on( 'click', function() {
		$(this).parent().toggleClass('open');
		return false;
	});

	$("body").on( 'click', function() {
		$("#header-search.open").removeClass('open');
	});

	$(".form-search").on( 'click', function(e) {
		e.stopPropagation();	//don't register as a body click
	});

	//membership widget behaviors
	$(".inn-members-widget .members a").on( 'click', function( ) {
		var member_url = this.href,
			the_link = this;
		$(this).addClass('icon-spinner');
		$('.member-details').load( member_url + " article.inn_member", function ( resp, status, xhr ) {
			$(this).parent().fadeIn('fast');
			$("body").addClass('member-widget-open');
			$(the_link).removeClass('icon-spinner');
		});
		return false;
	});

	$(".member-details-wrapper .close").on( 'click', function() {
		$(this).parent().fadeOut('fast');
		$("body").removeClass('member-widget-open');
	});

	$(".members-menu a").each( function() {
		if ( this.href ==  window.location.href.slice(0, -1) || this.href == window.location.href ) {
			$(this).parent().remove();
		}
	});

	// Resources widget behaviors
	$('.inn-resources-widget .resources a').on( 'click', function() {
		$('.inn-resources-widget .resources a').removeClass('current');
		$(this).addClass('current');
		var which = $(this).data('panel');
		$('.resource-items > div:visible').hide();
		$('.resource-items .item-' + which).show();
		return false;
	});

	//Click the first resource
	$('.inn-resources-widget .resources a:first').trigger('click');

	// Adding classes to header social for CSS hiding
	$('#header-social i').each( function() {
		$(this).closest('li').addClass( $(this).attr('class') + '-parent' );
	});
});