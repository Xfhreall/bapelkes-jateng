<?php
/**
 * Detail Fasilitas satu kampus (Figma 40:1978).
 * Akordion memakai elemen details bawaan HTML, jadi tetap bisa dibuka-tutup
 * tanpa JavaScript.
 */
get_header();

$kampus   = get_queried_object();
$kelompok = bapelkes_fasilitas_per_kelompok( $kampus->term_id );
$intro    = term_description( $kampus );
?>

<main class="detail-fasilitas" data-node-id="40:1996">
	<h1 class="detail-fasilitas__judul">
		<?php if ( $intro ) : ?>
			<?php echo wp_kses_post( $intro ); ?>
		<?php else : ?>
			Jelajahi berbagai fasilitas di <em><?php echo esc_html( $kampus->name ); ?></em>
			yang dirancang untuk mendukung pembelajaran, aktivitas mahasiswa, serta kenyamanan
			seluruh sivitas akademika.
		<?php endif; ?>
	</h1>

	<?php if ( ! $kelompok ) : ?>
		<p class="jadwal__kosong"><?php esc_html_e( 'Belum ada fasilitas terdaftar untuk kampus ini.', 'bapelkes' ); ?></p>
	<?php endif; ?>

	<?php foreach ( $kelompok as $urutan => $grup ) : ?>
		<details class="fasilitas-grup" <?php echo 0 === $urutan ? 'open' : ''; ?>>
			<summary class="fasilitas-grup__ringkas">
				<span class="fasilitas-grup__label"><?php esc_html_e( 'Fasilitas', 'bapelkes' ); ?></span>

				<span class="fasilitas-grup__teks">
					<span class="fasilitas-grup__baris">
						<span class="fasilitas-grup__nama"><?php echo esc_html( $grup['term']->name ); ?></span>
						<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/chevron-down.svg' ) ); ?>"
							alt="" width="24" height="24">
					</span>
					<span class="fasilitas-grup__ket"><?php echo esc_html( $grup['term']->description ); ?></span>
				</span>
			</summary>

			<div class="fasilitas-grid">
				<?php foreach ( $grup['items'] as $ruang ) : ?>
					<figure class="fasilitas-item">
						<span class="fasilitas-item__gambar">
							<?php
							if ( has_post_thumbnail( $ruang ) ) {
								echo get_the_post_thumbnail( $ruang, 'large', array( 'alt' => esc_attr( get_the_title( $ruang ) ) ) );
							}
							?>
						</span>
						<figcaption><?php echo esc_html( get_the_title( $ruang ) ); ?></figcaption>
					</figure>
				<?php endforeach; ?>
			</div>
		</details>
	<?php endforeach; ?>
</main>

<?php get_footer(); ?>
