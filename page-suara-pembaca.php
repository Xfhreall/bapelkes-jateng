<?php
/**
 * Halaman Suara Pembaca (Figma 40:2428).
 */
get_header();

$status = bapelkes_pesan_masukan();
?>

<main class="suara" data-node-id="40:2445">
	<div class="suara__intro">
		<h1 class="suara__title"><em>Suaramu</em> Membantu Kami Menjadi Lebih Baik</h1>
		<p class="suara__lead">
			Sampaikan masukan, saran, atau pengalamanmu untuk membantu kami meningkatkan
			layanan dan fasilitas ke depannya.
		</p>
	</div>

	<div class="suara__panel">
		<?php /* data-asal dipakai skrip untuk mengenali salinan statis di domain lain. */ ?>
		<form class="masukan-form" method="post" enctype="multipart/form-data"
			data-asal="<?php echo esc_attr( wp_parse_url( home_url(), PHP_URL_HOST ) ); ?>"
			action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="bapelkes_masukan">
			<?php wp_nonce_field( 'bapelkes_masukan', 'bapelkes_masukan_nonce' ); ?>

			<?php if ( $status ) : ?>
				<p class="masukan-form__status masukan-form__status--<?php echo esc_attr( $status[0] ); ?>">
					<?php echo esc_html( $status[1] ); ?>
				</p>
			<?php endif; ?>

			<p class="masukan-form__field">
				<label for="masukan-nama">Nama</label>
				<input type="text" id="masukan-nama" name="nama" required
					placeholder="e.g. Raynanta Aulia">
			</p>

			<p class="masukan-form__field">
				<label for="masukan-kontak">Email / Nomor WhatsApp</label>
				<input type="text" id="masukan-kontak" name="kontak" required
					placeholder="e.g. 081567890987">
			</p>

			<p class="masukan-form__field">
				<label for="masukan-lampiran">Lampiran</label>
				<span class="masukan-form__upload">
					<input type="file" id="masukan-lampiran" name="lampiran"
						accept="image/jpeg,image/png,image/webp">
					<span class="masukan-form__upload-label">Upload foto</span>
					<span class="masukan-form__upload-button">Upload</span>
				</span>
			</p>

			<p class="masukan-form__field">
				<label for="masukan-pesan">Masukan &amp; Saran</label>
				<textarea id="masukan-pesan" name="pesan" required
					placeholder="Masukkan masukan dan saran di sini..."></textarea>
			</p>

			<p class="masukan-form__honeypot" aria-hidden="true">
				<label for="masukan-situs">Situs web</label>
				<input type="text" id="masukan-situs" name="situs_web" tabindex="-1" autocomplete="off">
			</p>

			<button class="button--primary masukan-form__submit" type="submit">
				Kirim Masukan dan Saran
				<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/arrow-right.svg' ) ); ?>"
					alt="" width="24" height="24">
			</button>
		</form>
	</div>
</main>

<?php get_footer(); ?>
