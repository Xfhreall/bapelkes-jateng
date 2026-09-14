<?php
/**
 * Tipe konten "pelatihan" beserta taksonomi kampus dan field tanggal.
 */

defined( 'ABSPATH' ) || exit;

function bapelkes_register_pelatihan() {
	register_post_type(
		'pelatihan',
		array(
			'labels'       => array(
				'name'          => __( 'Pelatihan', 'bapelkes' ),
				'singular_name' => __( 'Pelatihan', 'bapelkes' ),
				'add_new_item'  => __( 'Tambah Pelatihan', 'bapelkes' ),
				'edit_item'     => __( 'Ubah Pelatihan', 'bapelkes' ),
			),
			'public'       => true,
			'has_archive'  => false,
			'menu_icon'    => 'dashicons-calendar-alt',
			'rewrite'      => array( 'slug' => 'pelatihan' ),
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'show_in_rest' => true,
		)
	);

	register_taxonomy(
		'kampus',
		'pelatihan',
		array(
			'labels'            => array(
				'name'          => __( 'Kampus', 'bapelkes' ),
				'singular_name' => __( 'Kampus', 'bapelkes' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'kampus' ),
		)
	);

	foreach ( array( 'mulai', 'selesai' ) as $field ) {
		register_post_meta(
			'pelatihan',
			"_bapelkes_{$field}",
			array(
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'bapelkes_sanitize_tanggal',
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}

	register_post_meta(
		'pelatihan',
		'_bapelkes_angkatan',
		array(
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => function () {
				return current_user_can( 'edit_posts' );
			},
		)
	);
}
add_action( 'init', 'bapelkes_register_pelatihan' );

/**
 * Menerima tanggal hanya dalam format Y-m-d yang benar-benar ada di kalender.
 * "2026-02-31" ditolak, bukan digeser diam-diam ke Maret.
 */
function bapelkes_sanitize_tanggal( $nilai ) {
	$nilai = trim( (string) $nilai );

	if ( '' === $nilai ) {
		return '';
	}

	$tanggal = DateTimeImmutable::createFromFormat( '!Y-m-d', $nilai );

	if ( ! $tanggal || $tanggal->format( 'Y-m-d' ) !== $nilai ) {
		return '';
	}

	return $nilai;
}

/**
 * Kotak isian tanggal dan angkatan di layar edit pelatihan.
 */
function bapelkes_pelatihan_metabox() {
	add_meta_box(
		'bapelkes-jadwal',
		__( 'Jadwal Pelatihan', 'bapelkes' ),
		'bapelkes_pelatihan_metabox_render',
		'pelatihan',
		'side'
	);
}
add_action( 'add_meta_boxes', 'bapelkes_pelatihan_metabox' );

function bapelkes_pelatihan_metabox_render( $post ) {
	wp_nonce_field( 'bapelkes_jadwal', 'bapelkes_jadwal_nonce' );

	$field = array(
		'_bapelkes_mulai'    => array( __( 'Tanggal mulai', 'bapelkes' ), 'date' ),
		'_bapelkes_selesai'  => array( __( 'Tanggal selesai', 'bapelkes' ), 'date' ),
		'_bapelkes_angkatan' => array( __( 'Angkatan', 'bapelkes' ), 'text' ),
	);

	foreach ( $field as $kunci => $info ) {
		printf(
			'<p><label for="%1$s"><strong>%2$s</strong></label><br>
			<input type="%3$s" id="%1$s" name="%1$s" value="%4$s" style="width:100%%"></p>',
			esc_attr( $kunci ),
			esc_html( $info[0] ),
			esc_attr( $info[1] ),
			esc_attr( get_post_meta( $post->ID, $kunci, true ) )
		);
	}
}

function bapelkes_pelatihan_simpan( $post_id ) {
	if ( ! isset( $_POST['bapelkes_jadwal_nonce'] ) ||
		! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['bapelkes_jadwal_nonce'] ) ), 'bapelkes_jadwal' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( array( '_bapelkes_mulai', '_bapelkes_selesai' ) as $kunci ) {
		$nilai = bapelkes_sanitize_tanggal( wp_unslash( $_POST[ $kunci ] ?? '' ) );
		update_post_meta( $post_id, $kunci, $nilai );
	}

	update_post_meta(
		$post_id,
		'_bapelkes_angkatan',
		sanitize_text_field( wp_unslash( $_POST['_bapelkes_angkatan'] ?? '' ) )
	);
}
add_action( 'save_post_pelatihan', 'bapelkes_pelatihan_simpan' );

/**
 * Pelatihan yang jadwalnya bersinggungan dengan satu bulan.
 * Pelatihan yang mulai bulan lalu dan berakhir bulan ini tetap ikut terhitung.
 *
 * @param DateTimeImmutable $bulan  Tanggal mana pun di bulan yang diminta.
 * @param array             $kampus Slug kampus untuk penyaringan, kosong berarti semua.
 * @param string            $cari   Kata kunci pencarian.
 * @return WP_Post[]
 */
function bapelkes_pelatihan_bulan( DateTimeImmutable $bulan, array $kampus = array(), $cari = '' ) {
	$awal  = $bulan->modify( 'first day of this month' )->format( 'Y-m-d' );
	$akhir = $bulan->modify( 'last day of this month' )->format( 'Y-m-d' );

	$args = array(
		'post_type'      => 'pelatihan',
		'posts_per_page' => -1,
		'orderby'        => 'meta_value',
		'meta_key'       => '_bapelkes_mulai',
		'order'          => 'ASC',
		'meta_query'     => array(
			array(
				'key'     => '_bapelkes_mulai',
				'value'   => $akhir,
				'compare' => '<=',
				'type'    => 'DATE',
			),
			array(
				'relation' => 'OR',
				array(
					'key'     => '_bapelkes_selesai',
					'value'   => $awal,
					'compare' => '>=',
					'type'    => 'DATE',
				),
				array(
					'key'     => '_bapelkes_selesai',
					'value'   => '',
					'compare' => '=',
				),
			),
		),
	);

	if ( $kampus ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'kampus',
				'field'    => 'slug',
				'terms'    => $kampus,
			),
		);
	}

	if ( '' !== $cari ) {
		$args['s'] = $cari;
	}

	return get_posts( $args );
}

