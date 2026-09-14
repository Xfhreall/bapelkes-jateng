<?php
/**
 * Template Name: Profil
 *
 * Halaman Profil (Figma 40:2099). Tata letak mengikuti desain; seluruh teks,
 * foto, dan daftar dapat diubah lewat kotak Konten Halaman.
 */
get_header();

$misi_gambar     = (int) bapelkes_konten( 'misi_gambar' );
$pimpinan_gambar = (int) bapelkes_konten( 'pimpinan_gambar' );
$struktur_gambar = (int) bapelkes_konten( 'struktur_gambar' );

$misi = bapelkes_konten_daftar(
	'misi',
	array(
		'Membangun masyarakat Jawa Tengah yang religius, toleran dan guyup untuk menjaga Negara Kesatuan Republik Indonesia.',
		'Membangun masyarakat Jawa Tengah yang religius, toleran dan guyup untuk menjaga Negara Kesatuan Republik Indonesia.',
		'Memperkuat kapasitas ekonomi rakyat dan memperluas lapangan kerja untuk mengurangi kemiskinan dan pengangguran.',
		'Menjadikan masyarakat Jawa Tengah, lebih sehat, lebih pintar, lebih berbudaya dan mencintai lingkungan.',
	)
);

$program = bapelkes_konten_daftar(
	'program',
	array(
		'Sekolah tanpa sekat: Pelatihan tentang demokrasi dan pemilu, gender, anti korupsi dan magang gubernur untuk siswa SMA/SMK.',
		'Peningkatan peran rumah ibadah, fasilitasi pendakwah dan guru ngaji.',
		'Reformasi birokrasi di kabupaten/kota berbasis teknologi informasi dan sistem layanan terintegrasi.',
		'Satgas kemiskinan, bantuan desa, rumah sederhana layak huni.',
		'Obligasi daerah, kemudahan akses kredit UMKM, penguatan BUMDes dan pelatihan startup untuk wirausahawan muda.',
		'Menjaga harga komoditas dan asuransi gagal panen untuk petani serta melindungi kepentingan nelayan.',
		'Pengembaganan transportasi massal, revitalisasi jalur kereta dan bandara serta pembangunan embung/irigasi.',
		'Pembukaan kawasan industry baru dan rintisan pertanian terintegrasi.',
		'Rumah Sakit tanpa dinding, sekolah gratis untuk SMAN, SMKN, SLB dan bantuan sekolah swasta, pondok pesantren, madrasah dan difabel.',
		'Festifas seni serta pengembangan infrastruktur olahraga, rumah kebudayaan dan kepedulian lingkungan.',
	)
);

$sejarah = bapelkes_konten_tabel(
	'sejarah',
	array(
		array( '1977', 'Pendirian Sekolah Pembantu Bidan', 'Pemerintah Pusat melalui BKKBN membangun Sekolah Pembantu Bidan atau Paramedis (ANM School).' ),
		array( '1981', 'Penetapan Menjadi BLKM', 'Sesuai SK Menkes RI No. 125/Kep/Diklat/Kes/1981, instansi ini ditetapkan menjadi BLKM (Balai Latihan Kesehatan Masyarakat).' ),
		array( '1987', 'Perubahan Nama Menjadi KLKM', 'Resmi berubah nama menjadi KLKM (Kursus Latihan Kesehatan Masyarakat).' ),
		array( '1993', 'Perubahan Menjadi Bapelkes UPT Pusat', 'Berubah menjadi Bapelkes (Balai Pelatihan Kesehatan) sebagai Unit Pelaksana Teknis (UPT) Pusdiklat Pegawai Depkes RI di Daerah.' ),
		array( '2002', 'Pelimpahan ke Daerah & Menjadi BPTPK', 'Seiring Otonomi Daerah, Bapelkes dilimpahkan ke Provinsi Jawa Tengah melalui Perda No. 1 Tahun 2002 dan berganti nama menjadi BPTPK (Balai Pelatihan Teknis Profesi Kesehatan) sebagai UPTD Dinkes Provinsi Jawa Tengah.' ),
	)
);

$pimpinan = get_posts(
	array(
		'post_type'      => 'pimpinan',
		'posts_per_page' => 3,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
	)
);

