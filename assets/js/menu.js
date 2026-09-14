/**
 * Navigasi: menu lipat pada layar kecil, dan dua dropdown yang muncul saat
 * kursor berada di atasnya pada layar besar.
 *
 * Dropdown dibuka dengan menambah kelas is-open; peredupannya diatur CSS.
 * Pada perangkat sentuh tidak ada hover, jadi ketukan pertama membuka panel.
 */
( function () {
	var area = document.querySelector( '.site-header-area' );

	if ( ! area ) {
		return;
	}

	/* ---------- Menu lipat ---------- */

	var tombol = area.querySelector( '.nav-toggle' );
	var nav = document.getElementById( 'menu-utama' );

	function tutupMenu() {
		if ( nav ) {
			nav.classList.remove( 'is-open' );
		}
		if ( tombol ) {
			tombol.setAttribute( 'aria-expanded', 'false' );
		}
		document.body.classList.remove( 'menu-terbuka' );
	}

	if ( tombol && nav ) {
		tombol.addEventListener( 'click', function () {
			var terbuka = nav.classList.toggle( 'is-open' );
			tombol.setAttribute( 'aria-expanded', terbuka ? 'true' : 'false' );
			document.body.classList.toggle( 'menu-terbuka', terbuka );
		} );
	}

	/* ---------- Dropdown ---------- */

	var panel = [
		{ label: 'layanan', el: document.getElementById( 'mega-menu-layanan' ) },
		{ label: 'pelayanan publik', el: document.getElementById( 'dropdown-pelayanan-publik' ) }
	].filter( function ( p ) {
		return p.el;
	} );

	var tundaTutup = null;

	function semuaTautan() {
		return Array.prototype.slice.call( area.querySelectorAll( '.nav-links a' ) );
	}

	function pemicuUntuk( p ) {
		return semuaTautan().filter( function ( a ) {
			return a.textContent.trim().toLowerCase() === p.label;
		} );
	}

	function tutupSemua() {
		panel.forEach( function ( p ) {
			p.el.classList.remove( 'is-open' );
			pemicuUntuk( p ).forEach( function ( a ) {
				a.setAttribute( 'aria-expanded', 'false' );
			} );
		} );
	}

	function buka( p ) {
		clearTimeout( tundaTutup );
		panel.forEach( function ( lain ) {
			if ( lain !== p ) {
				lain.el.classList.remove( 'is-open' );
			}
		} );
		p.el.classList.add( 'is-open' );
		pemicuUntuk( p ).forEach( function ( a ) {
			a.setAttribute( 'aria-expanded', 'true' );
		} );
	}

	/* Jeda singkat supaya kursor sempat berpindah dari tautan ke panel. */
	function jadwalkanTutup() {
		clearTimeout( tundaTutup );
		tundaTutup = setTimeout( tutupSemua, 160 );
	}

	var adaHover = window.matchMedia( '(hover: hover)' ).matches;

	panel.forEach( function ( p ) {
		p.el.setAttribute( 'aria-hidden', 'false' );

		pemicuUntuk( p ).forEach( function ( a ) {
			a.setAttribute( 'aria-expanded', 'false' );
			a.setAttribute( 'aria-controls', p.el.id );

			if ( adaHover ) {
				a.addEventListener( 'mouseenter', function () {
					buka( p );
				} );
				a.addEventListener( 'mouseleave', jadwalkanTutup );
				a.addEventListener( 'focus', function () {
					buka( p );
				} );
			}

			a.addEventListener( 'click', function ( event ) {
				event.preventDefault();
				var sedangTerbuka = p.el.classList.contains( 'is-open' );
				if ( sedangTerbuka ) {
					tutupSemua();
				} else {
					buka( p );
				}
			} );
		} );

		if ( adaHover ) {
			p.el.addEventListener( 'mouseenter', function () {
				clearTimeout( tundaTutup );
			} );
			p.el.addEventListener( 'mouseleave', jadwalkanTutup );
		}

		var tutupPanel = p.el.querySelector( '.mega-menu__tutup' );

		if ( tutupPanel ) {
			tutupPanel.addEventListener( 'click', tutupSemua );
		}
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' === event.key ) {
			tutupSemua();
			tutupMenu();
		}
	} );

	document.addEventListener( 'click', function ( event ) {
		if ( ! area.contains( event.target ) ) {
			tutupSemua();
		}
	} );
}() );
