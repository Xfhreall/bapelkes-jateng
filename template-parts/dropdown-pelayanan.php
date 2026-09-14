<?php
/**
 * Dropdown "Pelayanan Publik" (Figma 40:3410).
 */

$item = array(
	array(
		'ikon'  => 'chart-bar.svg',
		'judul' => __( 'Pelayanan Publik', 'bapelkes' ),
		'ket'   => __( 'Kenali komitmen layanan publik kami yang berkualitas.', 'bapelkes' ),
		'url'   => home_url( '/pelayanan-publik/' ),
	),
	array(
		'ikon'  => 'file.svg',
		'judul' => __( 'Standar Pelayanan Publik', 'bapelkes' ),
		'ket'   => __( 'Informasi standar layanan penyelenggaraan pelatihan.', 'bapelkes' ),
		'url'   => home_url( '/standar-pelayanan/' ),
	),
);
?>

<div class="dropdown dropdown--ringkas" id="dropdown-pelayanan-publik" data-node-id="40:3410">
	<div class="mega-menu__tautan">
		<?php foreach ( $item as $menu ) : ?>
			<a class="mega-menu__item" href="<?php echo esc_url( $menu['url'] ); ?>">
				<span class="mega-menu__ikon">
					<img src="<?php echo esc_url( get_theme_file_uri( "assets/icons/{$menu['ikon']}" ) ); ?>"
						alt="" width="20" height="20">
				</span>
				<span class="mega-menu__teks">
					<span class="mega-menu__baris">
						<span class="mega-menu__nama"><?php echo esc_html( $menu['judul'] ); ?></span>
						<img src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/chevron-right-sm.svg' ) ); ?>"
							alt="" width="20" height="20">
					</span>
					<span class="mega-menu__ket"><?php echo esc_html( $menu['ket'] ); ?></span>
				</span>
			</a>
		<?php endforeach; ?>
	</div>
</div>
