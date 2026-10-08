<?php

const BAPELKES_IG_API = 'https://graph.facebook.com/v26.0';

function bapelkes_ig_daftar_pengaturan() {
	register_setting( 'general', 'bapelkes_ig_token', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	register_setting(
		'general',
		'bapelkes_ig_akun',
		array(
			'sanitize_callback' => 'sanitize_user',
			'default'           => 'bapelkesjateng',
		)
	);

	add_settings_field(
		'bapelkes_ig_token',
		'Token akses Meta (Instagram)',
		function () {
			printf(
				'<input type="password" name="bapelkes_ig_token" value="%s" class="regular-text" autocomplete="off">',
				esc_attr( get_option( 'bapelkes_ig_token' ) )
			);
		},
		'general'
	);

	add_settings_field(
		'bapelkes_ig_akun',
		'Akun Instagram sumber berita',
		function () {
			printf(
				'@<input type="text" name="bapelkes_ig_akun" value="%s" class="regular-text">',
				esc_attr( get_option( 'bapelkes_ig_akun', 'bapelkesjateng' ) )
			);
		},
		'general'
	);
}
add_action( 'admin_init', 'bapelkes_ig_daftar_pengaturan' );

function bapelkes_ig_minta( $jalur, $kueri ) {
	$respons = wp_remote_get(
		add_query_arg(
			array_merge( $kueri, array( 'access_token' => get_option( 'bapelkes_ig_token' ) ) ),
			BAPELKES_IG_API . $jalur
		),
		array( 'timeout' => 20 )
	);

	if ( is_wp_error( $respons ) ) {
		return $respons;
	}

	$data = json_decode( wp_remote_retrieve_body( $respons ), true );

	if ( isset( $data['error'] ) ) {
		return new WP_Error( 'bapelkes_ig_api', $data['error']['message'] ?? 'Galat API Instagram' );
	}

	return $data;
}

function bapelkes_ig_ambil() {
	if ( ! get_option( 'bapelkes_ig_token' ) ) {
		return new WP_Error( 'bapelkes_ig_token', 'Token Instagram belum diisi di Pengaturan > Umum.' );
	}

	$halaman = bapelkes_ig_minta( '/me/accounts', array( 'fields' => 'instagram_business_account' ) );

	if ( is_wp_error( $halaman ) ) {
		$halaman = bapelkes_ig_minta( '/me', array( 'fields' => 'instagram_business_account' ) );

		if ( is_wp_error( $halaman ) ) {
			return $halaman;
		}

		$halaman = array( 'data' => array( $halaman ) );
	}

	$ig_id = current( array_filter( array_column( array_column( $halaman['data'] ?? array(), 'instagram_business_account' ), 'id' ) ) );

	if ( ! $ig_id ) {
		return new WP_Error( 'bapelkes_ig_akun', 'Tidak ada Page yang terhubung ke akun Instagram profesional untuk token ini.' );
	}

	$akun  = get_option( 'bapelkes_ig_akun', 'bapelkesjateng' );
	$hasil = bapelkes_ig_minta(
		'/' . $ig_id,
		array(
			'fields' => sprintf(
				'business_discovery.username(%s){media.limit(25){id,caption,media_type,media_url,thumbnail_url,permalink,timestamp,children{media_type,media_url,thumbnail_url}}}',
				$akun
			),
		)
	);

	if ( is_wp_error( $hasil ) ) {
		return $hasil;
	}

	return $hasil['business_discovery']['media']['data'] ?? array();
}

function bapelkes_ig_gambar( $media ) {
	if ( 'CAROUSEL_ALBUM' === ( $media['media_type'] ?? '' ) && ! empty( $media['children']['data'] ) ) {
		$media = $media['children']['data'][0];
	}

	if ( 'VIDEO' === ( $media['media_type'] ?? '' ) ) {
		return $media['thumbnail_url'] ?? '';
	}

	return $media['media_url'] ?? '';
}

function bapelkes_ig_petakan( $media ) {
	$caption = trim( $media['caption'] ?? '' );
	$daftar  = array_values( array_filter( array_map( 'trim', explode( "\n", $caption ) ) ) );
	$isi     = array_values( array_filter( $daftar, fn( $b ) => ! preg_match( '/^(hall?o|hai|hi)\b/i', $b ) ) );
	$baris   = $isi[0] ?? ( $daftar[0] ?? '' );

	if ( preg_match( '/\bkegiatan\s+(.+?)(?:\s+kerja\s*sama\b|\s+bersama\b|\.|$)/iu', $baris, $cocok ) ) {
		$baris = $cocok[1];
	}

	$judul = $baris ? wp_trim_words( $baris, 12, '…' ) : 'Unggahan Instagram';

	return array(
		'post_title'    => $judul,
		'post_content'  => wpautop( esc_html( $caption ) ) . sprintf(
			'<p><a href="%s" target="_blank" rel="noopener noreferrer">Lihat di Instagram</a></p>',
			esc_url( $media['permalink'] ?? '' )
		),
		'post_date_gmt' => gmdate( 'Y-m-d H:i:s', strtotime( $media['timestamp'] ?? 'now' ) ),
		'gambar'        => bapelkes_ig_gambar( $media ),
	);
}

function bapelkes_ig_sinkron() {
	$daftar = bapelkes_ig_ambil();

	if ( is_wp_error( $daftar ) ) {
		error_log( 'Sinkron Instagram gagal: ' . $daftar->get_error_message() );
		return $daftar;
	}

	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$baru = 0;

	foreach ( array_reverse( $daftar ) as $media ) {
		$ada = get_posts(
			array(
				'post_type'   => 'post',
				'post_status' => 'any',
				'meta_key'    => '_ig_id',
				'meta_value'  => $media['id'],
				'fields'      => 'ids',
			)
		);

		if ( $ada ) {
			continue;
		}

		$post   = bapelkes_ig_petakan( $media );
		$gambar = $post['gambar'];
		unset( $post['gambar'] );

		$post_id = wp_insert_post(
			$post + array(
				'post_type'   => 'post',
				'post_status' => 'publish',
				'post_date'   => get_date_from_gmt( $post['post_date_gmt'] ),
				'meta_input'  => array( '_ig_id' => $media['id'] ),
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			error_log( 'Sinkron Instagram: ' . $post_id->get_error_message() );
			continue;
		}

		if ( $gambar ) {
			$lampiran = media_sideload_image( $gambar, $post_id, $post['post_title'], 'id' );

			if ( is_wp_error( $lampiran ) ) {
				error_log( 'Sinkron Instagram: gambar ' . $media['id'] . ' gagal, ' . $lampiran->get_error_message() );
			} else {
				set_post_thumbnail( $post_id, $lampiran );
			}
		}

		++$baru;
	}

	return $baru;
}

add_action( 'bapelkes_ig_sinkron_harian', 'bapelkes_ig_sinkron' );

function bapelkes_ig_jadwalkan() {
	if ( ! wp_next_scheduled( 'bapelkes_ig_sinkron_harian' ) ) {
		wp_schedule_event( time(), 'daily', 'bapelkes_ig_sinkron_harian' );
	}
}
add_action( 'init', 'bapelkes_ig_jadwalkan' );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command(
		'bapelkes ig-sinkron',
		function () {
			$hasil = bapelkes_ig_sinkron();

			if ( is_wp_error( $hasil ) ) {
				WP_CLI::error( $hasil->get_error_message() );
			}

			WP_CLI::success( "{$hasil} post baru dari Instagram." );
		}
	);
}
