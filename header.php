<?php get_template_part('head'); ?>

<!-- Fixed navbar -->
<nav class="navbar navbar-expand-sm navbar-dark bg-dark navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu-principal" aria-controls="menu-principal" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="<?php echo home_url( '/' ); ?>"><?php bloginfo('name'); ?></a>
        </div>
        
        <div id="menu-principal" class="collapse navbar-collapse">
            <?php
            $args = array(
                'theme_location'  => 'menu_header', 
                'container'       => false,
                'container_class' => 'menu-principal',
                'menu_class'      => 'navbar-nav mr-auto',
                'walker'          => new bootstrap_nav_menu_walker
            );
            wp_nav_menu($args);
            ?>
            
            <?php echo custom_search_form('search-header', 'navbar-form navbar-right'); ?>
        </div><!-- /.navbar-collapse -->
    </div>
</nav>
