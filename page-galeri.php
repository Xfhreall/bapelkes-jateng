<?php
/**
 * Halaman Galeri (Figma 40:1587).
 * Gambar diambil dari media library. Unggah gambar ke halaman ini lewat
 * Media > Add New, lalu lampirkan ke halaman Galeri agar urutannya terkendali;
 * tanpa lampiran, template jatuh ke gambar terbaru di media library.
 */
get_header();

$lampiran = get_posts(
	array(
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'post_parent'    => get_the_ID(),
		'posts_per_page' => 15,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
	)
);

if ( ! $lampiran ) {
	$lampiran = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'posts_per_page' => 15,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);
}

/**
 * Lebar kolom per baris sesuai desain. 0 berarti mengisi sisa ruang.
 * Pola berulang setiap tiga baris.
 */
$pola = array(
	array( 268, 0, 186, 0, 261 ),
	array( 186, 0, 334, 273, 193 ),
	array( 0, 0, 268, 186, 261 ),
);

/** Gambar yang dicerminkan di desain, per baris. */
$cermin = array(
	array( false, false, true, false, true ),
	array( false, false, false, true, false ),
	array( false, false, false, true, true ),
);
?>

<main class="galeri">
	<div class="galeri__intro" data-node-id="40:1605">
		<h1 class="galeri__title">Dokumentasi Kegiatan Bapelkes Jateng</h1>
		<div class="galeri__aside">
			<p class="galeri__lead">
				Lihat berbagai kegiatan dan momen pelatihan dalam mendukung peningkatan kompetensi SDM kesehatan.
			</p>
			<a class="button--primary" href="<?php echo esc_url( home_url( '/layanan/' ) ); ?>">
				Daftar Pelatihan
				<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/arrow-right.svg' ) ); ?>"
					alt="" width="24" height="24">
			</a>
		</div>
	</div>

	<?php if ( $lampiran ) : ?>
		<div class="galeri__grid" data-node-id="40:1612">
			<?php foreach ( array_chunk( $lampiran, 5 ) as $index => $baris ) : ?>
				<div class="galeri__row">
					<?php
					$lebar   = $pola[ $index % count( $pola ) ];
					$dicermin = $cermin[ $index % count( $cermin ) ];

					foreach ( $baris as $kolom => $gambar ) :
						$w     = $lebar[ $kolom ] ?? 0;
						$style = $w ? sprintf( 'flex:0 0 %dpx;width:%dpx', $w, $w ) : 'flex:1 1 0;min-width:0';
						$kelas = ! empty( $dicermin[ $kolom ] ) ? ' is-mirrored' : '';
						?>
						<figure class="galeri__item<?php echo esc_attr( $kelas ); ?>"
							style="<?php echo esc_attr( $style ); ?>">
							<?php
							echo wp_get_attachment_image(
								$gambar->ID,
								'large',
								false,
								array( 'alt' => esc_attr( $gambar->post_title ) )
							);
							?>
						</figure>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<p class="publikasi__empty">Belum ada gambar di galeri.</p>
	<?php endif; ?>
</main>

<?php get_footer(); ?>
