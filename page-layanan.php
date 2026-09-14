<?php
/**
 * Halaman Layanan - Kalender Pelatihan (Figma 40:1668).
 */
get_header();

// Bulan yang ditampilkan. Parameter tidak valid jatuh ke bulan berjalan.
$param_bulan = bapelkes_nilai_rute( 'bulan' );
$bulan       = DateTimeImmutable::createFromFormat( '!Y-m-d', $param_bulan . '-01' );

if ( ! $bulan ) {
	$bulan = new DateTimeImmutable( wp_date( 'Y-m-01' ) );
}

$kampus_dipilih = isset( $_GET['kampus'] ) ? array_map( 'sanitize_title', (array) wp_unslash( $_GET['kampus'] ) ) : array();
$cari           = isset( $_GET['cari'] ) ? sanitize_text_field( wp_unslash( $_GET['cari'] ) ) : '';

$pelatihan     = bapelkes_pelatihan_bulan( $bulan, $kampus_dipilih, $cari );
$semua_kampus  = get_terms( array( 'taxonomy' => 'kampus', 'hide_empty' => false ) );
$sebulan_penuh = bapelkes_pelatihan_bulan( $bulan );

// Tanggal mulai dan rentang, untuk menandai kalender.
$hari_mulai   = array();
$hari_rentang = array();

foreach ( $sebulan_penuh as $item ) {
	$mulai   = get_post_meta( $item->ID, '_bapelkes_mulai', true );
	$selesai = get_post_meta( $item->ID, '_bapelkes_selesai', true ) ?: $mulai;

	if ( ! $mulai ) {
		continue;
	}

	$kursor = new DateTimeImmutable( $mulai );
	$batas  = new DateTimeImmutable( $selesai );

	if ( $kursor->format( 'Y-m' ) === $bulan->format( 'Y-m' ) ) {
		$hari_mulai[ (int) $kursor->format( 'j' ) ] = true;
	}

	while ( $kursor <= $batas ) {
		if ( $kursor->format( 'Y-m' ) === $bulan->format( 'Y-m' ) ) {
			$hari_rentang[ (int) $kursor->format( 'j' ) ] = true;
		}
		$kursor = $kursor->modify( '+1 day' );
	}
}

$halaman_ini = get_permalink();
// Tautan bulan memakai path agar tiap bulan punya alamat sendiri.
$link_bulan  = static function ( DateTimeImmutable $target ) use ( $halaman_ini ) {
	return bapelkes_url_bulan( $halaman_ini, $target );
};

$jumlah_hari  = (int) $bulan->format( 't' );
$offset_awal  = (int) $bulan->modify( 'first day of this month' )->format( 'w' );
$nama_bulan   = wp_date( 'F Y', $bulan->getTimestamp() );
?>

