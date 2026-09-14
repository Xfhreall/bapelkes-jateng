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

<header class="site-header<?php echo is_front_page() ? '' : ' site-header--solid'; ?>">
	<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<img src="<?php echo esc_url( get_theme_file_uri( is_front_page() ? 'assets/img/logo.png' : 'assets/img/logo-color.png' ) ); ?>"
			alt="<?php bloginfo( 'name' ); ?>" width="110" height="25">
	</a>

	<nav class="site-nav" aria-label="<?php esc_attr_e( 'Menu Utama', 'bapelkes' ); ?>">
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
