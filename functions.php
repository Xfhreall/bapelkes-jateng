<?php
/**
 * Bapelkes Jateng theme setup.
 */

require_once get_theme_file_path( 'inc/masukan.php' );
require_once get_theme_file_path( 'inc/pelatihan.php' );

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

		if ( 'Beranda' === $label && is_front_page() ) {
			$classes[] = 'is-active';
		}

		printf(
			'<li class="%1$s"><a href="%2$s">%3$s</a></li>',
			esc_attr( implode( ' ', $classes ) ),
			esc_url( $url ),
			esc_html( $label )
		);
	}
	echo '</ul>';
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
