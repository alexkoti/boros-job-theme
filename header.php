<?php get_template_part('head'); ?>

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
