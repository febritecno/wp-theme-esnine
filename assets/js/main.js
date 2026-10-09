( function () {
	'use strict';

	var pad = function ( n ) {
		return String( n ).padStart( 2, '0' );
	};

	// Dismissible banner, remembered for the browser session.
	var banner = document.querySelector( '[data-banner]' );
	if ( banner ) {
		var dismissed = false;
		try {
			dismissed = sessionStorage.getItem( 'esnineBannerDismissed' ) === '1';
		} catch ( e ) {}

		if ( dismissed ) {
			banner.remove();
		} else {
			banner.querySelector( '[data-banner-close]' ).addEventListener( 'click', function () {
				banner.remove();
				try {
					sessionStorage.setItem( 'esnineBannerDismissed', '1' );
				} catch ( e ) {}
			} );
		}
	}

	// Mobile navigation overlay.
	var nav = document.querySelector( '[data-nav]' );
	if ( nav ) {
		var toggle = nav.querySelector( '[data-nav-toggle]' );
		var menu = nav.querySelector( '.navbar__links' );
		var setOpen = function ( open ) {
			menu.classList.toggle( 'is-open', open );
			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		};

		if ( toggle && menu ) {
			toggle.addEventListener( 'click', function () {
				setOpen( ! menu.classList.contains( 'is-open' ) );
			} );
			menu.addEventListener( 'click', function ( event ) {
				if ( event.target.closest( 'a' ) ) {
					setOpen( false );
				}
			} );
			document.addEventListener( 'keydown', function ( event ) {
				if ( event.key === 'Escape' && menu.classList.contains( 'is-open' ) ) {
					setOpen( false );
					toggle.focus();
				}
			} );
		}
	}

	// Carousels: 3 cards per page on desktop, 2 on tablet, 1 on mobile.
	var perPage = function () {
		return mobile.matches ? 1 : tablet.matches ? 2 : 3;
	};

	var mobile = window.matchMedia( '(max-width: 767px)' );
	var tablet = window.matchMedia( '(max-width: 1100px)' );

	// Property photo gallery: two photos per view, one on mobile.
	document.querySelectorAll( '[data-gallery]' ).forEach( function ( gallery ) {
		var slides = Array.prototype.slice.call( gallery.querySelectorAll( '[data-gallery-slide]' ) );
		var thumbs = Array.prototype.slice.call( gallery.querySelectorAll( '[data-gallery-thumb]' ) );
		var dots = gallery.querySelector( '[data-gallery-dots]' );
		var prev = gallery.querySelector( '[data-gallery-prev]' );
		var next = gallery.querySelector( '[data-gallery-next]' );
		var first = 0;
		var size = function () {
			return mobile.matches ? 1 : 2;
		};

		var render = function () {
			var per = size();
			var pages = Math.max( 1, Math.ceil( slides.length / per ) );
			var page = Math.min( pages - 1, Math.floor( first / per ) );

			first = page * per;
			slides.forEach( function ( slide, index ) {
				slide.hidden = index < first || index >= first + per;
			} );
			thumbs.forEach( function ( thumb, index ) {
				var active = index >= first && index < first + per;
				thumb.classList.toggle( 'is-active', active );
				thumb.setAttribute( 'aria-pressed', active ? 'true' : 'false' );
			} );
			if ( dots ) {
				dots.innerHTML = '';
				for ( var i = 0; i < pages; i++ ) {
					var dot = document.createElement( 'span' );
					dot.className = i === page ? 'is-active' : '';
					dots.appendChild( dot );
				}
			}
			if ( prev && next ) {
				prev.disabled = page === 0;
				next.disabled = page === pages - 1;
			}
		};

		thumbs.forEach( function ( thumb, index ) {
			thumb.addEventListener( 'click', function () {
				first = index;
				render();
			} );
		} );
		if ( prev && next ) {
			prev.addEventListener( 'click', function () {
				first = Math.max( 0, first - size() );
				render();
			} );
			next.addEventListener( 'click', function () {
				first = first + size();
				render();
			} );
		}
		mobile.addEventListener( 'change', render );
		render();
	} );

	document.querySelectorAll( '[data-carousel]' ).forEach( function ( carousel ) {
		var items = Array.prototype.slice.call( carousel.querySelectorAll( '[data-carousel-items] > *' ) );
		var current = carousel.querySelector( '[data-carousel-current]' );
		var total = carousel.querySelector( '[data-carousel-total]' );
		var prev = carousel.querySelector( '[data-carousel-prev]' );
		var next = carousel.querySelector( '[data-carousel-next]' );
		var first = 0;

		if ( ! items.length || ! prev || ! next ) {
			return;
		}

		var render = function () {
			var size = perPage();
			var pages = Math.max( 1, Math.ceil( items.length / size ) );
			var page = Math.min( pages, Math.floor( first / size ) + 1 );

			first = ( page - 1 ) * size;
			items.forEach( function ( item, index ) {
				item.hidden = index < first || index >= first + size;
			} );
			current.textContent = pad( page );
			total.textContent = pad( pages );
			prev.disabled = page === 1;
			next.disabled = page === pages;
		};

		// Slide the newly shown cards in from the side the visitor moved towards.
		var enter = function ( dir ) {
			var n = 0;
			items.forEach( function ( item ) {
				item.classList.remove( 'is-entering' );
				if ( item.hidden ) {
					return;
				}
				item.classList.add( 'is-visible' );
				item.style.setProperty( '--dir', dir );
				item.style.setProperty( '--n', n++ );
				void item.offsetWidth;
				item.classList.add( 'is-entering' );
			} );
		};

		prev.addEventListener( 'click', function () {
			first = Math.max( 0, first - perPage() );
			render();
			enter( -1 );
		} );
		next.addEventListener( 'click', function () {
			first = first + perPage();
			render();
			enter( 1 );
		} );
		mobile.addEventListener( 'change', render );
		tablet.addEventListener( 'change', render );
		render();
		carousel.classList.add( 'is-ready' );

		// Autoplay: advance a page at a time and wrap around. It waits while the
		// visitor hovers or focuses the carousel, the tab is hidden, or the
		// section is off screen.
		var delay = parseInt( carousel.getAttribute( 'data-carousel-autoplay' ), 10 );
		var timer = null;
		var held = false;
		var onScreen = false;

		if ( ! delay || window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches || ! ( 'IntersectionObserver' in window ) ) {
			return;
		}
		current.parentNode.setAttribute( 'aria-live', 'off' );

		var advance = function () {
			if ( items.length <= perPage() ) {
				return;
			}
			first = first + perPage() >= items.length ? 0 : first + perPage();
			render();
			enter( 1 );
		};
		var schedule = function () {
			clearInterval( timer );
			timer = ( onScreen && ! held && ! document.hidden ) ? setInterval( advance, delay ) : null;
		};
		var hold = function ( state ) {
			return function () {
				held = state;
				schedule();
			};
		};

		carousel.addEventListener( 'mouseenter', hold( true ) );
		carousel.addEventListener( 'mouseleave', hold( false ) );
		carousel.addEventListener( 'focusin', hold( true ) );
		carousel.addEventListener( 'focusout', hold( false ) );
		prev.addEventListener( 'click', schedule );
		next.addEventListener( 'click', schedule );
		document.addEventListener( 'visibilitychange', schedule );
		new IntersectionObserver( function ( entries ) {
			onScreen = entries[ 0 ].isIntersecting;
			schedule();
		}, { threshold: 0.3 } ).observe( carousel );
	} );

	document.addEventListener( 'click', function ( event ) {
		var toggle = event.target.closest( '[data-more-toggle]' );
		var box = toggle && toggle.closest( '[data-more]' );

		if ( ! box ) {
			return;
		}
		var open = toggle.getAttribute( 'aria-expanded' ) !== 'true';

		box.querySelector( '[data-more-short]' ).hidden = open;
		box.querySelector( '[data-more-full]' ).hidden = ! open;
		box.classList.toggle( 'is-open', open );
		toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		toggle.textContent = toggle.getAttribute( open ? 'data-less-label' : 'data-more-label' );
	} );

	var reduced = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	var targets = document.querySelectorAll( '.section__heading, .cards > *, .stat, .features > li, .pd__top, .pd__panel, .pricing__note, .pricing__listing, .pricing__card, .form, .psearch, .pager, .cta__text, .cta__button, .footer__main' );

	var countUp = function ( el ) {
		var match = el.textContent.trim().match( /^(\d[\d,.]*)(.*)$/ );
		var target = match ? parseFloat( match[ 1 ].replace( /,/g, '' ) ) : NaN;
		var start = null;

		if ( reduced || isNaN( target ) ) {
			return;
		}
		var step = function ( now ) {
			start = start || now;
			var progress = Math.min( 1, ( now - start ) / 1200 );
			var eased = 1 - Math.pow( 1 - progress, 3 );

			el.textContent = Math.round( target * eased ).toLocaleString() + match[ 2 ];
			if ( progress < 1 ) {
				requestAnimationFrame( step );
			} else {
				el.textContent = match[ 0 ];
			}
		};
		requestAnimationFrame( step );
	};

	if ( 'IntersectionObserver' in window ) {
		var observer = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( ! entry.isIntersecting ) {
					return;
				}
				entry.target.classList.add( 'is-visible' );
				observer.unobserve( entry.target );
				entry.target.querySelectorAll( '[data-count]' ).forEach( countUp );
			} );
		}, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 } );

		targets.forEach( function ( el ) {
			var siblings = el.parentNode ? Array.prototype.filter.call( el.parentNode.children, function ( child ) {
				return ! child.hidden;
			} ) : [];

			el.style.setProperty( '--i', Math.max( 0, siblings.indexOf( el ) ) % 4 );
			el.setAttribute( 'data-reveal', '' );
			observer.observe( el );
		} );
	} else {
		targets.forEach( function ( el ) {
			el.classList.add( 'is-visible' );
		} );
	}
} )();
