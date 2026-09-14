<?php
/**
 * Tabel daftar pelatihan. Dipakai Kalender Pelatihan (Figma 40:1852) dan
 * Riwayat Unduhan (Figma 40:3156).
 *
 * @param WP_Post[] $args['items']  Daftar pelatihan.
 * @param string    $args['aksi']   'panah' atau 'unduh'.
 * @param bool      $args['status'] Tampilkan lencana status.
 * @param string    $args['kosong'] Pesan ketika daftar kosong.
 */

$items  = $args['items'] ?? array();
$aksi   = $args['aksi'] ?? 'panah';
$status = ! empty( $args['status'] );
$kosong = $args['kosong'] ?? __( 'Tidak ada pelatihan.', 'bapelkes' );
?>

<div class="jadwal">
	<?php if ( ! $items ) : ?>
		<p class="jadwal__kosong"><?php echo esc_html( $kosong ); ?></p>
	<?php endif; ?>

	<?php
	foreach ( $items as $item ) :
		$mulai    = get_post_meta( $item->ID, '_bapelkes_mulai', true );
		$selesai  = get_post_meta( $item->ID, '_bapelkes_selesai', true );
		$angkatan = get_post_meta( $item->ID, '_bapelkes_angkatan', true );
		$kampus   = wp_get_post_terms( $item->ID, 'kampus', array( 'fields' => 'names' ) );

		$rentang = $mulai ? wp_date( 'j M', strtotime( $mulai ) ) : '';

		if ( $selesai ) {
			$rentang .= ' - ' . wp_date( 'j M Y', strtotime( $selesai ) );
		} elseif ( $mulai ) {
			$rentang = wp_date( 'j M Y', strtotime( $mulai ) );
		}

		list( $status_slug, $status_label ) = bapelkes_status_pelatihan( $item->ID );
		?>
		<a class="jadwal__baris" href="<?php echo esc_url( get_permalink( $item ) ); ?>"
			data-kampus="<?php echo esc_attr( implode( ' ', wp_get_post_terms( $item->ID, 'kampus', array( 'fields' => 'slugs' ) ) ) ); ?>">
			<span class="jadwal__utama">
				<span class="jadwal__judul"><?php echo esc_html( get_the_title( $item ) ); ?></span>

				<span class="jadwal__tanggal">
					<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/calendar-table.svg' ) ); ?>"
						alt="" width="20" height="20">
					<?php echo esc_html( $rentang ); ?>

					<?php if ( $status ) : ?>
						<span class="unduhan__status unduhan__status--<?php echo esc_attr( $status_slug ); ?>">
							<?php echo esc_html( $status_label ); ?>
						</span>
					<?php endif; ?>
				</span>

				<?php if ( 'unduh' === $aksi ) : ?>
					<span class="jadwal__aksi">
						<span><?php esc_html_e( 'Unduh Materi/Sertifikat', 'bapelkes' ); ?></span>
						<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/arrow-up-right.svg' ) ); ?>"
							alt="" width="24" height="24">
					</span>
				<?php else : ?>
					<img class="jadwal__panah"
						src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/arrow-up-right.svg' ) ); ?>"
						alt="" width="24" height="24">
				<?php endif; ?>
			</span>

			<span class="jadwal__meta">
				<span><?php echo esc_html( $kampus ? $kampus[0] : '' ); ?></span>
				<?php if ( $angkatan ) : ?>
					<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/dot.svg' ) ); ?>"
						alt="" width="4" height="4">
					<span><?php echo esc_html( $angkatan ); ?></span>
				<?php endif; ?>
			</span>
		</a>
	<?php endforeach; ?>
</div>