$dokumen = array_filter( array_map( 'absint', explode( ',', (string) bapelkes_konten( 'tupoksi_dokumen' ) ) ) );

// Dokumen ditampilkan dua halaman sekaligus, seperti tampilan berkas di desain.
$dokumen_per_layar = 2;
$dokumen_total     = max( 1, (int) ceil( count( $dokumen ) / $dokumen_per_layar ) );
$dokumen_hal       = max( 1, min( $dokumen_total, absint( bapelkes_nilai_rute( 'doc', 1 ) ) ) );
$dokumen_tampil    = array_slice( $dokumen, ( $dokumen_hal - 1 ) * $dokumen_per_layar, $dokumen_per_layar );
?>

<main class="profil">

	<section class="profil__visi" data-node-id="40:2120">
		<h1 class="profil__visi-teks">
			<em><?php echo esc_html( bapelkes_konten( 'visi_prefix', 'Visi Bapelkes Jateng' ) ); ?></em>
			<?php echo esc_html( bapelkes_konten( 'visi_teks', ': Menuju Jawa Tengah Sejahtera dan Berdikari, Tetap Mboten Korupsi, Mboten Ngapusi.' ) ); ?>
		</h1>
	</section>

	<section class="profil__misi" data-node-id="40:2122">
		<?php if ( $misi_gambar ) : ?>
			<div class="profil__misi-latar">
				<?php echo wp_get_attachment_image( $misi_gambar, 'full', false, array( 'alt' => '' ) ); ?>
			</div>
		<?php endif; ?>

		<div class="profil__misi-kartu">
			<?php foreach ( $misi as $nomor => $teks ) : ?>
				<div class="misi-card">
					<span class="misi-card__label">
						<?php printf( esc_html__( 'Misi %d', 'bapelkes' ), (int) $nomor + 1 ); ?>
					</span>
					<p class="misi-card__teks"><?php echo esc_html( $teks ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="profil__program" data-node-id="40:2144">
		<h2 class="profil__program-judul">
			<?php echo esc_html( bapelkes_konten( 'program_judul', '10 Program Unggulan' ) ); ?>
		</h2>

		<?php
		$kolom = array_chunk( $program, (int) ceil( count( $program ) / 2 ) );
		?>
		<div class="profil__program-kolom">
			<?php foreach ( $kolom as $isi ) : ?>
				<ul class="program-list">
					<?php foreach ( $isi as $item ) : ?>
						<li><?php echo esc_html( $item ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="profil__pimpinan" data-node-id="40:2206">
		<div class="pimpinan-intro">
			<?php if ( $pimpinan_gambar ) : ?>
				<div class="pimpinan-intro__latar">
					<?php echo wp_get_attachment_image( $pimpinan_gambar, 'large', false, array( 'alt' => '' ) ); ?>
				</div>
			<?php endif; ?>

			<p class="pimpinan-intro__judul">
				<em><?php echo esc_html( bapelkes_konten( 'pimpinan_judul', 'Kepala Balpakes' ) ); ?></em><br>
				<span><?php echo esc_html( bapelkes_konten( 'pimpinan_sub', 'dari Waktu ke Waktu' ) ); ?></span>
			</p>
			<p class="pimpinan-intro__ket">
				<?php echo esc_html( bapelkes_konten( 'pimpinan_ket', 'Daftar pimpinan yang telah membawa Balpakes dalam setiap periode kepemimpinannya' ) ); ?>
			</p>
		</div>

		<?php foreach ( $pimpinan as $orang ) : ?>
			<div class="pimpinan-card">
				<?php if ( has_post_thumbnail( $orang ) ) : ?>
					<div class="pimpinan-card__foto">
						<?php echo get_the_post_thumbnail( $orang, 'large', array( 'alt' => esc_attr( get_the_title( $orang ) ) ) ); ?>
					</div>
				<?php endif; ?>

				<div class="pimpinan-card__teks">
					<p class="pimpinan-card__nama"><?php echo esc_html( get_the_title( $orang ) ); ?></p>
					<p class="pimpinan-card__jabatan"><?php echo esc_html( get_the_excerpt( $orang ) ); ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</section>

	<section class="profil__sejarah" data-node-id="40:2222">
		<div class="sejarah">
			<h2 class="sejarah__judul">
				<?php echo esc_html( bapelkes_konten( 'sejarah_judul', 'Sejarah Berdirinya Balpekes Jateng' ) ); ?>
			</h2>

			<div class="sejarah__baris-grup">
				<?php foreach ( $sejarah as $baris ) : ?>
					<div class="sejarah__baris">
						<span class="sejarah__tahun"><?php echo esc_html( $baris[0] ?? '' ); ?></span>
						<span class="sejarah__nama"><?php echo esc_html( $baris[1] ?? '' ); ?></span>
						<span class="sejarah__ket"><?php echo esc_html( $baris[2] ?? '' ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="profil__tupoksi" data-node-id="40:2247">
		<h2 class="profil__tupoksi-judul">
			<em><?php echo esc_html( bapelkes_konten( 'tupoksi_judul', 'Tugas Pokok dan Fungsi' ) ); ?></em>
		</h2>
		<p class="profil__tupoksi-sub">
			<?php echo esc_html( bapelkes_konten( 'tupoksi_sub', 'Bapelkes Provinsi Jawa Tengah' ) ); ?>
		</p>

		<?php $tupoksi_url = bapelkes_konten( 'tupoksi_url' ); ?>
		<?php if ( $tupoksi_url ) : ?>
			<a class="button--primary" href="<?php echo esc_url( $tupoksi_url ); ?>" target="_blank" rel="noopener">
				<?php echo esc_html( bapelkes_konten( 'tupoksi_tombol', 'Buka Dokumen Lengkap' ) ); ?>
				<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/arrow-right.svg' ) ); ?>"
					alt="" width="24" height="24">
			</a>
		<?php endif; ?>

		<?php if ( $dokumen ) : ?>
			<div class="profil__dokumen-wadah">
				<div class="profil__dokumen">
					<?php foreach ( $dokumen_tampil as $lampiran ) : ?>
						<figure class="profil__dokumen-halaman">
							<?php echo wp_get_attachment_image( $lampiran, 'large', false, array( 'alt' => '' ) ); ?>
						</figure>
					<?php endforeach; ?>
				</div>

				<?php if ( $dokumen_total > 1 ) : ?>
					<nav class="paginasi" aria-label="<?php esc_attr_e( 'Halaman dokumen', 'bapelkes' ); ?>">
						<?php if ( $dokumen_hal > 1 ) : ?>
							<a class="paginasi__arah" href="<?php echo esc_url( trailingslashit( get_permalink() ) . 'dokumen/' . ( $dokumen_hal - 1 ) . '/' ); ?>">
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
							<?php for ( $n = 1; $n <= $dokumen_total; $n++ ) : ?>
								<?php if ( $n === $dokumen_hal ) : ?>
									<span class="paginasi__hal paginasi__hal--aktif"><?php echo esc_html( $n ); ?></span>
								<?php else : ?>
									<a class="paginasi__hal" href="<?php echo esc_url( trailingslashit( get_permalink() ) . 'dokumen/' . $n . '/' ); ?>">
										<?php echo esc_html( $n ); ?>
									</a>
								<?php endif; ?>
							<?php endfor; ?>
						</span>

						<?php if ( $dokumen_hal < $dokumen_total ) : ?>
							<a class="paginasi__arah" href="<?php echo esc_url( trailingslashit( get_permalink() ) . 'dokumen/' . ( $dokumen_hal + 1 ) . '/' ); ?>">
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
			</div>
		<?php endif; ?>
	</section>

	<section class="profil__struktur">
		<h2 class="profil__struktur-judul" data-node-id="40:2282">
			<?php echo esc_html( bapelkes_konten( 'struktur_judul', 'Struktur Organisasi, Tata Kerja, dan Rincian Hierarki Jabatan Balai Pelatihan Kesehatan Provinsi Jawa Tengah.' ) ); ?>
		</h2>

		<?php if ( $struktur_gambar ) : ?>
			<figure class="profil__struktur-bagan" data-node-id="40:2283">
				<?php echo wp_get_attachment_image( $struktur_gambar, 'full', false, array( 'alt' => esc_attr__( 'Bagan struktur organisasi', 'bapelkes' ) ) ); ?>
			</figure>
		<?php endif; ?>
	</section>

</main>

<?php get_footer(); ?>
