<?php
/**
 * Section headline + tiga kampus (Figma 40:1272 dan 40:1275).
 * ponytail: data kampus statis — hanya tiga dan jarang berubah. Jadikan CPT
 * kalau nanti tiap kampus butuh halaman sendiri.
 */

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
			<span>Kampus Gombong</span>
			<img src="<?php echo esc_url( $arrow ); ?>" alt="" width="32" height="32">
		</span>
		<span class="kampus__lead-address">
			Jl. Yos Sudarso 461, Gombong, Kab. Kebumen, Jawa Tengah.
		</span>
	</a>

	<div class="kampus__grid">
		<div class="kampus__main">
			<img src="<?php echo esc_url( get_theme_file_uri( 'assets/img/kampus-gombong.jpg' ) ); ?>"
				alt="Kampus Gombong" width="567" height="481">
		</div>

		<div class="kampus__side">
			<a class="kampus-card" href="<?php echo esc_url( $kampus_url ); ?>">
				<span class="kampus-card__media kampus-card__media--wonosobo">
					<img src="<?php echo esc_url( get_theme_file_uri( 'assets/img/kampus-wonosobo.jpg' ) ); ?>"
						alt="Kampus Wonosobo">
				</span>
				<span class="kampus-card__text">
					<span class="kampus-card__title">
						<span>Kampus Wonosobo</span>
						<img src="<?php echo esc_url( $arrow ); ?>" alt="" width="32" height="32">
					</span>
					<span class="kampus-card__address">
						Jl. KH. Hasyim Asy'ari Km. 03, Kalibeber, Kecamatan Mojotengah, Kabupaten Wonosobo.
					</span>
				</span>
			</a>

			<a class="kampus-card" href="<?php echo esc_url( $kampus_url ); ?>">
				<span class="kampus-card__media kampus-card__media--ungaran">
					<img src="<?php echo esc_url( get_theme_file_uri( 'assets/img/kampus-ungaran.jpg' ) ); ?>"
						alt="Kampus Ungaran">
				</span>
				<span class="kampus-card__text">
					<span class="kampus-card__title">
						<span>Kampus Ungaran</span>
						<img src="<?php echo esc_url( $arrow ); ?>" alt="" width="32" height="32">
					</span>
					<span class="kampus-card__address">
						Jl. Diponegoro No. 186, Gedanganak / Candirejo, Ungaran Timur/Barat, Kab. Semarang.
					</span>
				</span>
			</a>
		</div>
	</div>
</section>
