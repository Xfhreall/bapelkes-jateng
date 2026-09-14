/**
 * Membuka mega menu Layanan dari navbar (Figma 40:3347).
 * Tautan Layanan tetap mengarah ke halamannya, jadi tanpa JavaScript menu
 * ini hanya berperilaku sebagai tautan biasa.
 */
( function () {
	/* Menu utama pada layar kecil. */
	var tombol = document.querySelector( '.nav-toggle' );
	var nav = document.getElementById( 'menu-utama' );

	if ( tombol && nav ) {
		tombol.addEventListener( 'click', function () {
			var terbuka = nav.classList.toggle( 'is-open' );
			tombol.setAttribute( 'aria-expanded', terbuka ? 'true' : 'false' );
			document.body.classList.toggle( 'menu-terbuka', terbuka );
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' === event.key && nav.classList.contains( 'is-open' ) ) {
				nav.classList.remove( 'is-open' );
				tombol.setAttribute( 'aria-expanded', 'false' );
				document.body.classList.remove( 'menu-terbuka' );
			}
		} );
	}

	var menu = document.getElementById( 'mega-menu-layanan' );

	if ( ! menu ) {
		return;
	}

	var pemicu = Array.prototype.filter.call(
		document.querySelectorAll( '.nav-links a' ),
		function ( tautan ) {
			return 'layanan' === tautan.textContent.trim().toLowerCase();
		}
	);

	function tutup() {
		menu.hidden = true;
		pemicu.forEach( function ( tautan ) {
			tautan.setAttribute( 'aria-expanded', 'false' );
		} );
	}

	pemicu.forEach( function ( tautan ) {
		tautan.setAttribute( 'aria-expanded', 'false' );
		tautan.setAttribute( 'aria-controls', 'mega-menu-layanan' );

		tautan.addEventListener( 'click', function ( event ) {
			event.preventDefault();
			var terbuka = ! menu.hidden;
			menu.hidden = terbuka;
			tautan.setAttribute( 'aria-expanded', terbuka ? 'false' : 'true' );
		} );
	} );

	document.addEventListener( 'click', function ( event ) {
		if ( event.target.closest( '.mega-menu__tutup' ) ) {
			tutup();
		}
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' === event.key ) {
			tutup();
		}
	} );
}() );
