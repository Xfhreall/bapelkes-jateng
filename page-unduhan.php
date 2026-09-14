<?php
/**
 * Halaman Unduhan - Riwayat Pelatihan (Figma 40:3124).
 * Hanya pelatihan yang sudah selesai, karena materi dan sertifikat baru
 * tersedia setelah pelatihan berakhir.
 */
get_header();

$param_bulan = bapelkes_nilai_rute( 'bulan' );
$bulan       = DateTimeImmutable::createFromFormat( '!Y-m-d', $param_bulan . '-01' );

if ( ! $bulan ) {
	/*
	 * Tanpa parameter bulan, halaman ini akan membuka bulan berjalan yang
	 * biasanya belum punya pelatihan selesai, sehingga pengunjung disambut
	 * daftar kosong. Bulan bawaan diambil dari pelatihan selesai terbaru.
	 */
	$terbaru = get_posts(
		array(
			'post_type'      => 'pelatihan',
			'posts_per_page' => 1,
			'meta_key'       => '_bapelkes_selesai',
			'orderby'        => 'meta_value',
			'order'          => 'DESC',
			'meta_query'     => array(
				array(
					'key'     => '_bapelkes_selesai',
					'value'   => wp_date( 'Y-m-d' ),
					'compare' => '<',
					'type'    => 'DATE',
				),
			),
		)
	);

	$bulan = $terbaru
		? new DateTimeImmutable( get_post_meta( $terbaru[0]->ID, '_bapelkes_selesai', true ) )
		: new DateTimeImmutable( wp_date( 'Y-m-01' ) );

	$bulan = $bulan->modify( 'first day of this month' );
}

$cari     = isset( $_GET['cari'] ) ? sanitize_text_field( wp_unslash( $_GET['cari'] ) ) : '';
$semua    = bapelkes_pelatihan_bulan( $bulan, array(), $cari );
$hari_ini = wp_date( 'Y-m-d' );

$selesai = array_values(
	array_filter(
		$semua,
		static function ( $item ) use ( $hari_ini ) {
			$akhir = get_post_meta( $item->ID, '_bapelkes_selesai', true )
				?: get_post_meta( $item->ID, '_bapelkes_mulai', true );

			return $akhir && $akhir < $hari_ini;
		}
	)
);

$halaman_ini = get_permalink();
$link_bulan  = static function ( DateTimeImmutable $target ) use ( $halaman_ini ) {
	return bapelkes_url_bulan( $halaman_ini, $target );
};
?>

<main class="layanan" data-node-id="40:3141">
	<div class="publikasi__intro">
		<h1 class="publikasi__title">Materi &amp; Sertifikat</h1>
		<p class="publikasi__lead">Akses materi dan sertifikat dari pelatihan yang telah selesai.</p>
	</div>

	<div class="layanan__content layanan__content--penuh">
		<form class="layanan__toolbar" method="get" action="<?php echo esc_url( $halaman_ini ); ?>">
			<input type="hidden" name="bulan" value="<?php echo esc_attr( $bulan->format( 'Y-m' ) ); ?>">

			<span class="search-form search-form--lebar">
				<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/search.svg' ) ); ?>"
					alt="" width="23" height="24">
				<label class="screen-reader-text" for="cari-riwayat">
					<?php esc_html_e( 'Cari pelatihan', 'bapelkes' ); ?>
				</label>
				<input type="search" id="cari-riwayat" name="cari"
					value="<?php echo esc_attr( $cari ); ?>"
					placeholder="<?php esc_attr_e( 'Cari pelatihan untuk akses materi/sertifikat...', 'bapelkes' ); ?>">
			</span>

			<span class="bulan-nav">
				<a class="bulan-nav__tombol bulan-nav__tombol--aktif"
					href="<?php echo esc_url( $link_bulan( $bulan->modify( '-1 month' ) ) ); ?>"
					aria-label="<?php esc_attr_e( 'Bulan sebelumnya', 'bapelkes' ); ?>">
					<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/chevron-left.svg' ) ); ?>"
						alt="" width="24" height="24">
				</a>
				<span class="bulan-nav__label"><?php echo esc_html( wp_date( 'F Y', $bulan->getTimestamp() ) ); ?></span>
				<a class="bulan-nav__tombol bulan-nav__tombol--pasif"
					href="<?php echo esc_url( $link_bulan( $bulan->modify( '+1 month' ) ) ); ?>"
					aria-label="<?php esc_attr_e( 'Bulan berikutnya', 'bapelkes' ); ?>">
					<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/chevron-right.svg' ) ); ?>"
						alt="" width="24" height="24">
				</a>
			</span>
		</form>

		<?php
		get_template_part(
			'template-parts/tabel',
			'pelatihan',
			array(
				'items'  => $selesai,
				'aksi'   => 'unduh',
				'status' => true,
				'kosong' => __( 'Belum ada pelatihan selesai pada bulan ini.', 'bapelkes' ),
			)
		);
		?>
	</div>
</main>

<?php get_footer(); ?>
