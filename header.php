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
<link rel="icon" type="image/png" href="<?php echo CSS_IMG; ?>/favicon_mam.png" />
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

<!-- Fixed navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar, navbar-sec" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Menu</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo home_url( '/' ); ?>"><?php bloginfo('name'); ?></a>
        </div>
        
        <div id="navbar" class="collapse navbar-collapse">
            <?php
            $args = array(
                'theme_location'  => 'menu_header', 
                'container'       => false,
                'container_class' => 'menu-principal',
                'menu_class'      => 'nav navbar-nav',
                'walker'          => new bootstrap_nav_menu_walker
            );
            wp_nav_menu($args);
            ?>
            
            <?php echo custom_search_form('search-header', 'navbar-form navbar-right'); ?>
            
            <?php
            $args = array(
                'theme_location'  => 'menu_header_sec', 
                'container'       => false,
                'container_class' => 'menu-sec',
                'menu_class'      => 'nav navbar-nav navbar-right',
                'walker'          => new bootstrap_nav_menu_walker
            );
            wp_nav_menu($args);
            ?>
        </div><!-- /.navbar-collapse -->
    </div>
</nav>
