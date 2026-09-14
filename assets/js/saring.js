/**
 * Penyaringan berbasis parameter URL.
 *
 * Status penyaring disimpan di URL, bukan di dalam halaman. Alamat hasil
 * penyaringan bisa disalin, di-bookmark, dan dibuka ulang dengan hasil yang
 * sama. Penyaring hanya berlaku setelah form dikirim, sesuai tombol Terapkan
 * di desain; mengetik atau mencentang saja tidak mengubah apa pun.
 *
 * Di hosting PHP, WordPress yang menyaring dari parameter yang sama. Di
 * salinan statis parameter diabaikan server, lalu skrip ini menyaring baris
 * yang sudah tampil. Hasil akhirnya sama di kedua tempat.
 */
( function () {
	var params = new URLSearchParams( window.location.search );

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

	/* ---------- Daftar pelatihan ---------- */

	var jadwal = document.querySelector( '.jadwal' );

	if ( jadwal ) {
		var baris = Array.prototype.slice.call( jadwal.querySelectorAll( '.jadwal__baris' ) );
		var kotakCari = document.querySelector( '#cari-pelatihan, #cari-riwayat' );
		var centang = Array.prototype.slice.call(
			document.querySelectorAll( '.filter-pelatihan__check input[type="checkbox"]' )
		);

		var kataURL = params.get( 'cari' ) || '';
		var kampusURL = params.getAll( 'kampus[]' ).concat( params.getAll( 'kampus' ) );

		// Kendali form disesuaikan dengan URL, karena salinan statis selalu
		// mengirim halaman bawaan tanpa mengetahui parameter.
		if ( kotakCari && kataURL ) {
			kotakCari.value = kataURL;
		}

		centang.forEach( function ( c ) {
			c.checked = kampusURL.indexOf( c.value ) !== -1;
		} );

		var kata = normal( kataURL );
		var tampil = 0;

		baris.forEach( function ( b ) {
			var cocokKata = ! kata || normal( b.textContent ).indexOf( kata ) !== -1;
			var milik = ( b.getAttribute( 'data-kampus' ) || '' ).split( ' ' );
			var cocokKampus = ! kampusURL.length || kampusURL.some( function ( k ) {
				return milik.indexOf( k ) !== -1;
			} );

			var lolos = cocokKata && cocokKampus;
			b.hidden = ! lolos;

			if ( lolos ) {
				tampil++;
			}
		} );

		pesanKosong( jadwal, 0 === tampil && baris.length > 0, 'Tidak ada pelatihan yang cocok.' );

		/**
		 * Kotak cari dan daftar kampus berada di dua form terpisah. Mengirim
		 * salah satunya akan menghapus pilihan yang lain, jadi pengiriman
		 * ditahan lalu alamat disusun dari kedua form sekaligus.
		 */
		function kirim( event ) {
			event.preventDefault();

			var url = new URLSearchParams();
			var nilaiCari = kotakCari ? kotakCari.value.trim() : '';

			if ( nilaiCari ) {
				url.set( 'cari', nilaiCari );
			}

			centang.forEach( function ( c ) {
				if ( c.checked ) {
					url.append( 'kampus[]', c.value );
				}
			} );

			var tanya = url.toString();
			window.location.assign( window.location.pathname + ( tanya ? '?' + tanya : '' ) );
		}

		Array.prototype.forEach.call(
			document.querySelectorAll( '.layanan__toolbar, .filter-pelatihan' ),
			function ( form ) {
				form.addEventListener( 'submit', kirim );
			}
		);

		// Tautan pindah bulan ikut membawa penyaring yang sedang aktif.
		if ( window.location.search ) {
			Array.prototype.forEach.call(
				document.querySelectorAll( '.bulan-nav a, .kalender__nav a' ),
				function ( a ) {
					a.setAttribute( 'href', a.getAttribute( 'href' ) + window.location.search );
				}
			);
		}
	}

	/* ---------- Daftar sertifikat ---------- */

	var daftarSertifikat = document.querySelector( '.sertifikat' );
	var kotakNama = document.querySelector( '#cari-nama' );

	if ( daftarSertifikat && kotakNama ) {
		var kartu = Array.prototype.slice.call( daftarSertifikat.querySelectorAll( '.sertifikat-card' ) );
		var paginasi = document.querySelector( '.paginasi' );
		var namaURL = params.get( 'nama' ) || '';

		if ( namaURL ) {
			kotakNama.value = namaURL;
		}

		var kataNama = normal( namaURL );
		var tampilNama = 0;

		kartu.forEach( function ( k ) {
			var lolos = ! kataNama || normal( k.textContent ).indexOf( kataNama ) !== -1;
			k.hidden = ! lolos;

			if ( lolos ) {
				tampilNama++;
			}
		} );

		/* Saat menyaring, paginasi menyesatkan: yang tersaring hanya halaman ini. */
		if ( paginasi ) {
			paginasi.hidden = kataNama.length > 0;
		}

		pesanKosong(
			daftarSertifikat.parentElement,
			0 === tampilNama && kartu.length > 0,
			'Tidak ada nama itu di halaman ini. Coba halaman lain.'
		);

		kotakNama.form.addEventListener( 'submit', function ( event ) {
			event.preventDefault();
			var nilai = kotakNama.value.trim();
			window.location.assign(
				window.location.pathname + ( nilai ? '?nama=' + encodeURIComponent( nilai ) : '' )
			);
		} );
	}
}() );
