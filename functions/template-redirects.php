<?php
/**
 * TEMPLATE REDIRECTS
 * Funções estáticas para habilitar o uso de arquivos de templates para os seguintes casos:
 * 1) subcategoria usar o template da categoria
 * 2) templates em singles, usando na ordem de prioridade:
 *    - ID = single-123.php
 *    - slug da categoria que pertence o post = single-cat-categoryslug.php
 *    - ID da categoria que pertence o post = single-cat-123.php
 *    - slug da categoria parent = single-cat-parentcategoryslug.php
 * 
 */



/**
 * ==================================================
 * TEMPLATE EM SUBCATEGORIA =========================
 * ==================================================
 * 
 * Usar o mesmo template de categoria nas listagens de sub-categorias.
 * Busca templates no formato {category|taxonomy}-term-slug.php
 * 
 * @link http://codex.wordpress.org/User:MichaelH/MyPlugins - modificado para pegar custom taxonomies
 */
add_action( 'template_redirect', 'use_parent_term_template' );
function use_parent_term_template(){
    global $wp_query;
    $GLOBALS['template'] = false;
    
    $taxonomies = get_taxonomies();
    if( isset($taxonomies['category']) )
        $taxonomies['category'] = 'category_name';
    foreach( $taxonomies as $tax ){
        if( isset($wp_query->query[$tax]) ){
            $terms = array_reverse( explode( '/', $wp_query->query[$tax] ) );
            $pre = ( $tax == 'category_name' ) ? 'category' : "taxonomy-{$tax}";
            foreach( $terms as $term ){
                if( $tax == 'category_name' )
                    $tax = 'category';
                
                if( file_exists(TEMPLATEPATH . "/{$pre}-{$term}.php") ){
                    $GLOBALS['template'] = TEMPLATEPATH . "/{$pre}-{$term}.php";
                    include_once(TEMPLATEPATH . "/{$pre}-{$term}.php");
                    exit;
                }
            }
        }
    }
}



/**
 * ==================================================
 * SINGLES TEMPLATES ================================
 * ==================================================
 * 
 * Define templates para as páginas single, na seguinte ordem de prioridade: categoria, tag, author, single, default
 * 
 * @link http://justintadlock.com/archives/2008/12/06/creating-single-post-templates-in-wordpress
 */
add_filter( 'page_template', 'custom_page_template' );
function custom_page_template( $page_template ){
    global $post;
    
    // caso já exista um template único do slug, apenas retornar
    $custom_template = TEMPLATEPATH . "/page-{$post->post_name}.php";
    if( $page_template == $custom_template ){
        return $page_template;
    }
    
    if( isset($post->ancestors) ){
        foreach( $post->ancestors as $parent_id ){
            $parent = get_page( $parent_id );
            
            // primeiro tentar pela ID
            if(file_exists(TEMPLATEPATH . '/page-' . $parent->ID . '.php'))
                return TEMPLATEPATH . '/page-' . $parent->ID . '.php';
            
            if(file_exists(TEMPLATEPATH . '/page-' . $parent->post_name . '.php'))
                return TEMPLATEPATH . '/page-' . $parent->post_name . '.php';
        }
    }
    
    return $page_template;
}

add_filter( 'single_template', 'custom_single_template' );
function custom_single_template( $single ){
    global $wp_query, $post;
    
    /**
     * Prioridade máxima para o post->ID
     */
    if(file_exists(TEMPLATEPATH . '/single-' . $post->ID . '.php'))
        return TEMPLATEPATH . '/single-' . $post->ID . '.php';
    
    /**
     * IMPORTANTE:
     * Muito cuidado ao criar templates para autores, pois eles irão prevalecer sobre os templates de categoria.
     * Muitas vezes será melhor fazer uma verificação de autor dentro do loop de exibição para pegar determinados users.
     */
    $curauth = get_userdata($wp_query->post->post_author);
    if(file_exists(TEMPLATEPATH . '/single-author-' . $curauth->user_nicename . '.php'))
        return TEMPLATEPATH . '/single-author-' . $curauth->user_nicename . '.php';
    elseif(file_exists(TEMPLATEPATH . '/single-author-' . $curauth->ID . '.php'))
        return TEMPLATEPATH  . '/single-author-' . $curauth->ID . '.php';
    
    // Pegar todas as taxonomias e ordenar por prioridade caso exista essa definição em get_option();
    $taxonomies = get_option( 'taxonomy_priorities' );
    if( !$taxonomies )
        $taxonomies = get_taxonomies();
    
    // loop em todos os termos(começando pelo nível mais baixo) e verificando a existência de templates
    foreach( $taxonomies as $tax ){
        $terms = get_the_terms( $post->ID, $tax );
        if( $terms ){
            $args = array(
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => 0,
                'depth' => 0,
            );
            // organizar os termos em ordem hierárquica, do nível mais baixo para o mais alto
            $ordered_terms = array_reverse( walk_simple_taxonomy( $terms, $args['depth'], $args ) );
            
            // verificar a existência de templates para cada nível
            foreach( $ordered_terms as $level ){
                foreach( $level as $term ){
                    if( file_exists(TEMPLATEPATH . "/single-{$tax}-{$term->slug}.php") ){
                        return TEMPLATEPATH . "/single-{$tax}-{$term->slug}.php";
                    }
                }
            }
            // verificar se existe um template para single da taxonomia geral
            // @bug IMPORTANTE: caso exista uma taxonomia com o mesmo nome de um post_type, irá conflitar com o template padrão "single-{$post_type}.php"
            // $todo: arrumar esse conflito de nomes
            if( file_exists(TEMPLATEPATH . "/single-{$tax}.php") ){
                return TEMPLATEPATH . "/single-{$tax}.php";
            }
        }
    }
    
    return $single;
}


