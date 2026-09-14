<?php
/**
 * Arsip Publikasi (Figma 40:1353). Dipakai home.php, archive.php, dan search.php
 * sehingga daftar berita, arsip kategori, dan hasil pencarian memakai satu layout.
 */

$publikasi_url = get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/publikasi/' );
$kategori      = get_categories( array( 'hide_empty' => true ) );
$kategori_aktif = is_category() ? get_queried_object_id() : 0;
?>

<main class="publikasi" data-node-id="40:1353">
	<div class="publikasi__intro">
		<h1 class="publikasi__title">
			<em>Publikasi</em> Bapelkes Jateng
		</h1>
		<p class="publikasi__lead">
			Dapatkan informasi terbaru seputar program, kegiatan, dan pelatihan kesehatan di Bapelkes Jateng.
		</p>
	</div>

	<div class="publikasi__body">
		<div class="publikasi__toolbar">
			<ul class="filters">
				<li<?php echo $kategori_aktif ? '' : ' class="is-active"'; ?>>
					<a href="<?php echo esc_url( $publikasi_url ); ?>"><?php esc_html_e( 'Semua', 'bapelkes' ); ?></a>
				</li>
				<?php foreach ( $kategori as $cat ) : ?>
					<li<?php echo ( $kategori_aktif === $cat->term_id ) ? ' class="is-active"' : ''; ?>>
						<a href="<?php echo esc_url( get_category_link( $cat ) ); ?>">
							<?php echo esc_html( $cat->name ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>

			<form class="search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/search.svg' ) ); ?>"
					alt="" width="23" height="24">
				<label class="screen-reader-text" for="publikasi-search">
					<?php esc_html_e( 'Cari publikasi', 'bapelkes' ); ?>
				</label>
				<input type="search" id="publikasi-search" name="s"
					value="<?php echo esc_attr( get_search_query() ); ?>"
					placeholder="<?php esc_attr_e( 'Cari...', 'bapelkes' ); ?>">
			</form>
		</div>

		<?php if ( have_posts() ) : ?>
			<div class="post-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/card', 'post' );
				endwhile;
				?>
			</div>

			<?php
			the_posts_pagination(
				array(
					'mid_size'  => 2,
					'prev_text' => __( 'Sebelumnya', 'bapelkes' ),
					'next_text' => __( 'Berikutnya', 'bapelkes' ),
				)
			);
			?>
		<?php else : ?>
			<p class="publikasi__empty"><?php esc_html_e( 'Belum ada publikasi.', 'bapelkes' ); ?></p>
		<?php endif; ?>
	</div>
</main>
