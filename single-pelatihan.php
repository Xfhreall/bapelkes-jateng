<?php
/**
 * Unduhan Materi & Sertifikat satu pelatihan (Figma 40:2872).
 */
get_header();

while ( have_posts() ) :
	the_post();

	$id       = get_the_ID();
	$mulai    = get_post_meta( $id, '_bapelkes_mulai', true );
	$selesai  = get_post_meta( $id, '_bapelkes_selesai', true );
	list( $status_slug, $status_label ) = bapelkes_status_pelatihan( $id );

	$cari_nama = isset( $_GET['nama'] ) ? sanitize_text_field( wp_unslash( $_GET['nama'] ) ) : '';
	$materi    = bapelkes_lampiran_pelatihan( $id, 'materi' );
	$sertifikat = bapelkes_lampiran_pelatihan( $id, 'sertifikat', $cari_nama );

	// Paginasi sertifikat: 12 kartu per halaman seperti desain.
	$per_halaman = 12;
	$halaman     = max( 1, isset( $_GET['hal'] ) ? absint( wp_unslash( $_GET['hal'] ) ) : 1 );
	$total_hal   = max( 1, (int) ceil( count( $sertifikat ) / $per_halaman ) );
	$halaman     = min( $halaman, $total_hal );
	$tampil      = array_slice( $sertifikat, ( $halaman - 1 ) * $per_halaman, $per_halaman );

	$link_hal = static function ( $nomor ) use ( $cari_nama ) {
		$args = array( 'hal' => $nomor );

		if ( '' !== $cari_nama ) {
			$args['nama'] = $cari_nama;
		}

		return add_query_arg( $args, get_permalink() );
	};

	$rentang = $mulai ? wp_date( 'j M', strtotime( $mulai ) ) : '';

	if ( $selesai ) {
		$rentang .= ' - ' . wp_date( 'j M Y', strtotime( $selesai ) );
	} elseif ( $mulai ) {
		$rentang = wp_date( 'j M Y', strtotime( $mulai ) );
	}
	?>

	<main class="unduhan" data-node-id="40:2889">
		<h1 class="unduhan__judul"><?php the_title(); ?></h1>

		<p class="unduhan__meta">
			<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/calendar-table.svg' ) ); ?>"
				alt="" width="19" height="20">
			<span><?php echo esc_html( $rentang ); ?></span>
			<span class="unduhan__status unduhan__status--<?php echo esc_attr( $status_slug ); ?>">
				<?php echo esc_html( $status_label ); ?>
			</span>
		</p>

		<section class="unduhan__blok">
			<h2 class="unduhan__subjudul"><?php esc_html_e( 'List Materi', 'bapelkes' ); ?></h2>

			<?php if ( $materi ) : ?>
				<div class="materi">
					<?php
					foreach ( $materi as $berkas ) :
						$ext  = strtolower( pathinfo( get_attached_file( $berkas->ID ), PATHINFO_EXTENSION ) );
						$ikon = in_array( $ext, array( 'ppt', 'pptx' ), true ) ? 'ikon-pptx.png' : 'ikon-pdf.png';
						?>
						<a class="materi-card" href="<?php echo esc_url( wp_get_attachment_url( $berkas->ID ) ); ?>"
							download data-unduhan="materi">
							<span class="materi-card__ikon">
								<img src="<?php echo esc_url( get_theme_file_uri( "assets/img/{$ikon}" ) ); ?>"
									alt="" width="32" height="32">
							</span>
							<span class="materi-card__teks">
								<span class="materi-card__nama"><?php echo esc_html( get_the_title( $berkas ) . '.' . $ext ); ?></span>
								<span class="materi-card__ket"><?php echo esc_html( get_the_title( $berkas ) ); ?></span>
							</span>
							<img class="materi-card__unduh"
								src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/download.svg' ) ); ?>"
								alt="" width="24" height="24">
						</a>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<p class="jadwal__kosong"><?php esc_html_e( 'Belum ada materi diunggah.', 'bapelkes' ); ?></p>
			<?php endif; ?>
		</section>

		<section class="unduhan__blok">
			<div class="unduhan__blok-header">
				<h2 class="unduhan__subjudul"><?php esc_html_e( 'List Sertifikat', 'bapelkes' ); ?></h2>

				<form class="search-form" method="get" action="<?php echo esc_url( get_permalink() ); ?>">
					<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/search.svg' ) ); ?>"
						alt="" width="23" height="24">
					<label class="screen-reader-text" for="cari-nama">
						<?php esc_html_e( 'Cari nama peserta', 'bapelkes' ); ?>
					</label>
					<input type="search" id="cari-nama" name="nama"
						value="<?php echo esc_attr( $cari_nama ); ?>"
						placeholder="<?php esc_attr_e( 'Cari namamu...', 'bapelkes' ); ?>">
				</form>
			</div>

			<?php if ( $tampil ) : ?>
				<div class="sertifikat">
					<?php foreach ( $tampil as $berkas ) : ?>
						<div class="sertifikat-card">
							<div class="sertifikat-card__pratinjau">
								<?php if ( wp_attachment_is_image( $berkas->ID ) ) : ?>
									<?php echo wp_get_attachment_image( $berkas->ID, 'medium', false, array( 'alt' => '' ) ); ?>
								<?php else : ?>
									<img src="<?php echo esc_url( get_theme_file_uri( 'assets/img/sertifikat-contoh.jpg' ) ); ?>"
										alt="">
								<?php endif; ?>
							</div>

							<div class="sertifikat-card__baris">
								<span class="sertifikat-card__nama">
									<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/file-text.svg' ) ); ?>"
										alt="" width="20" height="20">
									<?php
									echo esc_html(
										get_the_title( $berkas ) . '.' .
										strtolower( pathinfo( get_attached_file( $berkas->ID ), PATHINFO_EXTENSION ) )
									);
									?>
								</span>
								<a class="sertifikat-card__unduh"
									href="<?php echo esc_url( wp_get_attachment_url( $berkas->ID ) ); ?>"
									download data-unduhan="sertifikat">
									<span><?php esc_html_e( 'Unduh', 'bapelkes' ); ?></span>
									<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/download.svg' ) ); ?>"
										alt="" width="24" height="24">
								</a>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<?php if ( $total_hal > 1 ) : ?>
					<nav class="paginasi" aria-label="<?php esc_attr_e( 'Halaman sertifikat', 'bapelkes' ); ?>">
						<?php if ( $halaman > 1 ) : ?>
							<a class="paginasi__arah" href="<?php echo esc_url( $link_hal( $halaman - 1 ) ); ?>">
								<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/arrow-left.svg' ) ); ?>"
									alt="" width="24" height="24">
								<?php esc_html_e( 'Sebelumnya', 'bapelkes' ); ?>
							</a>
						<?php else : ?>
							<span class="paginasi__arah paginasi__arah--mati">
								<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/arrow-left.svg' ) ); ?>"
									alt="" width="24" height="24">
								<?php esc_html_e( 'Sebelumnya', 'bapelkes' ); ?>
							</span>
						<?php endif; ?>

						<span class="paginasi__nomor">
							<?php for ( $n = 1; $n <= $total_hal; $n++ ) : ?>
								<?php if ( $n === $halaman ) : ?>
									<span class="paginasi__hal paginasi__hal--aktif"><?php echo esc_html( $n ); ?></span>
								<?php else : ?>
									<a class="paginasi__hal" href="<?php echo esc_url( $link_hal( $n ) ); ?>">
										<?php echo esc_html( $n ); ?>
									</a>
								<?php endif; ?>
							<?php endfor; ?>
						</span>

						<?php if ( $halaman < $total_hal ) : ?>
							<a class="paginasi__arah" href="<?php echo esc_url( $link_hal( $halaman + 1 ) ); ?>">
								<?php esc_html_e( 'Selanjutnya', 'bapelkes' ); ?>
								<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/arrow-right-nav.svg' ) ); ?>"
									alt="" width="24" height="24">
							</a>
						<?php else : ?>
							<span class="paginasi__arah paginasi__arah--mati">
								<?php esc_html_e( 'Selanjutnya', 'bapelkes' ); ?>
								<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/arrow-right-nav.svg' ) ); ?>"
									alt="" width="24" height="24">
							</span>
						<?php endif; ?>
					</nav>
				<?php endif; ?>
			<?php else : ?>
				<p class="jadwal__kosong">
					<?php
					echo '' !== $cari_nama
						? esc_html__( 'Tidak ada sertifikat dengan nama itu.', 'bapelkes' )
						: esc_html__( 'Belum ada sertifikat diunggah.', 'bapelkes' );
					?>
				</p>
			<?php endif; ?>
		</section>
	</main>

	<div class="popup" id="popup-unduhan" hidden>
		<div class="popup__kotak" role="dialog" aria-modal="true" aria-labelledby="popup-judul">
			<button class="popup__tutup" type="button" aria-label="<?php esc_attr_e( 'Tutup', 'bapelkes' ); ?>">&times;</button>
			<p class="popup__judul" id="popup-judul"><?php esc_html_e( 'Berhasil Mengunduh', 'bapelkes' ); ?></p>
			<p class="popup__teks"><?php esc_html_e( 'Berkas sedang diunduh ke perangkat Anda.', 'bapelkes' ); ?></p>
		</div>
	</div>

	<?php
endwhile;

get_footer();
