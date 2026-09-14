<?php
/**
 * Tipe konten fasilitas: satu ruang atau sarana, berfoto, milik satu kampus
 * dan satu kelompok (Akademik & Administrasi, Penunjang & Hunian, dan seterusnya).
 */

defined( 'ABSPATH' ) || exit;

function bapelkes_register_fasilitas() {
	register_post_type(
		'fasilitas',
		array(
			'labels'      => array(
				'name'          => __( 'Fasilitas', 'bapelkes' ),
				'singular_name' => __( 'Fasilitas', 'bapelkes' ),
				'add_new_item'  => __( 'Tambah Fasilitas', 'bapelkes' ),
			),
			'public'      => true,
			'has_archive' => false,
			'menu_icon'   => 'dashicons-building',
			'supports'    => array( 'title', 'thumbnail', 'page-attributes' ),
			'taxonomies'  => array( 'kampus' ),
			'rewrite'     => array( 'slug' => 'ruang' ),
		)
	);

	register_taxonomy(
		'kelompok',
		'fasilitas',
		array(
			'labels'            => array(
				'name'          => __( 'Kelompok Fasilitas', 'bapelkes' ),
				'singular_name' => __( 'Kelompok Fasilitas', 'bapelkes' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'kelompok-fasilitas' ),
		)
	);
}
add_action( 'init', 'bapelkes_register_fasilitas' );

/**
 * Taksonomi kampus dipakai bersama pelatihan dan fasilitas.
 */
function bapelkes_kampus_untuk_fasilitas() {
	register_taxonomy_for_object_type( 'kampus', 'fasilitas' );
}
add_action( 'init', 'bapelkes_kampus_untuk_fasilitas', 11 );

/**
 * Fasilitas satu kampus, dikelompokkan menurut taksonomi kelompok.
 * Kelompok tanpa isi tidak dikembalikan.
 *
 * @return array<int, array{term: WP_Term, items: WP_Post[]}>
 */
function bapelkes_fasilitas_per_kelompok( $kampus_id ) {
	$kelompok = get_terms(
		array(
			'taxonomy'   => 'kelompok',
			'hide_empty' => false,
			'orderby'    => 'term_order',
		)
	);

	if ( is_wp_error( $kelompok ) ) {
		return array();
	}

	$hasil = array();

	foreach ( $kelompok as $term ) {
		$items = get_posts(
			array(
				'post_type'      => 'fasilitas',
				'posts_per_page' => -1,
				'orderby'        => 'menu_order title',
				'order'          => 'ASC',
				'tax_query'      => array(
					'relation' => 'AND',
					array( 'taxonomy' => 'kampus', 'field' => 'term_id', 'terms' => $kampus_id ),
					array( 'taxonomy' => 'kelompok', 'field' => 'term_id', 'terms' => $term->term_id ),
				),
			)
		);

		if ( $items ) {
			$hasil[] = array( 'term' => $term, 'items' => $items );
		}
	}

	return $hasil;
}
