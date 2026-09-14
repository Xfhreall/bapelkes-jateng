<?php
/**
 * Footer — dipakai semua halaman.
 * ponytail: struktur link masih array statis. Ganti ke menu WP begitu labelnya final.
 */

$footer_columns = array(
	'Tentang Bapelkes' => array(
		'Profil'              => '/profil/',
		'Sejarah'             => '/profil/#sejarah',
		'Visi & Misi'         => '/profil/#visi-misi',
		'Struktur Organisasi' => '/profil/#struktur-organisasi',
	),
	'Pelatihan'        => array(
		'Jadwal Pelatihan'     => '/layanan/',
		'Daftar Pelatihan'     => '/layanan/#daftar',
		'Pendaftaran Pelatihan' => '/layanan/#pendaftaran',
		'Kampus Pelatihan'     => '/fasilitas/',
		'Fasilitas'            => '/fasilitas/',
	),
	'Informasi'        => array(
		'Berita'   => '/publikasi/',
		'Galeri'   => '/galeri/',
		'Unduhan'  => '/unduhan/',
	),
);

$footer_contacts = array(
	'+6289197278113'     => 'tel:+6289197278113',
	'bapelkes@gmail.com' => 'mailto:bapelkes@gmail.com',
	'LinkedIn'           => '#',
	'Instagram'          => '#',
);
?>

<footer class="site-footer" data-node-id="40:1298">
	<div class="site-footer__bg" aria-hidden="true"></div>
	<p class="site-footer__wordmark" aria-hidden="true">Bapelkes</p>

	<div class="site-footer__inner">
		<a class="site-footer__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<img src="<?php echo esc_url( get_theme_file_uri( 'assets/img/logo-color.png' ) ); ?>"
				alt="<?php bloginfo( 'name' ); ?>" width="113" height="25">
		</a>

		<nav class="site-footer__nav" aria-label="<?php esc_attr_e( 'Tautan footer', 'bapelkes' ); ?>">
			<?php foreach ( $footer_columns as $heading => $links ) : ?>
				<div class="footer-column">
					<h2 class="footer-column__title"><?php echo esc_html( $heading ); ?></h2>
					<ul class="footer-column__links">
						<?php foreach ( $links as $label => $path ) : ?>
							<li>
								<a href="<?php echo esc_url( home_url( $path ) ); ?>">
									<?php echo esc_html( $label ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>

			<div class="footer-column">
				<h2 class="footer-column__title"><?php esc_html_e( 'Hubungi Kami', 'bapelkes' ); ?></h2>
				<ul class="footer-column__links">
					<?php foreach ( $footer_contacts as $label => $href ) : ?>
						<li><a href="<?php echo esc_url( $href ); ?>"><?php echo esc_html( $label ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</nav>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
