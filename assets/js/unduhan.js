/**
 * Pop up konfirmasi setelah menekan tombol unduh (Figma 40:3314 dan 40:3325).
 * Unduhan tetap berjalan lewat atribut download; pop up hanya pemberitahuan.
 */
( function () {
	var popup = document.getElementById( 'popup-unduhan' );

	if ( ! popup ) {
		return;
	}

	var judul = popup.querySelector( '.popup__judul' );

	function tutup() {
		popup.hidden = true;
	}

	document.addEventListener( 'click', function ( event ) {
		var pemicu = event.target.closest( '[data-unduhan]' );

		if ( pemicu ) {
			judul.textContent = 'sertifikat' === pemicu.dataset.unduhan
				? 'Berhasil Mengunduh Sertifikat'
				: 'Berhasil Mengunduh Materi';
			popup.hidden = false;
			return;
		}

		if ( event.target.closest( '.popup__tutup' ) || event.target === popup ) {
			tutup();
		}
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' === event.key ) {
			tutup();
		}
	} );
}() );
