
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry_header">
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<div class="entry_meta">
							<?php post_meta_posted_on(); ?>
						</div>
					</header>
					<div class="entry_content">
						<?php the_excerpt(); ?>
					</div>
				</article>