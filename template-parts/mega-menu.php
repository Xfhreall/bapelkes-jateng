<?php
/**
 * Mega menu "Layanan" (Figma 40:3366). Dibuka dari tautan Layanan di navbar.
 */

$kampus = bapelkes_kampus();
$menu   = array(
	array(
		'ikon'  => 'calendar-menu.svg',
		'judul' => __( 'Kalender Pelatihan', 'bapelkes' ),
		'ket'   => __( 'Temukan jadwal dan agenda pelatihan kesehatan', 'bapelkes' ),
		'url'   => home_url( '/layanan/' ),
	),
	array(
		'ikon'  => 'building.svg',
		'judul' => __( 'Fasilitas Kampus', 'bapelkes' ),
		'ket'   => __( 'Kenali fasilitas dan lingkungan setiap kampus', 'bapelkes' ),
		'url'   => home_url( '/fasilitas/' ),
	),
);
?>

<div class="mega-menu" id="mega-menu-layanan" hidden data-node-id="40:3366">
	<div class="mega-menu__header">
		<p class="mega-menu__judul"><?php esc_html_e( 'Layanan', 'bapelkes' ); ?></p>
		<button class="mega-menu__tutup" type="button"
			aria-label="<?php esc_attr_e( 'Tutup menu', 'bapelkes' ); ?>">
			<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/x.svg' ) ); ?>"
				alt="" width="24" height="24">
		</button>
	</div>

	<div class="mega-menu__isi">
		<div class="mega-menu__tautan">
			<?php foreach ( $menu as $item ) : ?>
				<a class="mega-menu__item" href="<?php echo esc_url( $item['url'] ); ?>">
					<span class="mega-menu__ikon">
						<img src="<?php echo esc_url( get_theme_file_uri( "assets/icons/{$item['ikon']}" ) ); ?>"
							alt="" width="20" height="20">
					</span>
					<span class="mega-menu__teks">
						<span class="mega-menu__baris">
							<span class="mega-menu__nama"><?php echo esc_html( $item['judul'] ); ?></span>
							<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/chevron-right-sm.svg' ) ); ?>"
								alt="" width="20" height="20">
						</span>
						<span class="mega-menu__ket"><?php echo esc_html( $item['ket'] ); ?></span>
					</span>
				</a>
			<?php endforeach; ?>
		</div>

		<div class="mega-menu__kampus">
			<?php foreach ( $kampus as $slug => $data ) : ?>
				<a class="mega-kampus" href="<?php echo esc_url( home_url( '/fasilitas/' ) ); ?>">
					<span class="mega-kampus__gambar">
						<img src="<?php echo esc_url( get_theme_file_uri( $data['gambar'] ) ); ?>"
							alt="<?php echo esc_attr( $data['nama'] ); ?>">
					</span>
					<span class="mega-kampus__teks">
						<span class="mega-kampus__isi">
							<span class="mega-kampus__nama"><?php echo esc_html( $data['nama'] ); ?></span>
							<span class="mega-kampus__alamat"><?php echo esc_html( $data['alamat'] ); ?></span>
						</span>
						<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/arrow-up-right.svg' ) ); ?>"
							alt="" width="20" height="20">
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</div>
