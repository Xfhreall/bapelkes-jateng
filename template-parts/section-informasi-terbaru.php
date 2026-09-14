<?php
/**
 * Section "Informasi Terbaru" (Figma 40:1235).
 * Empat post terbaru. Post tanpa featured image menampilkan placeholder abu.
 */

$berita = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 4,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

if ( ! $berita->have_posts() ) {
	return;
}
?>

<section class="news" data-node-id="40:1235">
	<div class="news__header">
		<p class="news__eyebrow"><?php esc_html_e( 'Informasi Terbaru', 'bapelkes' ); ?></p>

		<div class="news__intro">
			<h2 class="news__title">
				<?php esc_html_e( 'Ikuti perkembangan terbaru mengenai pelatihan dan kegiatan Bapelkes Jateng', 'bapelkes' ); ?>
			</h2>

			<a class="news__more" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/publikasi/' ) ); ?>">
				<span><?php esc_html_e( 'Lihat Semua', 'bapelkes' ); ?></span>
				<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/arrow-up-right.svg' ) ); ?>"
					alt="" width="24" height="24">
			</a>
		</div>
	</div>

	<div class="post-grid post-grid--single-row">
		<?php
		while ( $berita->have_posts() ) :
			$berita->the_post();
			get_template_part( 'template-parts/card', 'post' );
		endwhile;
		wp_reset_postdata();
		?>
	</div>
</section>
