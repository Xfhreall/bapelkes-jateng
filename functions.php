<?php
/**
 * Bapelkes Jateng theme setup.
 */

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
		'https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'bapelkes-style',
		get_stylesheet_uri(),
		array( 'bapelkes-fonts' ),
		filemtime( get_stylesheet_directory() . '/style.css' )
	);
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
