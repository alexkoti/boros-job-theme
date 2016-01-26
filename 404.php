<?php
/**
 * Template para página 'not found', qualquer caso.
 * 
 */

get_header(); ?>
    
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php boros_breadcrumb(); ?>
            </div>
            <div class="col-md-8">
                <h1>Conteúdo não encontrado.</h1>
                <?php the_widget( 'WP_Widget_Search', array('title' => 'Faça uma busca pelo que procura') ); ?>
            </div>
            <div class="col-md-4">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
    
<?php get_footer() ?>