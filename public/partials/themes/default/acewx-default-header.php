<?php
/**
 * Acewx Header file (Elementor-based)
 *
 * @package acewx-header-footer
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
<?php
    do_action( 'acewx_header' );
?>
