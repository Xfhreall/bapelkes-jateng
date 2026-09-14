/**
 * Penyaringan di sisi klien untuk pencarian dan filter kampus.
 *
 * Versi server tetap ada dan tetap jadi acuan: tanpa JavaScript, form
 * dikirim seperti biasa dan WordPress yang menyaring. Skrip ini hanya
 * menahan pengiriman lalu menyaring baris yang sudah tampil, sehingga
 * halaman juga berfungsi ketika disajikan sebagai berkas statis.
 */
( function () {
	function normal( teks ) {
		return ( teks || '' ).toLowerCase().trim();
	}

	function pesanKosong( wadah, tampil, teks ) {
		var pesan = wadah.querySelector( '.saring-kosong' );

		if ( ! tampil ) {
			if ( pesan ) {
				pesan.remove();
			}
			return;
		}

		if ( ! pesan ) {
			pesan = document.createElement( 'p' );
			pesan.className = 'jadwal__kosong saring-kosong';
			wadah.appendChild( pesan );
		}

		pesan.textContent = teks;
	}

	/* ---------- Daftar pelatihan: pencarian + filter kampus ---------- */

	var jadwal = document.querySelector( '.jadwal' );

	if ( jadwal ) {
		var baris = Array.prototype.slice.call( jadwal.querySelectorAll( '.jadwal__baris' ) );
		var kotakCari = document.querySelector( '#cari-pelatihan, #cari-riwayat' );
		var centang = Array.prototype.slice.call(
			document.querySelectorAll( '.filter-pelatihan__check input[type="checkbox"]' )
		);

		var saring = function () {
			var kata = kotakCari ? normal( kotakCari.value ) : '';
			var kampus = centang.filter( function ( c ) {
				return c.checked;
			} ).map( function ( c ) {
				return c.value;
			} );

			var tampil = 0;

			baris.forEach( function ( b ) {
				var cocokKata = ! kata || normal( b.textContent ).indexOf( kata ) !== -1;
				var milik = ( b.getAttribute( 'data-kampus' ) || '' ).split( ' ' );
				var cocokKampus = ! kampus.length || kampus.some( function ( k ) {
					return milik.indexOf( k ) !== -1;
				} );

				var lolos = cocokKata && cocokKampus;
				b.hidden = ! lolos;

				if ( lolos ) {
					tampil++;
				}
			} );

			pesanKosong( jadwal, 0 === tampil && baris.length > 0, 'Tidak ada pelatihan yang cocok.' );
		};

		if ( kotakCari ) {
			kotakCari.addEventListener( 'input', saring );
		}

		centang.forEach( function ( c ) {
			c.addEventListener( 'change', saring );
		} );

		Array.prototype.forEach.call( document.querySelectorAll( '.layanan__toolbar, .filter-pelatihan' ), function ( form ) {
			form.addEventListener( 'submit', function ( event ) {
				event.preventDefault();
				saring();
			} );
		} );
	}

	/* ---------- Daftar sertifikat: pencarian nama ---------- */

	var daftarSertifikat = document.querySelector( '.sertifikat' );
	var kotakNama = document.querySelector( '#cari-nama' );

	if ( daftarSertifikat && kotakNama ) {
		var kartu = Array.prototype.slice.call( daftarSertifikat.querySelectorAll( '.sertifikat-card' ) );
		var paginasi = document.querySelector( '.paginasi' );

		var saringNama = function () {
			var kata = normal( kotakNama.value );
			var tampil = 0;

			kartu.forEach( function ( k ) {
				var lolos = ! kata || normal( k.textContent ).indexOf( kata ) !== -1;
				k.hidden = ! lolos;

				if ( lolos ) {
					tampil++;
				}
			} );

			/* Saat menyaring, paginasi menyesatkan: yang tersaring hanya halaman ini. */
			if ( paginasi ) {
				paginasi.hidden = kata.length > 0;
			}

			pesanKosong(
				daftarSertifikat.parentElement,
				0 === tampil && kartu.length > 0,
				'Tidak ada nama itu di halaman ini. Coba halaman lain.'
			);
		};

		kotakNama.addEventListener( 'input', saringNama );

		kotakNama.form.addEventListener( 'submit', function ( event ) {
			event.preventDefault();
			saringNama();
		} );
	}
}() );
