<?php
/**
 * ==================================================
 * REGISTER MENUS ===================================
 * ==================================================
 * Aqui apenas são adicionadas posições pré-definidas de menus, e não são registrados nenhum menu de fato.
 * São registrados locais(placeholders), aonde poderão ser adicionados menus criados. O nome ideal seria 'register_menu_location', 
 * pois não são criados menus previamente, apenas via admin.
 * 
 * ATENÇÃO: o indice 'menu_footer' é utilizado pelo bootstrap_nav_menu_walker(), caso seja preciso modificar o índice, é
 * preciso atualizar 
 */
add_action( 'after_setup_theme', 'register_menus' );
function register_menus(){
    register_nav_menus(
        array(
            'menu_header' => 'Slot Cabeçalho',
            'menu_footer' => 'Slot Rodapé',
        )
    );
}



/**
 * ==================================================
 * REGISTER SIDEBARS ================================
 * ==================================================
 * Aqui são registrados os sidebars para serem exibidos no admin e no frontend ao mesmo tempo
 * 
 * @param string    $name             Nome que irá aprecer no controle do admin
 * @param string    $description      Descrição que aparece no admin
 * @param string    $before_widget    HTML de abertura do widget. Geralmente é usado <LI>, mas pode ser qualquer tag de bloco.
 * @param string    $after_widget     HTML de fechamento
 * @param string    $before_title     HTML de abertura do título do widget. Lembre-se que alguns widgets podem ser configurados com ou sem título, então a formatação deverá prever esses casos.
 * @param string    $after_title      HTML de fechamento
 * 
 */
add_action( 'widgets_init', 'register_sidebars_init' );
function register_sidebars_init(){
    
    // Registrar um novo sidebar
    $args = array(
        'name' => 'Sidebar Geral',
        'id' => 'sidebar_geral',
        'description' => 'Sidebar Geral',
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    );
    register_sidebar( $args );
}



/**
 * ==================================================
 * CLASSES DO MENU ==================================
 * ==================================================
 * Adicionar class active no item do menu, conforme o post_type navegado.
 * Por exemplo, deixar ativo uma 'page' chamada 'Vídeos', quando se está em uma listagem do post_type 'video'.
 * 
 * @ATENÇÂO: a class é aplicada no link <a>, e não na li parent
 */
add_filter( 'walker_nav_menu_start_el', 'add_class_to_custom_post_type_menu', 10, 4 );
function add_class_to_custom_post_type_menu( $item_output, $item, $depth, $args ){
    global $post;
    
    if( !is_home() or !is_front_page() ){
        /**
         * Este trecho tenta aplicar automaticamente o link ativo caso o título do conteúdo tenha o mesmo nome do post_type - label plural do post_type, por exemplo, Posts, Vídeos, Eventos
         */
        $post_types = get_post_types( '', 'objects' );
        $current_type = get_post_type();
        foreach( $post_types as $pt ){
            if( $current_type == $pt->name and $item->title == $pt->labels->name )
                $item_output = str_replace( '">', '" class="active">', $item_output );
        }
        
        /**
         * Adições personalizadas, definir qual post_type ativará determinado content(page, post, custom) pelo título.
         * Sempre verificar se a ativação já não foi feita no bloco anterior.
         */
        if( 'post' == get_post_type() and $item->title == 'Teste' ){
            $item_output = str_replace( '">', '" class="active">', $item_output );
        }
    }
    return $item_output;
}



/**
 * ==================================================
 * WP_LIST_PAGE ACTIVE CLASS ========================
 * ==================================================
 * Adicionar a class .active no link ativo em wp_list_pages();
 * 
 */
add_filter( 'page_css_class', 'boros_page_css_class', 10, 5 );
function boros_page_css_class( $css_class, $page, $depth, $args, $current_page ){
    if( $current_page == $page->ID ){
        $css_class[] = 'active';
    }
    return $css_class;
}



/**
 * ==================================================
 * BOOTSTRAP MENU ===================================
 * ==================================================
 * 
 * 
 */
class bootstrap_nav_menu_walker extends Walker_Nav_Menu {

    /**
     * Armazenar itens do menu para evitar novas queries
     * 
     */
    var $menu_locations;
    var $menu_header_items = array();

    function start_lvl( &$output, $depth = 0, $args = array() ){
        // depth dependent classes
        $indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
        $display_depth = ( $depth + 1); // because it counts the first submenu as 0
        $classes = array(
            'dropdown-menu',
            'sub-menu',
            ( $display_depth % 2  ? 'menu-odd' : 'menu-even' ),
            ( $display_depth >=2 ? 'sub-sub-menu' : '' ),
            'menu-depth-' . $display_depth
        );
        $class_names = implode( ' ', $classes );
        $output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
    }
    
    function start_el( &$output, $item, $depth = 0, $args = array(), $current_object_id = 0 ){
        //pre($item, 'item');
        //pre($args, 'args');

        /**
         * Caso seja o primeiro nível, adicionar class de dropdown, mas apenas quando o existirem childs.
         * 
         * ATENÇÃO: o índice 'menu_header' é relativo aos slots de menus registrados em register_menus(), neste mesmo arquivo.
         * 
         */
        if( empty($this->menu_locations) ){
            $this->menu_locations = get_nav_menu_locations();
            if( isset($this->menu_locations['menu_header'] ) ){
                $this->menu_header_items = wp_get_nav_menu_items( $this->menu_locations['menu_header'] );
            }
        }

        $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent
        
        $depth_class_names = "li_normal depth-{$depth}";
        if( $depth == 0 ){
            $depth_class_names = "dropdown depth-{$depth}";
        }
        
        $output .= $indent . '<li class="' . $depth_class_names . '">';
        
        $link_classes = '';
        
        $childs = false;
        if( isset($this->menu_locations['menu_header']) ){
            foreach( $this->menu_header_items as $i ){
                if( $i->menu_item_parent == $item->ID ){
                    $childs[] = $i;
                }
            }
            // possui childs!
            if( !empty($childs) and $depth == 0 ){
                $link_classes = 'class="dropdown-toggle" data-toggle="dropdown"';
                $item->title .= ' <b class="caret"></b>';
            }
        }
        
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        $attributes .= " {$link_classes}";
        
        $item_output = sprintf(
            '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s' . "\n",
            $item->before,
            $attributes,
            $item->link_before,
            apply_filters( 'the_title', $item->title, $item->ID ),
            $item->link_after,
            $item->after
        );
        
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}


