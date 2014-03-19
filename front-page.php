<?php
/**
 * Template da página inicial do site.
 * Configurar qual 'page' deverá mostrar essa listagem em 'Admin > Configurações > Leitura', item 'Página inicial:'
 * 
 * Teste de comentário para sincronização :D
 * 
 * teste de conflito: online
 * 
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
			
			<section>
				<h5>Lista de posts chamada por <code>WP_Query()</code>, dentro de <code>front-page.php</code>:</h5>
				<dl>
				<?php
				$query = array(
					'post_type' => array('post', 'page'),
					'post_status' => 'publish',
					'post_parent' => 0,
					'orderby' => 'menu_order',
					'order' => 'DESC',
				);
				$custom_posts = new WP_Query();
				$custom_posts->query($query);
				if( $custom_posts->posts ){
					foreach($custom_posts->posts as $post){
						setup_postdata($post);
						?>
						<dt><?php the_title(); ?></dt>
						<dd><?php the_excerpt(); ?></dd>
						<?php
					}
				}
				wp_reset_query();
				?>
				</dl>
			</section>
			
		</div><!-- .column -->
		
		<?php get_sidebar(); ?>
		
<?php get_footer() ?>
