<?php
/**
 * Funções específicas para atender o frontend este site.
 * As funções listadas no começo do arquivo possuem maior prioridade de edição, as funções mais ao fim do arquivo muitas vezes não necessitam de personalização.
 * 
 * 
 */



add_action( 'template_redirect', 'close_site' );
function close_site(){
    $logged_in_only = get_option('logged_in_only');
    if( !empty($logged_in_only) ){
        if( !is_user_logged_in() ){
            get_template_part('locked');
            exit();
        }
    }
}



/**
 * ==================================================
 * CALENDÁRIO =======================================
 * ==================================================
 * 
 * 
 */
add_filter( 'boros_calendar_show_events_button', 'boros_base_calendar_show_events_button', 10, 2 );
function boros_base_calendar_show_events_button( $output, $args ){
    $button = array('<div class="show-events-btn hidden-xs">');
    foreach( $args['events_available'] as $evt ){
        $cat = $evt->category[0]->slug;
        $button[] = "<span class='event-circle {$cat}'></span>";
    }
    $button[] = '</div>';
    
    return implode('', $button);
}

add_filter( 'boros_calendar_event_day_item_output', 'boros_base_calendar_event_day_item_output', 10, 2 );
function boros_base_calendar_event_day_item_output( $output, $args ){
    $cat = $args['post']->category[0]->slug;
    $thumb = wp_get_attachment_image_src($args['post']->metas['_thumbnail_id'][0]);
    $excerpt = get_the_excerpt($args['post']);
    $output = "
    <li class='event-item {$cat}'>
        <div class='row hidden-xs'>
            <div class='col-md-3 col-sm-3'><a href='{$args['post']->url}'><img src='{$thumb[0]}' alt='' class='img-responsive' /></a></div>
            <div class='col-md-9 col-sm-9'><a href='{$args['post']->url}'>{$args['post']->title}</a><p>{$excerpt}</p></div>
        </div>
        <div class='row visible-xs'>
            <div class='col-md-12 calendar-xs-title'>{$args['post']->title}</div>
            <div class='col-md-12 calendar-xs-content'>
                <div class='row'>
                    <div class='col-xs-3'><a href='{$args['post']->url}'><img src='{$thumb[0]}' alt='' class='img-responsive' /></a></div>
                    <div class='col-xs-9'><a href='{$args['post']->url}'>{$args['post']->title}</a><p>{$excerpt}</p></div>
                </div>
            </div>
        </div>
    </li>";
    return $output;
}



/**
 * ==================================================
 * GOOGLE ANALYTICS =================================
 * ==================================================
 * 
 * 
 */
add_action( 'wp_footer', 'footer_google_analytics' );
function footer_google_analytics(){
    opt_option('google_analytics');
}



/**
 * ==================================================
 * CUSTOM POSTS PER PAGE ============================
 * ==================================================
 * Definir a quantidade total de post por página em diferentes situações.
 * A quantidade padrão é definida pela option 'posts_per_page', gravada em wp_options, e definida via admin em 'Configurações > Leitura"
 * Como as situações possíveis de listagens são infinitas, é preciso codificar a verificação para cada caso.
 * 
 * IMPORTANTE:
 * Quando é definido uma página estática para a frontpage, a primeira vez que é chamado o filtro 'pre_get_posts', ocorre um erro, onde não se consegue acesso ao queried object.
 * Para resolver isso, é verificado se está na frontpage comparando a query_var 'page_id', que sempre está nas querys com a option 'page_on_front'. Em caso positivo, 
 * estamos na frontpage, portanto $query é retornado sem modificações. Essa primeira chamada de 'pre_get_posts' nesse caso específico não precisa ser modificada em nenhum caso.
 * 
 * As posteriores chamadas de pre_get_posts que precisem verificar is_front_page() rodarão normalmente. Por exemplo, se for feita uma nova requisição no meio da página, como
 * query_posts() ou WP_Query(), as verificações de is_front_page() funcionarão como esperado.
 * 
 */
//add_filter( 'pre_get_posts', 'filter_pre_get_posts' );
function filter_pre_get_posts( $query ){
    $page_on_front = get_option('page_on_front');
    if( $query->query_vars['page_id'] == $page_on_front ){
        return $query;
    }
    
    // definir a quantidade de posts padrão em chamadas de query_posts() e WP_Query() na frontpage. Sobrepõem qualquer definição das funções.
    if( is_front_page() ){
        $query->query_vars['posts_per_page'] = 3;
    }
    
    // definir a quantidade de posts padrão na home(home de posts)
    if( is_home() ){
        $query->query_vars['posts_per_page'] = 2;
    }
    
    // posts per page em fábrica de ideias
    if(
        (isset($wp_query->query_vars['post_type']) and $wp_query->query_vars['post_type'] == 'ideia') OR 
        (isset($wp_query->query_vars['taxonomy']) and $wp_query->query_vars['taxonomy'] == 'category-ideias')
    ){
        if( $wp_query->is_single != true )
            $query->query_vars['posts_per_page'] = 14;
    }
    
    // remover vídeos da listagem normal de blogs
    if( !is_front_page() ){
        if ( isset($query->category_name) and $query->category_name != 'videos' AND $wp_query->is_admin == false ) {
            $exclude = get_cat_ID('videos');
            $query->set('cat', '-'.$exclude);
        }
    }

    return $query;
}



/**
 * ==================================================
 * REDIRECT =========================================
 * ==================================================
 * Redirecionar a página corrente para outro local.
 * Como as situações possíveis são infinitas, é preciso codificar a verificação para cada caso.
 */
//add_filter( 'parse_query', 'redirect_pages' );
function redirect_pages( &$q ) {
    if( empty($q->is_admin) and isset($q->query_vars['page_id']) ){
        // ID da página pedida
        $page_id = $q->query_vars['page_id'];
        
        if( $page_id == get_page_ID_by_name('Painel 5', 'page') ){
            $url = get_permalink( get_page_ID_by_name('Painel Home', 'page') );
            wp_redirect( $url, 301 );
            exit();
        }
    }
    /**
    pre($q);
    pre($q->query_vars['category_name']);
    pre($q->query_vars['posts_per_page']);
    pre($q->query_vars['numberposts']);
    pre($q->query_vars['posts_per_page']);
    pre($q->query_vars['numberposts']);
    /**/
}


