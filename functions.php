<?php
/**
 * Bapelkes Jateng theme setup.
 */

require_once get_theme_file_path( 'inc/masukan.php' );
require_once get_theme_file_path( 'inc/pelatihan.php' );
require_once get_theme_file_path( 'inc/konten-halaman.php' );
require_once get_theme_file_path( 'inc/fasilitas.php' );
require_once get_theme_file_path( 'inc/rute.php' );

function bapelkes_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );

	register_nav_menus(
		array(
			'primary' => __( 'Menu Utama', 'bapelkes' ),
		)
	);
}
add_action( 'after_setup_theme', 'bapelkes_setup' );

function bapelkes_assets() {
	wp_enqueue_style(
		'bapelkes-fonts',
		'https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&family=Open+Sans:wght@400;600&family=Geist+Mono:wght@500&family=Instrument+Serif:ital@1&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'bapelkes-style',
		get_stylesheet_uri(),
		array( 'bapelkes-fonts' ),
		filemtime( get_stylesheet_directory() . '/style.css' )
	);

	wp_enqueue_script(
		'bapelkes-menu',
		get_theme_file_uri( 'assets/js/menu.js' ),
		array(),
		filemtime( get_theme_file_path( 'assets/js/menu.js' ) ),
		true
	);

	if ( is_page_template( 'page-layanan.php' ) || is_page( 'layanan' ) || is_page( 'unduhan' ) || is_singular( 'pelatihan' ) ) {
		wp_enqueue_script(
			'bapelkes-saring',
			get_theme_file_uri( 'assets/js/saring.js' ),
			array(),
			filemtime( get_theme_file_path( 'assets/js/saring.js' ) ),
			true
		);
	}

	if ( is_singular( 'pelatihan' ) ) {
		wp_enqueue_script(
			'bapelkes-unduhan',
			get_theme_file_uri( 'assets/js/unduhan.js' ),
			array(),
			filemtime( get_theme_file_path( 'assets/js/unduhan.js' ) ),
			true
		);
	}

	if ( is_page_template( 'page-suara-pembaca.php' ) || is_page( 'suara-pembaca' ) ) {
		wp_enqueue_script(
			'bapelkes-form',
			get_theme_file_uri( 'assets/js/form.js' ),
			array(),
			filemtime( get_theme_file_path( 'assets/js/form.js' ) ),
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'bapelkes_assets' );

/**
 * Menu bawaan selama menu Appearance > Menus belum dibuat.
 * ponytail: link statis, ganti dengan menu asli begitu strukturnya final.
 */
function bapelkes_default_menu() {
	$items = array(
		'Beranda'          => home_url( '/' ),
		'Profil'           => home_url( '/profil/' ),
		'Publikasi'        => home_url( '/publikasi/' ),
		'Layanan'          => home_url( '/layanan/' ),
		'Galeri'           => home_url( '/galeri/' ),
		'Unduhan'          => home_url( '/unduhan/' ),
		'Suara Pembaca'    => home_url( '/suara-pembaca/' ),
		'Pelayanan Publik' => home_url( '/pelayanan-publik/' ),
	);

	$dropdowns = array( 'Layanan', 'Pelayanan Publik' );

	echo '<ul class="nav-links">';
	foreach ( $items as $label => $url ) {
		$classes = array();

		if ( in_array( $label, $dropdowns, true ) ) {
			$classes[] = 'menu-item-has-children';
		}

		$aktif = bapelkes_menu_aktif( $label );

		if ( $aktif ) {
			$classes[] = 'is-active';
		}

		printf(
			'<li class="%1$s"><a href="%2$s"%3$s>%4$s</a></li>',
			esc_attr( implode( ' ', $classes ) ),
			esc_url( $url ),
			$aktif ? ' aria-current="page"' : '',
			esc_html( $label )
		);
	}
	echo '</ul>';
}

/**
 * Menentukan item menu mana yang sedang aktif.
 *
 * Bukan sekadar mencocokkan alamat: satu item mewakili beberapa halaman.
 * Publikasi tetap menyala saat membaca satu berita atau arsip kategori,
 * dan Layanan tetap menyala di halaman satu pelatihan maupun fasilitas
 * kampus, karena keduanya cabang dari menu itu.
 */
function bapelkes_menu_aktif( $label ) {
	switch ( $label ) {
		case 'Beranda':
			return is_front_page();

		case 'Profil':
			return is_page( 'profil' );

		case 'Publikasi':
			return is_home() || is_singular( 'post' ) || is_category() || is_search();

		case 'Layanan':
			return is_page( 'layanan' )
				|| is_singular( 'pelatihan' )
				|| is_page( 'fasilitas' )
				|| is_tax( 'kampus' );

		case 'Galeri':
			return is_page( 'galeri' );

		case 'Unduhan':
			return is_page( 'unduhan' );

		case 'Suara Pembaca':
			return is_page( 'suara-pembaca' );

		case 'Pelayanan Publik':
			return is_page( 'pelayanan-publik' ) || is_page( 'standar-pelayanan' );
	}

	return false;
}

/**
 * Data tiga kampus. Dipakai section Beranda dan halaman Fasilitas.
 * ponytail: array statis — hanya tiga dan jarang berubah.
 */
function bapelkes_kampus() {
	return array(
		'gombong'  => array(
			'nama'   => 'Kampus Gombong',
			'alamat' => 'Jl. Yos Sudarso 461, Gombong, Kab. Kebumen, Jawa Tengah.',
			'gambar' => 'assets/img/kampus-gombong.jpg',
		),
		'wonosobo' => array(
			'nama'   => 'Kampus Wonosobo',
			'alamat' => "Jl. KH. Hasyim Asy'ari Km. 03, Kalibeber, Kecamatan Mojotengah, Kabupaten Wonosobo.",
			'gambar' => 'assets/img/kampus-wonosobo.jpg',
		),
		'ungaran'  => array(
			'nama'   => 'Kampus Ungaran',
			'alamat' => 'Jl. Diponegoro No. 186, Gedanganak / Candirejo, Ungaran Timur/Barat, Kab. Semarang.',
			'gambar' => 'assets/img/kampus-ungaran.jpg',
		),
	);
}

/**
 * Pita penanda demo. Hanya tampil bila BAPELKES_DEMO aktif di wp-config,
 * sehingga pemasangan produksi tidak pernah menampilkannya.
 *
 * Isi situs ini masih data contoh, bukan data resmi Bapelkes. Pita ini
 * mencegah pengunjung menyangka halaman ini publikasi resmi.
 */
function bapelkes_pita_demo() {
	if ( ! defined( 'BAPELKES_DEMO' ) || ! BAPELKES_DEMO || is_admin() ) {
		return;
	}

	printf(
		'<p class="pita-demo">%s</p>',
		esc_html__( 'PRATINJAU PENGEMBANGAN — seluruh berita, nama peserta, sertifikat, dan angka di situs ini adalah data contoh, bukan data resmi Bapelkes Jateng.', 'bapelkes' )
	);
}
add_action( 'wp_body_open', 'bapelkes_pita_demo', 1 );

/**
 * Menandai body saat mode demo, agar ruang untuk pita hanya disediakan
 * ketika pitanya memang ada.
 */
function bapelkes_kelas_demo( $kelas ) {
	if ( defined( 'BAPELKES_DEMO' ) && BAPELKES_DEMO ) {
		$kelas[] = 'mode-demo';
	}

	return $kelas;
}
add_filter( 'body_class', 'bapelkes_kelas_demo' );

