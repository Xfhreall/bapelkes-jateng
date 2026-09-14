<?php
/**
 * Template Name: Standar Pelayanan
 *
 * Standar Pelayanan Pelatihan (Figma 40:2844). Isi ditulis admin lewat editor
 * blok sebagai judul dan daftar bernomor; template yang memberi tata letaknya.
 */
get_header();
?>

<main class="standar" data-node-id="40:2844">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<p class="standar__label">
			<?php echo esc_html( bapelkes_konten( 'sp_label', get_the_title() ) ); ?>
		</p>

		<div class="standar__isi">
			<?php the_content(); ?>
		</div>
		<?php
	endwhile;
	?>
</main>

<?php get_footer(); ?>
