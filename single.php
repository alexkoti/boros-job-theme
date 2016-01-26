<?php
/**
 * Template para qualquer single que já não possua um template mais específico.
 */

get_header(); ?>
    
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php boros_breadcrumb(); ?>
            </div>
            <div class="col-md-8">
                <?php
                if (have_posts()){
                    custom_content_nav( 'nav_above' );
                    while (have_posts()){
                        the_post();
                        get_template_part( 'content', 'single' );
                    }
                }
                ?>
            </div>
            <div class="col-md-4">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
    
<?php get_footer() ?>