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
			$total_hal = (int) $GLOBALS['wp_query']->max_num_pages;
			$halaman   = max( 1, (int) get_query_var( 'paged' ) );
			?>
			<?php if ( $total_hal > 1 ) : ?>
				<?php
				$panah = array(
					'kiri'  => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
					'kanan' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				);
				?>
				<nav class="paginasi paginasi--ringkas" aria-label="<?php esc_attr_e( 'Halaman publikasi', 'bapelkes' ); ?>">
					<?php if ( $halaman > 1 ) : ?>
						<a class="paginasi__hal paginasi__panah" href="<?php echo esc_url( get_pagenum_link( $halaman - 1 ) ); ?>"
							aria-label="<?php esc_attr_e( 'Halaman sebelumnya', 'bapelkes' ); ?>"><?php echo $panah['kiri']; ?></a>
					<?php else : ?>
						<span class="paginasi__hal paginasi__panah paginasi__panah--mati" aria-hidden="true"><?php echo $panah['kiri']; ?></span>
					<?php endif; ?>

					<?php foreach ( bapelkes_nomor_halaman( $halaman, $total_hal ) as $n ) : ?>
						<?php if ( 0 === $n ) : ?>
							<span class="paginasi__hal paginasi__hal--jeda" aria-hidden="true">…</span>
						<?php elseif ( $n === $halaman ) : ?>
							<span class="paginasi__hal paginasi__hal--aktif" aria-current="page"><?php echo esc_html( $n ); ?></span>
						<?php else : ?>
							<a class="paginasi__hal" href="<?php echo esc_url( get_pagenum_link( $n ) ); ?>"><?php echo esc_html( $n ); ?></a>
						<?php endif; ?>
					<?php endforeach; ?>

					<?php if ( $halaman < $total_hal ) : ?>
						<a class="paginasi__hal paginasi__panah" href="<?php echo esc_url( get_pagenum_link( $halaman + 1 ) ); ?>"
							aria-label="<?php esc_attr_e( 'Halaman berikutnya', 'bapelkes' ); ?>"><?php echo $panah['kanan']; ?></a>
					<?php else : ?>
						<span class="paginasi__hal paginasi__panah paginasi__panah--mati" aria-hidden="true"><?php echo $panah['kanan']; ?></span>
					<?php endif; ?>
				</nav>
			<?php endif; ?>
		<?php else : ?>
			<p class="publikasi__empty"><?php esc_html_e( 'Belum ada publikasi.', 'bapelkes' ); ?></p>
		<?php endif; ?>
	</div>
</main>
