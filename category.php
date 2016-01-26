<?php
/**
 * Template de categoria aplicável em listagens gerais para qualquer termo, a menos que este já possua um template próprio.
 * Aplicação geral, para criar templates para cada termo, usar esses modelos:
 *  - category-slug.php
 *  - category-id.php
 * 
 * 
 */

$queried = $wp_query->get_queried_object();
get_header(); ?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php boros_breadcrumb(); ?>
                <h3>Categoria <strong><?php echo $queried->name; ?></strong></h3>
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
                        get_template_part( 'content', 'list' );
                    }
                    custom_content_nav( 'nav_below' );
                }
                ?>
            </div>
            <div class="col-md-4">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
    
<?php get_footer() ?>