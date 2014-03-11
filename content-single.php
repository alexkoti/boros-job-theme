
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry_header">
						<h1><?php the_title(); ?></h1>
						<div class="entry_meta">
							<?php post_meta_posted_on(); ?>
						</div>
					</header>
					<div class="entry_content">
						<?php the_content(); ?>
					</div>
					<?php comments_template( '', true ); ?>
				</article>