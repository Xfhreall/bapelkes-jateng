<?php
/**
 * Beranda.
 * ponytail: teks hero masih hardcode. Pindah ke Customizer/ACF kalau admin perlu mengubahnya sendiri.
 */
get_header();
?>

<section class="hero" data-node-id="40:1195">
	<div class="hero__inner">
		<div class="hero__content">
			<div class="hero__text">
				<h1 class="hero__title">
					Bersama Wujudkan<br>
					<span>SDM Kesehatan Unggul</span>
				</h1>
				<p class="hero__lead">
					Kembangkan kompetensi dan keterampilan Anda melalui berbagai program pelatihan
					kesehatan bersama Bapelkes Jateng.
				</p>
			</div>

			<a class="button--primary" href="<?php echo esc_url( home_url( '/layanan/' ) ); ?>">
				Lihat Jadwal Pelatihan
				<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/arrow-right.svg' ) ); ?>"
					alt="" width="24" height="24">
			</a>

			<hr class="hero__divider">

			<ul class="hero__badges">
				<?php
				$badges = array(
					'file-check' => 'Pendaftaran Mudah',
					'calendar'   => 'Terjadwal',
					'globe'      => 'Sertifikasi Nasional',
				);

				foreach ( $badges as $icon => $label ) :
					?>
					<li class="hero__badge">
						<img src="<?php echo esc_url( get_theme_file_uri( "assets/icons/{$icon}.svg" ) ); ?>"
							alt="" width="20" height="20">
						<?php echo esc_html( $label ); ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>

		<div class="hero__qr">
			<div class="hero__qr-frame">
				<div class="hero__qr-media">
					<img src="<?php echo esc_url( get_theme_file_uri( 'assets/img/qr.png' ) ); ?>"
						alt="<?php esc_attr_e( 'QR code pendaftaran', 'bapelkes' ); ?>">
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_template_part( 'template-parts/section', 'informasi-terbaru' ); ?>

<?php get_template_part( 'template-parts/section', 'kampus' ); ?>

<?php get_footer(); ?>
