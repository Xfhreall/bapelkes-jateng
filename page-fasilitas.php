<?php
/**
 * Halaman Fasilitas (Figma 40:2349).
 */
get_header();

$kampus = bapelkes_kampus();
$term_kampus = get_terms( array( 'taxonomy' => 'kampus', 'hide_empty' => false ) );
$arrow  = get_theme_file_uri( 'assets/icons/arrow-up-right-lg.svg' );
?>

<main class="fasilitas">
	<div class="fasilitas__intro" data-node-id="40:2367">
		<h1 class="fasilitas__title">
			Lihat beragam fasilitas di setiap kampus untuk menemukan lingkungan belajar yang nyaman dan sesuai untukmu.
		</h1>
	</div>

	<div class="fasilitas__grid" data-node-id="40:2369">
		<?php foreach ( $kampus as $slug => $data ) : ?>
			<?php
			$term = get_term_by( 'name', $data['nama'], 'kampus' );
			$tautan = $term && ! is_wp_error( $term ) ? get_term_link( $term ) : home_url( '/fasilitas/' );
			?>
			<a class="fasilitas-card" href="<?php echo esc_url( $tautan ); ?>">
				<span class="fasilitas-card__head">
					<span class="fasilitas-card__text">
						<span class="fasilitas-card__nama"><?php echo esc_html( $data['nama'] ); ?></span>
						<span class="fasilitas-card__alamat"><?php echo esc_html( $data['alamat'] ); ?></span>
					</span>
					<img src="<?php echo esc_url( $arrow ); ?>" alt="" width="24" height="24">
				</span>
				<span class="fasilitas-card__media fasilitas-card__media--<?php echo esc_attr( $slug ); ?>">
					<img src="<?php echo esc_url( get_theme_file_uri( $data['gambar'] ) ); ?>"
						alt="<?php echo esc_attr( $data['nama'] ); ?>">
				</span>
			</a>
		<?php endforeach; ?>
	</div>
</main>

<?php get_footer(); ?>
