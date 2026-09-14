<?php
/**
 * Suara Pembaca: tipe konten "masukan" dan penanganan submit form publik.
 *
 * Form ini terbuka untuk umum, jadi semua input diperlakukan sebagai tidak
 * tepercaya: nonce wajib, honeypot untuk bot sederhana, setiap field
 * disanitasi, dan unggahan dibatasi pada berkas gambar.
 */

defined( 'ABSPATH' ) || exit;

const BAPELKES_MASUKAN_MAX_UPLOAD = 5242880; // 5 MB

function bapelkes_register_masukan() {
	register_post_type(
		'masukan',
		array(
			'labels'          => array(
				'name'          => __( 'Suara Pembaca', 'bapelkes' ),
				'singular_name' => __( 'Masukan', 'bapelkes' ),
				'menu_name'     => __( 'Suara Pembaca', 'bapelkes' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'menu_icon'       => 'dashicons-megaphone',
			'supports'        => array( 'title', 'editor' ),
			'capability_type' => 'post',
			'map_meta_cap'    => true,
		)
	);
}
add_action( 'init', 'bapelkes_register_masukan' );

/**
 * Kolom kontak di daftar admin, supaya staf tidak perlu membuka tiap masukan.
 */
function bapelkes_masukan_columns( $columns ) {
	$columns['kontak'] = __( 'Kontak', 'bapelkes' );
	return $columns;
}
add_filter( 'manage_masukan_posts_columns', 'bapelkes_masukan_columns' );

function bapelkes_masukan_column_content( $column, $post_id ) {
	if ( 'kontak' === $column ) {
		echo esc_html( get_post_meta( $post_id, '_bapelkes_kontak', true ) );
	}
}
add_action( 'manage_masukan_posts_custom_column', 'bapelkes_masukan_column_content', 10, 2 );

/**
 * Menyimpan submit form. Mengembalikan pengunjung ke halaman asal dengan
 * parameter status, tanpa pernah menampilkan kembali input mentah.
 */
function bapelkes_handle_masukan() {
	$kembali = wp_get_referer() ?: home_url( '/suara-pembaca/' );

	if ( ! isset( $_POST['bapelkes_masukan_nonce'] ) ||
		! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['bapelkes_masukan_nonce'] ) ), 'bapelkes_masukan' ) ) {
		wp_safe_redirect( add_query_arg( 'masukan', 'gagal', $kembali ) );
		exit;
	}

	// Honeypot: field ini disembunyikan dari manusia, hanya bot yang mengisinya.
	if ( ! empty( $_POST['situs_web'] ) ) {
		wp_safe_redirect( add_query_arg( 'masukan', 'terkirim', $kembali ) );
		exit;
	}

	$nama   = sanitize_text_field( wp_unslash( $_POST['nama'] ?? '' ) );
	$kontak = sanitize_text_field( wp_unslash( $_POST['kontak'] ?? '' ) );
	$pesan  = sanitize_textarea_field( wp_unslash( $_POST['pesan'] ?? '' ) );

	if ( '' === $nama || '' === $kontak || '' === $pesan ) {
		wp_safe_redirect( add_query_arg( 'masukan', 'kosong', $kembali ) );
		exit;
	}

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'masukan',
			'post_status'  => 'private',
			'post_title'   => sprintf( '%s - %s', $nama, wp_date( 'd F Y H:i' ) ),
			'post_content' => $pesan,
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		wp_safe_redirect( add_query_arg( 'masukan', 'gagal', $kembali ) );
		exit;
	}

	update_post_meta( $post_id, '_bapelkes_kontak', $kontak );

	if ( ! empty( $_FILES['lampiran']['name'] ) ) {
		$lampiran_id = bapelkes_simpan_lampiran( $post_id );

		if ( is_wp_error( $lampiran_id ) ) {
			wp_safe_redirect( add_query_arg( 'masukan', 'lampiran', $kembali ) );
			exit;
		}
	}

	wp_safe_redirect( add_query_arg( 'masukan', 'terkirim', $kembali ) );
	exit;
}
add_action( 'admin_post_nopriv_bapelkes_masukan', 'bapelkes_handle_masukan' );
add_action( 'admin_post_bapelkes_masukan', 'bapelkes_handle_masukan' );

/**
 * Menyimpan lampiran. Hanya gambar, dengan batas ukuran, dan tipe ditentukan
 * dari isi berkas oleh WordPress, bukan dari nama berkas yang dikirim klien.
 */
function bapelkes_simpan_lampiran( $post_id ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$berkas = $_FILES['lampiran'];

	if ( ! empty( $berkas['error'] ) ) {
		return new WP_Error( 'upload_error', __( 'Unggahan gagal.', 'bapelkes' ) );
	}

	if ( $berkas['size'] > BAPELKES_MASUKAN_MAX_UPLOAD ) {
		return new WP_Error( 'upload_besar', __( 'Ukuran berkas melebihi 5 MB.', 'bapelkes' ) );
	}

	$dicek = wp_check_filetype_and_ext( $berkas['tmp_name'], $berkas['name'] );

	if ( empty( $dicek['type'] ) || 0 !== strpos( $dicek['type'], 'image/' ) ) {
		return new WP_Error( 'upload_tipe', __( 'Lampiran harus berupa gambar.', 'bapelkes' ) );
	}

	return media_handle_upload(
		'lampiran',
		$post_id,
		array(),
		array(
			'test_form' => false,
			'mimes'     => array(
				'jpg|jpeg|jpe' => 'image/jpeg',
				'png'          => 'image/png',
				'webp'         => 'image/webp',
			),
		)
	);
}

/**
 * Pesan status setelah submit.
 */
function bapelkes_pesan_masukan() {
	$status = isset( $_GET['masukan'] ) ? sanitize_key( wp_unslash( $_GET['masukan'] ) ) : '';

	$pesan = array(
		'terkirim' => array( 'sukses', __( 'Terima kasih. Masukan Anda sudah kami terima.', 'bapelkes' ) ),
		'kosong'   => array( 'galat', __( 'Nama, kontak, dan masukan wajib diisi.', 'bapelkes' ) ),
		'lampiran' => array( 'galat', __( 'Masukan tersimpan, tetapi lampiran ditolak. Gunakan gambar JPG, PNG, atau WebP maksimal 5 MB.', 'bapelkes' ) ),
		'gagal'    => array( 'galat', __( 'Masukan gagal dikirim. Silakan coba lagi.', 'bapelkes' ) ),
	);

	return $pesan[ $status ] ?? null;
}
