<?php
/**
 * Header: navbar melayang di atas hero.
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="site-header-area<?php echo is_front_page() ? '' : ' site-header-area--solid'; ?>">
<header class="site-header<?php echo is_front_page() ? '' : ' site-header--solid'; ?>">
	<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php /* Dua versi logo: yang putih hanya dipakai saat navbar melayang di atas foto. */ ?>
		<img class="site-logo__terang"
			src="<?php echo esc_url( get_theme_file_uri( 'assets/img/logo.png' ) ); ?>"
			alt="<?php bloginfo( 'name' ); ?>" width="110" height="25">
		<img class="site-logo__gelap"
			src="<?php echo esc_url( get_theme_file_uri( 'assets/img/logo-color.png' ) ); ?>"
			alt="" aria-hidden="true" width="110" height="25">
	</a>

	<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="menu-utama">
		<span class="nav-toggle__garis" aria-hidden="true"></span>
		<span class="screen-reader-text"><?php esc_attr_e( 'Buka menu', 'bapelkes' ); ?></span>
	</button>

	<nav class="site-nav" id="menu-utama" aria-label="<?php esc_attr_e( 'Menu Utama', 'bapelkes' ); ?>">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'nav-links',
				'fallback_cb'    => 'bapelkes_default_menu',
				'depth'          => 2,
			)
		);
		?>
	</nav>
</header>

<?php get_template_part( 'template-parts/mega', 'menu' ); ?>
<?php get_template_part( 'template-parts/dropdown', 'pelayanan' ); ?>
</div>
