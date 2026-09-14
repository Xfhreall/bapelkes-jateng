<?php
/**
 * Detail Publikasi (Figma 40:1479).
 */
get_header();

while ( have_posts() ) :
	the_post();

	$share_url   = rawurlencode( get_permalink() );
	$share_title = rawurlencode( get_the_title() );
	$share       = array(
		'linkedin'  => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $share_url,
		'twitter'   => 'https://twitter.com/intent/tweet?url=' . $share_url . '&text=' . $share_title,
		'instagram' => 'https://www.instagram.com/',
		'facebook'  => 'https://www.facebook.com/sharer/sharer.php?u=' . $share_url,
		'youtube'   => 'https://www.youtube.com/',
	);
	?>

	<article <?php post_class( 'artikel' ); ?>>
		<header class="artikel__head" data-node-id="40:1497">
			<h1 class="artikel__title"><?php the_title(); ?></h1>
			<p class="artikel__meta">
				<span><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
				<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/dot.svg' ) ); ?>"
					alt="" width="4" height="4">
				<span><?php echo esc_html( get_the_date( 'd F Y' ) ); ?></span>
			</p>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="artikel__figure" data-node-id="40:1503">
				<?php the_post_thumbnail( 'full' ); ?>
			</figure>
		<?php endif; ?>

		<div class="artikel__content" data-node-id="40:1505">
			<div class="artikel__body">
				<?php the_content(); ?>
			</div>

			<div class="artikel__share">
				<p class="artikel__share-label"><?php esc_html_e( 'Bagikan postingan ini', 'bapelkes' ); ?></p>
				<div class="artikel__share-links">
					<?php foreach ( $share as $jaringan => $url ) : ?>
						<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer">
							<img src="<?php echo esc_url( get_theme_file_uri( "assets/icons/{$jaringan}.svg" ) ); ?>"
								alt="<?php echo esc_attr( ucfirst( $jaringan ) ); ?>" width="20" height="20">
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</article>

	<?php
	get_template_part( 'template-parts/section', 'informasi-terbaru' );
endwhile;

get_footer();
