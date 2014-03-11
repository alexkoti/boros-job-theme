<?php
/**
 * Template para página 'not found', qualquer caso.
 * 
 */

get_header(); ?>
		
		<div id="column">
			<?php boros_breadcrumb(); ?>
			<h1>Conteúdo não encontrado.</h1>
			
			<?php the_widget( 'WP_Widget_Search', array('title' => 'Faça uma busca pelo que procura') ); ?> 
			
		</div><!-- .column -->
		
		<?php get_sidebar(); ?>
		
<?php get_footer() ?>