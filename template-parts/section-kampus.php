<?php
/**
 * Section headline + tiga kampus (Figma 40:1272 dan 40:1275).
 * Data kampus berasal dari bapelkes_kampus() agar sama dengan halaman Fasilitas.
 */

$kampus     = bapelkes_kampus();
$utama      = $kampus['gombong'];
$pendamping = array( 'wonosobo' => $kampus['wonosobo'], 'ungaran' => $kampus['ungaran'] );
$kampus_url = home_url( '/fasilitas/' );
$arrow      = get_theme_file_uri( 'assets/icons/arrow-up-right-lg.svg' );
?>

<section class="wordmark-head" data-node-id="40:1272">
	<p class="wordmark-head__line">Bapelkes Jateng</p>
	<p class="wordmark-head__line wordmark-head__line--right">
		Hadir di <em>Tiga Kampus</em>
	</p>
</section>

<section class="kampus" data-node-id="40:1275">
	<a class="kampus__lead" href="<?php echo esc_url( $kampus_url ); ?>">
		<span class="kampus__lead-title">
			<span><?php echo esc_html( $utama['nama'] ); ?></span>
			<img src="<?php echo esc_url( $arrow ); ?>" alt="" width="32" height="32">
		</span>
		<span class="kampus__lead-address"><?php echo esc_html( $utama['alamat'] ); ?></span>
	</a>

	<div class="kampus__grid">
		<div class="kampus__main">
			<img src="<?php echo esc_url( get_theme_file_uri( $utama['gambar'] ) ); ?>"
				alt="<?php echo esc_attr( $utama['nama'] ); ?>" width="567" height="481">
		</div>

		<div class="kampus__side">
			<?php foreach ( $pendamping as $slug => $data ) : ?>
				<a class="kampus-card" href="<?php echo esc_url( $kampus_url ); ?>">
					<span class="kampus-card__media kampus-card__media--<?php echo esc_attr( $slug ); ?>">
						<img src="<?php echo esc_url( get_theme_file_uri( $data['gambar'] ) ); ?>"
							alt="<?php echo esc_attr( $data['nama'] ); ?>">
					</span>
					<span class="kampus-card__text">
						<span class="kampus-card__title">
							<span><?php echo esc_html( $data['nama'] ); ?></span>
							<img src="<?php echo esc_url( $arrow ); ?>" alt="" width="32" height="32">
						</span>
						<span class="kampus-card__address"><?php echo esc_html( $data['alamat'] ); ?></span>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
