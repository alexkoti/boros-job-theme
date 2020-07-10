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
            /**
             * Versão 1:
             * Sempre mostrar algum menu, fallback para o wp_page_menu
             * 
             */
            $menu_locations = get_nav_menu_locations();
            $args = array(
                'theme_location'  => 'menu_header', 
                'container'       => false,
                'container_class' => 'menu-principal',
                'menu_class'      => 'navbar-nav mr-auto',
            );
            if( isset($menu_locations['menu_header'] ) ){
                $args['walker'] = new bootstrap_nav_menu_walker;
            }
            wp_nav_menu($args);

            /**
             * Versão 2:
             * Obrigatório 'menu_header' salvo no admin, mostrar alerta caso não encontrado
             * 
             */
            $menu_locations = get_nav_menu_locations();
            if( isset($menu_locations['menu_header'] ) ){
                $args = array(
                    'theme_location'  => 'menu_header', 
                    'container'       => false,
                    'container_class' => 'menu-principal',
                    'menu_class'      => 'navbar-nav mr-auto',
                    'walker'          => new bootstrap_nav_menu_walker
                );
                wp_nav_menu($args);
            }
            else{
                pam('Menu Principal não definido. <a href="' . admin_url('nav-menus.php') . '">Criar um menu no admin</a>, e definir na localização "Slot Cabeçalho"', 'danger', 'MENU');
            }
            ?>
            
            <?php echo custom_search_form('search-header', 'navbar-form navbar-right'); ?>
        </div><!-- /.navbar-collapse -->
    </div>
</nav>
