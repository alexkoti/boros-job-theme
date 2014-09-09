<!doctype html>
<!--[if lt IE 7 ]> <html lang="pt" class="no-js ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]>    <html lang="pt" class="no-js ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html lang="pt" class="no-js ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9 ]>    <html lang="pt" class="no-js ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="geo.country" content="br" />
<meta name="description" content="<?php bloginfo('description'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="home" href="<?php site_url('/'); ?>" />
<title><?php
global $page, $paged;
$title = wp_title( '|', true, 'right' );

// Add the blog name.
bloginfo( 'name' );

// Add the blog description for the home/front page.
$site_description = get_bloginfo( 'description', 'display' );
if ( $site_description && ( is_home() || is_front_page() ) )
	echo " | $site_description";

// Add a page number if necessary:
if ( $paged >= 2 || $page >= 2 )
	echo ' | ' . sprintf( 'Página %s', max( $paged, $page ) );
?></title>
<?php
if ( is_singular() && get_option( 'thread_comments' ) ){ wp_enqueue_script( 'comment-reply' ); }
wp_head();
?>
</head>

<body <?php body_class(); ?>>

<?php do_action( 'header_debug' ); ?>

<div id="site">
	<header id="header">
		<div id="header_info">
			<a href="<?php echo home_url( '/' ); ?>" id="logo_home" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
				<?php bloginfo( 'name' ); ?>
			</a>
			<?php get_search_form(); ?>
		</div>
		
		<?php wp_nav_menu( array( 'theme_location' => 'menu_principal', 'container' => 'nav', 'container_class' => 'menu_principal' ) ); ?>
	</header><!-- .header -->
	
	<div id="content">
		<div>
			<?php
			/**
			$args = array(
				'type' => 'search',
				'search' => '#Sharecamp',
				'number' => 10,
				'avatars' => false,
				'link_avatars' => false,
				'cache' => 5,
				'cache_unit' => 1,
				'title' => 'teste %USER%',
			);
			boros_twitter( $args );
			/**/
			//foobar();
			//test_subpage_tab_function();
			//
			//$end = opt_option('site_street_address', '<div>Endereço: %s</div>', false);
			//echo $end;
			//apagar();
			//test_1();
			?>
		</div>