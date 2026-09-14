<?php
/**
 * Field konten per template halaman.
 *
 * Halaman Profil, Fasilitas, dan Pelayanan Publik punya tata letak tetap
 * sesuai desain, tetapi teksnya harus bisa diubah admin tanpa menyentuh kode.
 * Tiap template mendaftarkan daftar field di sini, lalu template memanggil
 * bapelkes_konten() yang jatuh ke teks bawaan desain bila belum diisi.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Definisi field per template.
 *
 * Tipe: teks, area, daftar (satu item per baris),
 * tabel (satu baris per item, kolom dipisah |), gambar (ID lampiran),
 * galeri (beberapa ID lampiran), url.
 */
function bapelkes_field_halaman() {
	return array(
		'page-profil.php' => array(
			'visi_prefix'      => array( 'teks', 'Judul visi (miring, bergradien)' ),
			'visi_teks'        => array( 'area', 'Kalimat visi' ),
			'misi_gambar'      => array( 'gambar', 'Foto latar misi' ),
			'misi'             => array( 'daftar', 'Misi, satu per baris' ),
			'program_judul'    => array( 'teks', 'Judul bagian program' ),
			'program'          => array( 'daftar', 'Program unggulan, satu per baris' ),
			'pimpinan_judul'   => array( 'teks', 'Judul bagian pimpinan (miring)' ),
			'pimpinan_sub'     => array( 'teks', 'Anak judul pimpinan' ),
			'pimpinan_ket'     => array( 'area', 'Keterangan pimpinan' ),
			'pimpinan_gambar'  => array( 'gambar', 'Latar kartu pimpinan' ),
			'sejarah_judul'    => array( 'teks', 'Judul bagian sejarah' ),
			'sejarah'          => array( 'tabel', 'Sejarah: Tahun | Judul | Keterangan' ),
			'tupoksi_judul'    => array( 'teks', 'Judul tugas pokok (miring)' ),
			'tupoksi_sub'      => array( 'teks', 'Anak judul tugas pokok' ),
			'tupoksi_tombol'   => array( 'teks', 'Teks tombol dokumen' ),
			'tupoksi_url'      => array( 'url', 'Tautan dokumen lengkap' ),
			'tupoksi_dokumen'  => array( 'galeri', 'Halaman dokumen (gambar)' ),
			'struktur_judul'   => array( 'area', 'Judul struktur organisasi' ),
			'struktur_gambar'  => array( 'gambar', 'Bagan struktur organisasi' ),
		),

		'page-pelayanan-publik.php' => array(
			'pp_judul'            => array( 'teks', 'Judul (kata miring bergradien)' ),
			'pp_judul_sisa'       => array( 'teks', 'Sisa judul' ),
			'pp_lead'             => array( 'area', 'Kalimat pengantar' ),
			'ikm_label'           => array( 'teks', 'Label indeks' ),
			'ikm_nilai'           => array( 'teks', 'Nilai IKM' ),
			'ikm_mutu'            => array( 'teks', 'Mutu pelayanan' ),
			'ikm_ket'             => array( 'area', 'Keterangan di bawah nilai' ),
			'ikm_grafik_judul'    => array( 'teks', 'Judul grafik' ),
			'ikm_unsur'           => array( 'tabel', 'Unsur penilaian: Nama | Nilai' ),
			'responden_total'     => array( 'teks', 'Jumlah responden' ),
			'responden_perempuan' => array( 'teks', 'Responden perempuan' ),
			'responden_laki'      => array( 'teks', 'Responden laki-laki' ),
			'maklumat_gambar'     => array( 'gambar', 'Gambar maklumat pelayanan' ),
			'maklumat_latar'      => array( 'gambar', 'Foto latar maklumat' ),
		),
	);
}

function bapelkes_daftar_field_template( $template ) {
	$semua = bapelkes_field_halaman();

	return $semua[ $template ] ?? array();
}

/**
 * Membaca konten halaman. Mengembalikan $bawaan bila belum pernah diisi,
 * sehingga halaman tetap tampil sesuai desain sejak awal.
 *
 * @param string $kunci   Nama field.
 * @param mixed  $bawaan  Nilai bawaan dari desain.
 * @param int    $post_id Halaman, default halaman saat ini.
 */
function bapelkes_konten( $kunci, $bawaan = '', $post_id = 0 ) {
	$post_id = $post_id ?: get_the_ID();
	$nilai   = get_post_meta( $post_id, "_bapelkes_{$kunci}", true );

	if ( '' === $nilai || array() === $nilai || null === $nilai ) {
		return $bawaan;
	}

	return $nilai;
}

/**
 * Konten bertipe daftar: satu item per baris.
 */
function bapelkes_konten_daftar( $kunci, array $bawaan = array(), $post_id = 0 ) {
	$mentah = bapelkes_konten( $kunci, '', $post_id );

	if ( '' === $mentah ) {
		return $bawaan;
	}

	$baris = array_map( 'trim', explode( "\n", $mentah ) );

	return array_values( array_filter( $baris, 'strlen' ) );
}

/**
 * Konten bertipe tabel: satu baris per item, kolom dipisah tanda |.
 */