<main class="layanan" data-node-id="40:1686">
	<div class="publikasi__intro">
		<h1 class="publikasi__title"><em>Kalender</em> Pelatihan</h1>
		<p class="publikasi__lead">Temukan jadwal dan agenda pelatihan kesehatan yang tersedia</p>
	</div>

	<div class="layanan__body">
		<aside class="layanan__sidebar">
			<div class="kalender">
				<div class="kalender__header">
					<p class="kalender__bulan"><?php echo esc_html( wp_date( 'M Y', $bulan->getTimestamp() ) ); ?></p>
					<span class="kalender__nav">
						<a href="<?php echo esc_url( $link_bulan( $bulan->modify( '-1 month' ) ) ); ?>"
							aria-label="<?php esc_attr_e( 'Bulan sebelumnya', 'bapelkes' ); ?>">
							<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/chevron-left.svg' ) ); ?>"
								alt="" width="24" height="24">
						</a>
						<a href="<?php echo esc_url( $link_bulan( $bulan->modify( '+1 month' ) ) ); ?>"
							aria-label="<?php esc_attr_e( 'Bulan berikutnya', 'bapelkes' ); ?>">
							<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/chevron-right.svg' ) ); ?>"
								alt="" width="24" height="24">
						</a>
					</span>
				</div>

				<hr class="kalender__garis">

				<div class="kalender__grid">
					<?php foreach ( array( 'Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa' ) as $hari ) : ?>
						<span class="kalender__weekday"><?php echo esc_html( $hari ); ?></span>
					<?php endforeach; ?>

					<?php for ( $i = 0; $i < $offset_awal; $i++ ) : ?>
						<span class="kalender__day kalender__day--kosong"></span>
					<?php endfor; ?>

					<?php
					for ( $tanggal = 1; $tanggal <= $jumlah_hari; $tanggal++ ) :
						$kelas = 'kalender__day';

						if ( isset( $hari_mulai[ $tanggal ] ) ) {
							$kelas .= ' is-mulai';
						} elseif ( isset( $hari_rentang[ $tanggal ] ) ) {
							$kelas .= ' is-rentang';
						}
						?>
						<span class="<?php echo esc_attr( $kelas ); ?>">
							<span class="kalender__day-number"><?php echo esc_html( $tanggal ); ?></span>
						</span>
					<?php endfor; ?>
				</div>
			</div>

			<form class="filter-pelatihan" method="get" action="<?php echo esc_url( $halaman_ini ); ?>">
				<input type="hidden" name="bulan" value="<?php echo esc_attr( $bulan->format( 'Y-m' ) ); ?>">

				<div class="filter-pelatihan__header">
					<p class="filter-pelatihan__judul"><?php esc_html_e( 'Filter Pelatihan', 'bapelkes' ); ?></p>
					<a class="filter-pelatihan__reset"
						href="<?php echo esc_url( bapelkes_url_bulan( $halaman_ini, $bulan ) ); ?>">
						<?php esc_html_e( 'Reset', 'bapelkes' ); ?>
					</a>
				</div>

				<hr class="kalender__garis">

				<fieldset class="filter-pelatihan__grup">
					<legend><?php esc_html_e( 'Lokasi Kampus', 'bapelkes' ); ?></legend>

					<?php
					foreach ( $semua_kampus as $term ) :
						$jumlah = 0;

						foreach ( $sebulan_penuh as $item ) {
							if ( has_term( $term->term_id, 'kampus', $item ) ) {
								$jumlah++;
							}
						}
						?>
						<label class="filter-pelatihan__baris">
							<span class="filter-pelatihan__check">
								<input type="checkbox" name="kampus[]"
									value="<?php echo esc_attr( $term->slug ); ?>"
									<?php checked( in_array( $term->slug, $kampus_dipilih, true ) ); ?>>
								<span><?php echo esc_html( $term->name ); ?></span>
							</span>
							<span class="filter-pelatihan__jumlah"><?php echo esc_html( $jumlah ); ?></span>
						</label>
					<?php endforeach; ?>
				</fieldset>

				<button class="button--primary filter-pelatihan__submit" type="submit">
					<?php esc_html_e( 'Terapkan', 'bapelkes' ); ?>
				</button>
			</form>
		</aside>

		<div class="layanan__content">
			<form class="layanan__toolbar" method="get" action="<?php echo esc_url( $halaman_ini ); ?>">
				<input type="hidden" name="bulan" value="<?php echo esc_attr( $bulan->format( 'Y-m' ) ); ?>">
				<?php foreach ( $kampus_dipilih as $slug ) : ?>
					<input type="hidden" name="kampus[]" value="<?php echo esc_attr( $slug ); ?>">
				<?php endforeach; ?>

				<span class="search-form search-form--lebar">
					<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/search.svg' ) ); ?>"
						alt="" width="23" height="24">
					<label class="screen-reader-text" for="cari-pelatihan">
						<?php esc_html_e( 'Cari pelatihan', 'bapelkes' ); ?>
					</label>
					<input type="search" id="cari-pelatihan" name="cari"
						value="<?php echo esc_attr( $cari ); ?>"
						placeholder="<?php esc_attr_e( 'Cari pelatihan...', 'bapelkes' ); ?>">
				</span>

				<span class="bulan-nav">
					<a class="bulan-nav__tombol bulan-nav__tombol--pasif"
						href="<?php echo esc_url( $link_bulan( $bulan->modify( '-1 month' ) ) ); ?>"
						aria-label="<?php esc_attr_e( 'Bulan sebelumnya', 'bapelkes' ); ?>">
						<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/chevron-left.svg' ) ); ?>"
							alt="" width="24" height="24">
					</a>
					<span class="bulan-nav__label"><?php echo esc_html( $nama_bulan ); ?></span>
					<a class="bulan-nav__tombol bulan-nav__tombol--aktif"
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
					'items'  => $pelatihan,
					'kosong' => __( 'Tidak ada pelatihan pada bulan ini.', 'bapelkes' ),
				)
			);
			?>
		</div>
	</div>
</main>

<?php get_footer(); ?>
