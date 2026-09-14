/**
 * Menampilkan nama berkas yang dipilih pada field lampiran.
 * Tanpa skrip ini form tetap berfungsi; hanya labelnya yang diam.
 */
document.addEventListener( 'change', function ( event ) {
	if ( ! event.target.matches( '.masukan-form__upload input[type="file"]' ) ) {
		return;
	}

	var label = event.target.parentElement.querySelector( '.masukan-form__upload-label' );
	var berkas = event.target.files[ 0 ];

	if ( label ) {
		label.textContent = berkas ? berkas.name : 'Upload foto';
		label.classList.toggle( 'has-file', Boolean( berkas ) );
	}
} );

/**
 * Salinan statis situs ini disajikan dari domain lain dan tidak punya PHP,
 * sehingga pengiriman form pasti gagal. Daripada membiarkan pengunjung
 * mengetik lalu kehilangan isinya, form dinonaktifkan dengan keterangan.
 */
( function () {
	var form = document.querySelector( '.masukan-form[data-asal]' );

	if ( ! form || form.dataset.asal === window.location.hostname ) {
		return;
	}

	form.querySelectorAll( 'input, textarea, button' ).forEach( function ( el ) {
		el.disabled = true;
	} );

	var catatan = document.createElement( 'p' );
	catatan.className = 'masukan-form__status masukan-form__status--galat';
	catatan.textContent = 'Form ini tidak aktif pada pratinjau statis. Di situs sungguhan, masukan tersimpan langsung ke dasbor WordPress.';
	form.prepend( catatan );
}() );
