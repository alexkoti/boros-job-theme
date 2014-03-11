
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry_header">
						<h1><?php the_title(); ?></h1>
					</header>
					<div class="entry_content">
						<?php
						the_content();
						wp_link_pages();
						?>
					</div>
				</article>