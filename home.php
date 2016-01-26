<?php
/**
 * Template para a página de listagem comum de posts(post_type 'post').
 * Configurar qual 'page' deverá mostrar essa listagem em 'Admin > Configurações > Leitura', item 'Página de posts:'
 */

get_header(); ?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php boros_breadcrumb(); ?>
            </div>
            <div class="col-md-8">
                <?php
                /**
                 * Caso tenha posts para exibir
                 * 
                 */
                if (have_posts()){
                    custom_content_nav( 'nav_above' );
                    while (have_posts()){
                        the_post();
                        get_template_part( 'content' );
                    }
                    custom_content_nav( 'nav_below' );
                }
                /**
                 * Sem resultados
                 * IMPORTANTE: isso não é exatamente a mesma coisa que o 404 not found(este possui template próprio), é aplicado à uma requisição válida porém sem resultados
                 * conforme o contexto, por exemplo listagem de posts na página 999, onde não existem mais posts para exibir.
                 */
                 else {
                    ?>
                    <article class="post hentry no-results not-found">
                        <header class="entry_header">
                            <h2>Sem resultados</h2>
                        </header>
                        <div class="entry_content">
                            Não foram encontrados resultados para essa requisição :(
                        </div>
                    </article>
                    <?php
                }
                ?>
            </div>
            <div class="col-md-4">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
    
<?php get_footer() ?>