/**
 * Penanda jenis lampiran: materi atau sertifikat. Muncul sebagai pilihan di
 * jendela media, jadi staf tidak perlu mengandalkan pola nama berkas.
 */
function bapelkes_field_jenis_lampiran( $form_fields, $post ) {
	$nilai = get_post_meta( $post->ID, '_bapelkes_jenis', true );

	$form_fields['bapelkes_jenis'] = array(
		'label' => __( 'Jenis berkas Bapelkes', 'bapelkes' ),
		'input' => 'html',
		'html'  => sprintf(
			'<select name="attachments[%1$d][bapelkes_jenis]" id="attachments-%1$d-bapelkes_jenis">
				<option value="materi" %2$s>Materi</option>
				<option value="sertifikat" %3$s>Sertifikat</option>
			</select>',
			$post->ID,
			selected( $nilai, 'materi', false ),
			selected( $nilai, 'sertifikat', false )
		),
		'helps' => __( 'Menentukan berkas ini masuk daftar materi atau daftar sertifikat.', 'bapelkes' ),
	);

	return $form_fields;
}
add_filter( 'attachment_fields_to_edit', 'bapelkes_field_jenis_lampiran', 10, 2 );

function bapelkes_simpan_jenis_lampiran( $post, $attachment ) {
	if ( isset( $attachment['bapelkes_jenis'] ) ) {
		$jenis = in_array( $attachment['bapelkes_jenis'], array( 'materi', 'sertifikat' ), true )
			? $attachment['bapelkes_jenis']
			: 'materi';

		update_post_meta( $post['ID'], '_bapelkes_jenis', $jenis );
	}

	return $post;
}
add_filter( 'attachment_fields_to_save', 'bapelkes_simpan_jenis_lampiran', 10, 2 );

/**
 * Lampiran satu pelatihan menurut jenis.
 *
 * @param int    $pelatihan_id ID pelatihan.
 * @param string $jenis        'materi' atau 'sertifikat'.
 * @param string $cari         Penyaring nama berkas, dipakai pencarian sertifikat.
 */
function bapelkes_lampiran_pelatihan( $pelatihan_id, $jenis, $cari = '' ) {
	$args = array(
		'post_type'      => 'attachment',
		'post_status'    => 'inherit',
		'post_parent'    => $pelatihan_id,
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
		'meta_query'     => array( array( 'key' => '_bapelkes_jenis', 'value' => $jenis ) ),
	);

	if ( 'materi' === $jenis ) {
		// Lampiran lama tanpa penanda dianggap materi.
		$args['meta_query'] = array(
			'relation' => 'OR',
			array( 'key' => '_bapelkes_jenis', 'value' => 'materi' ),
			array( 'key' => '_bapelkes_jenis', 'compare' => 'NOT EXISTS' ),
		);
	}

	if ( '' !== $cari ) {
		$args['s'] = $cari;
	}

	return get_posts( $args );
}

/**
 * Status pelatihan berdasarkan tanggal hari ini.
 */
function bapelkes_status_pelatihan( $pelatihan_id ) {
	$mulai   = get_post_meta( $pelatihan_id, '_bapelkes_mulai', true );
	$selesai = get_post_meta( $pelatihan_id, '_bapelkes_selesai', true ) ?: $mulai;

	if ( ! $mulai ) {
		return array( 'jadwal', __( 'Belum dijadwalkan', 'bapelkes' ) );
	}

	$hari_ini = wp_date( 'Y-m-d' );

	if ( $hari_ini < $mulai ) {
		return array( 'akan', __( 'Akan datang', 'bapelkes' ) );
	}

	if ( $hari_ini > $selesai ) {
		return array( 'selesai', __( 'Selesai', 'bapelkes' ) );
	}

	return array( 'berlangsung', __( 'Berlangsung', 'bapelkes' ) );
}
