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
