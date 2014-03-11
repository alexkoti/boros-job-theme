<?php
/**
 * Template geral para 'pages'
 */

get_header(); ?>
		
		<div id="column">
			<?php boros_breadcrumb(); ?>
			
			<?php
			if (have_posts()){
				custom_content_nav( 'nav_above' );
				while (have_posts()){
					the_post();
					get_template_part( 'content', 'page' );
				}
			}
			?>
			
		</div><!-- .column -->
		
		<?php get_sidebar(); ?>
		
<?php get_footer() ?>