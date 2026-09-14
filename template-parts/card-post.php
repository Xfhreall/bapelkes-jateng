<?php
/**
 * Kartu post — dipakai Beranda (Figma 40:1244) dan arsip Publikasi (40:1370).
 */
?>
<a class="news-card" href="<?php the_permalink(); ?>">
	<div class="news-card__media">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'large', array( 'alt' => the_title_attribute( array( 'echo' => false ) ) ) ); ?>
		<?php endif; ?>
	</div>

	<div class="news-card__body">
		<p class="news-card__date"><?php echo esc_html( get_the_date( 'd F Y' ) ); ?></p>

		<div class="news-card__text">
			<h3 class="news-card__title"><?php the_title(); ?></h3>
			<p class="news-card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
		</div>
	</div>
</a>
