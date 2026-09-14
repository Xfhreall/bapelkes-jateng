/**
 * Pemilih media untuk field gambar dan galeri pada kotak Konten Halaman.
 */
jQuery( function ( $ ) {
	$( document ).on( 'click', '.bapelkes-pilih-media', function ( event ) {
		event.preventDefault();

		var tombol = $( this );
		var input = $( '#' + tombol.data( 'target' ) );
		var banyak = '1' === String( input.data( 'multiple' ) );

		var pustaka = wp.media( {
			title: banyak ? 'Pilih beberapa berkas' : 'Pilih berkas',
			multiple: banyak,
			library: { type: 'image' }
		} );

		pustaka.on( 'select', function () {
			var terpilih = pustaka.state().get( 'selection' ).map( function ( item ) {
				return item.id;
			} );

			input.val( terpilih.join( ',' ) );
		} );

		pustaka.open();
	} );
} );
