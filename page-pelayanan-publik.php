<?php
/**
 * Template Name: Pelayanan Publik
 *
 * Halaman Pelayanan Publik (Figma 40:2509): indeks kepuasan masyarakat,
 * grafik penilaian per unsur, komposisi responden, dan maklumat pelayanan.
 */
get_header();

$unsur = bapelkes_konten_tabel(
	'ikm_unsur',
	array(
		array( 'Perilaku Pelaksana', '87.75' ),
		array( 'Penanganan Pengaduan, Saran, dan Masukan', '86.25' ),
		array( 'Kompetensi Pelaksana', '85.25' ),
		array( 'Produk Spesifikasi Jenis Pelayanan', '83.75' ),
		array( 'Waktu Pelayanan', '83.25' ),
		array( 'Prosedur', '83.00' ),
		array( 'Biaya', '82.75' ),
		array( 'Persyaratan', '82.25' ),
		array( 'Kelengkapan Sarana dan Prasarana', '81.00' ),
	)
);

$perempuan = (float) bapelkes_konten( 'responden_perempuan', 695 );
$laki      = (float) bapelkes_konten( 'responden_laki', 224 );
$total     = (float) bapelkes_konten( 'responden_total', $perempuan + $laki );

// Busur responden: setengah lingkaran, porsi perempuan mengisi dari kiri.
$porsi_perempuan = $total > 0 ? round( ( $perempuan / $total ) * 100 ) : 0;

$maklumat       = (int) bapelkes_konten( 'maklumat_gambar' );
$maklumat_latar = (int) bapelkes_konten( 'maklumat_latar' );
?>

<main class="pelayanan" data-node-id="40:2526">
	<div class="publikasi__intro">
		<h1 class="publikasi__title">
			<em><?php echo esc_html( bapelkes_konten( 'pp_judul', 'Pelayanan' ) ); ?></em>
			<?php echo esc_html( bapelkes_konten( 'pp_judul_sisa', 'Publik' ) ); ?>
		</h1>
		<p class="publikasi__lead">
			<?php echo esc_html( bapelkes_konten( 'pp_lead', 'Kenali komitmen dan standar pelayanan kami dalam memberikan layanan publik yang berkualitas.' ) ); ?>
		</p>
	</div>

	<div class="ikm">
		<div class="ikm__kiri">
			<div class="ikm__nilai">
				<span class="ikm__label">
					<?php echo esc_html( bapelkes_konten( 'ikm_label', 'Indeks Kepuasan Masyarakat' ) ); ?>
				</span>
				<span class="ikm__angka"><?php echo esc_html( bapelkes_konten( 'ikm_nilai', '84' ) ); ?></span>
				<p class="ikm__mutu">
					Mutu Pelayanan: <em><?php echo esc_html( bapelkes_konten( 'ikm_mutu', 'Baik' ) ); ?></em>
				</p>
				<p class="ikm__ket">
					<?php echo esc_html( bapelkes_konten( 'ikm_ket', 'Terima kasih atas masukannya. Hal ini sangat bermanfaat untuk peningkatan layanan Bapelkes Prov Jateng.' ) ); ?>
				</p>
				<a class="button--primary" href="<?php echo esc_url( home_url( '/suara-pembaca/' ) ); ?>">
					Kirim Masukan dan Saran
					<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/arrow-right.svg' ) ); ?>"
						alt="" width="24" height="24">
				</a>
			</div>

			<div class="responden">
				<div class="responden__busur" style="--porsi: <?php echo esc_attr( $porsi_perempuan ); ?>%">
					<span class="responden__jumlah"><?php echo esc_html( number_format_i18n( $total ) ); ?></span>
					<span class="responden__label">Jumlah Responden</span>
				</div>

				<dl class="responden__rincian">
					<div>
						<dt><span class="responden__titik responden__titik--p"></span> Perempuan</dt>
						<dd><?php echo esc_html( number_format_i18n( $perempuan ) ); ?></dd>
					</div>
					<div>
						<dt><span class="responden__titik responden__titik--l"></span> Laki-laki</dt>
						<dd><?php echo esc_html( number_format_i18n( $laki ) ); ?></dd>
					</div>
				</dl>
			</div>
		</div>

		<div class="ikm__grafik">
			<h2 class="ikm__grafik-judul">
				<?php echo esc_html( bapelkes_konten( 'ikm_grafik_judul', 'Penilaian IKM Bapelkes Prov jateng Smt-1 Tahun 2024' ) ); ?>
			</h2>

			<div class="grafik">
				<div class="grafik__skala" aria-hidden="true">
					<?php foreach ( array( 0, 20, 40, 60, 80, 100 ) as $tanda ) : ?>
						<span><?php echo esc_html( $tanda ); ?></span>
					<?php endforeach; ?>
				</div>

				<table class="grafik__tabel">
					<caption class="screen-reader-text">
						<?php esc_html_e( 'Nilai tiap unsur pelayanan dari skala 0 sampai 100', 'bapelkes' ); ?>
					</caption>
					<tbody>
						<?php foreach ( $unsur as $urutan => $baris ) : ?>
							<?php
							$nama  = $baris[0] ?? '';
							$nilai = (float) ( $baris[1] ?? 0 );
							?>
							<tr>
								<th scope="row"><?php echo esc_html( $nama ); ?></th>
								<td>
									<span class="grafik__batang grafik__batang--<?php echo esc_attr( $urutan % 9 ); ?>"
										style="width: <?php echo esc_attr( min( 100, max( 0, $nilai ) ) ); ?>%"></span>
								</td>
								<td class="grafik__nilai"><?php echo esc_html( number_format( $nilai, 2 ) ); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<section class="maklumat" data-node-id="40:2623">
		<div class="maklumat__latar">
			<?php
			if ( $maklumat_latar ) {
				echo wp_get_attachment_image( $maklumat_latar, 'full', false, array( 'alt' => '' ) );
			} else {
				printf(
					'<img src="%s" alt="">',
					esc_url( get_theme_file_uri( 'assets/img/maklumat-latar.jpg' ) )
				);
			}
			?>
		</div>

		<figure class="maklumat__kartu">
			<?php
			if ( $maklumat ) {
				echo wp_get_attachment_image( $maklumat, 'large', false, array( 'alt' => esc_attr__( 'Maklumat pelayanan', 'bapelkes' ) ) );
			} else {
				printf(
					'<img src="%s" alt="%s">',
					esc_url( get_theme_file_uri( 'assets/img/maklumat-dokumen.jpg' ) ),
					esc_attr__( 'Maklumat pelayanan', 'bapelkes' )
				);
			}
			?>
		</figure>
	</section>
</main>

<?php get_footer(); ?>