function bapelkes_konten_tabel( $kunci, array $bawaan = array(), $post_id = 0 ) {
	$baris = bapelkes_konten_daftar( $kunci, array(), $post_id );

	if ( ! $baris ) {
		return $bawaan;
	}

	return array_map(
		static function ( $item ) {
			return array_map( 'trim', explode( '|', $item ) );
		},
		$baris
	);
}

function bapelkes_metabox_konten() {
	foreach ( array_keys( bapelkes_field_halaman() ) as $template ) {
		add_meta_box(
			'bapelkes-konten',
			__( 'Konten Halaman', 'bapelkes' ),
			'bapelkes_metabox_konten_render',
			'page',
			'normal',
			'high',
			array( 'template' => $template )
		);
	}
}
add_action( 'add_meta_boxes_page', 'bapelkes_metabox_konten' );

function bapelkes_metabox_konten_render( $post, $kotak ) {
	$template = get_page_template_slug( $post->ID );
	$field    = bapelkes_daftar_field_template( $template );

	if ( ! $field ) {
		printf(
			'<p>%s</p>',
			esc_html__( 'Halaman ini memakai template bawaan. Pilih template khusus untuk mengisi konten terstruktur.', 'bapelkes' )
		);
		return;
	}

	wp_nonce_field( 'bapelkes_konten', 'bapelkes_konten_nonce' );

	foreach ( $field as $kunci => $info ) {
		list( $tipe, $label ) = $info;
		$nama  = "_bapelkes_{$kunci}";
		$nilai = get_post_meta( $post->ID, $nama, true );

		printf( '<p><label for="%s"><strong>%s</strong></label><br>', esc_attr( $nama ), esc_html( $label ) );

		if ( in_array( $tipe, array( 'gambar', 'galeri' ), true ) ) {
			printf(
				'<input type="text" class="widefat bapelkes-media" id="%1$s" name="%1$s" value="%2$s" data-multiple="%3$s">
				<button type="button" class="button bapelkes-pilih-media" data-target="%1$s">%4$s</button>
				<span class="description">%5$s</span>',
				esc_attr( $nama ),
				esc_attr( $nilai ),
				'galeri' === $tipe ? '1' : '0',
				esc_html__( 'Pilih dari media', 'bapelkes' ),
				esc_html__( 'Berisi ID lampiran.', 'bapelkes' )
			);
		} elseif ( 'teks' === $tipe || 'url' === $tipe ) {
			printf(
				'<input type="%1$s" class="widefat" id="%2$s" name="%2$s" value="%3$s">',
				'url' === $tipe ? 'url' : 'text',
				esc_attr( $nama ),
				esc_attr( $nilai )
			);
		} else {
			printf(
				'<textarea class="widefat" rows="%1$d" id="%2$s" name="%2$s">%3$s</textarea>',
				'area' === $tipe ? 3 : 8,
				esc_attr( $nama ),
				esc_textarea( $nilai )
			);
		}

		echo '</p>';
	}
}

function bapelkes_simpan_konten( $post_id ) {
	if ( ! isset( $_POST['bapelkes_konten_nonce'] ) ||
		! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['bapelkes_konten_nonce'] ) ), 'bapelkes_konten' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$field = bapelkes_daftar_field_template( get_page_template_slug( $post_id ) );

	foreach ( $field as $kunci => $info ) {
		$nama = "_bapelkes_{$kunci}";

		if ( ! isset( $_POST[ $nama ] ) ) {
			continue;
		}

		$mentah = wp_unslash( $_POST[ $nama ] );

		switch ( $info[0] ) {
			case 'url':
				$bersih = esc_url_raw( $mentah );
				break;
			case 'gambar':
				$bersih = (string) absint( $mentah );
				break;
			case 'galeri':
				$bersih = implode( ',', array_filter( array_map( 'absint', explode( ',', $mentah ) ) ) );
				break;
			case 'teks':
				$bersih = sanitize_text_field( $mentah );
				break;
			default:
				$bersih = sanitize_textarea_field( $mentah );
		}

		update_post_meta( $post_id, $nama, $bersih );
	}
}
add_action( 'save_post_page', 'bapelkes_simpan_konten' );

function bapelkes_admin_media_script( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_script(
		'bapelkes-admin',
		get_theme_file_uri( 'assets/js/admin.js' ),
		array( 'jquery' ),
		filemtime( get_theme_file_path( 'assets/js/admin.js' ) ),
		true
	);
}
add_action( 'admin_enqueue_scripts', 'bapelkes_admin_media_script' );

/**
 * Tipe konten pimpinan: nama pada judul, jabatan atau periode pada ringkasan,
 * foto pada gambar unggulan. Urutan mengikuti kolom Urutan.
 */
function bapelkes_register_pimpinan() {
	register_post_type(
		'pimpinan',
		array(
			'labels'      => array(
				'name'          => __( 'Pimpinan', 'bapelkes' ),
				'singular_name' => __( 'Pimpinan', 'bapelkes' ),
				'add_new_item'  => __( 'Tambah Pimpinan', 'bapelkes' ),
			),
			'public'      => false,
			'show_ui'     => true,
			'menu_icon'   => 'dashicons-groups',
			'supports'    => array( 'title', 'excerpt', 'thumbnail', 'page-attributes' ),
			'has_archive' => false,
		)
	);
}
add_action( 'init', 'bapelkes_register_pimpinan' );
