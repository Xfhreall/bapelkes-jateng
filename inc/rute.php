<?php
/**
 * Rute berbasis path untuk hal-hal yang semula memakai parameter URL.
 *
 * Hosting statis melayani berkas menurut path dan mengabaikan tanda tanya,
 * sehingga /layanan/?bulan=2026-10 selalu menghasilkan halaman yang sama.
 * Dengan /layanan/2026-10/ tiap bulan punya berkas sendiri dan tetap bisa
 * dirayapi. Parameter lama tetap diterima agar tautan lama tidak putus.
 */

defined( 'ABSPATH' ) || exit;

function bapelkes_query_vars( $vars ) {
	$vars[] = 'bapelkes_bulan';
	$vars[] = 'bapelkes_doc';
	$vars[] = 'bapelkes_hal';

	return $vars;
}
add_filter( 'query_vars', 'bapelkes_query_vars' );

function bapelkes_rute() {
	add_rewrite_rule(
		'^layanan/(\d{4}-\d{2})/?$',
		'index.php?pagename=layanan&bapelkes_bulan=$matches[1]',
		'top'
	);

	add_rewrite_rule(
		'^unduhan/(\d{4}-\d{2})/?$',
		'index.php?pagename=unduhan&bapelkes_bulan=$matches[1]',
		'top'
	);

	add_rewrite_rule(
		'^profil/dokumen/(\d+)/?$',
		'index.php?pagename=profil&bapelkes_doc=$matches[1]',
		'top'
	);

	add_rewrite_rule(
		'^pelatihan/([^/]+)/hal/(\d+)/?$',
		'index.php?pelatihan=$matches[1]&bapelkes_hal=$matches[2]',
		'top'
	);
}
add_action( 'init', 'bapelkes_rute' );

/**
 * Membaca nilai dari path lebih dulu, lalu parameter lama sebagai cadangan.
 */
function bapelkes_nilai_rute( $nama, $bawaan = '' ) {
	$dari_path = get_query_var( "bapelkes_{$nama}" );

	if ( '' !== $dari_path && null !== $dari_path ) {
		return $dari_path;
	}

	return isset( $_GET[ $nama ] ) ? sanitize_text_field( wp_unslash( $_GET[ $nama ] ) ) : $bawaan;
}

/**
 * Membangun tautan bulan sebagai path.
 */
function bapelkes_url_bulan( $dasar, DateTimeImmutable $bulan ) {
	return trailingslashit( trailingslashit( $dasar ) . $bulan->format( 'Y-m' ) );
}
