<?php
/* ========================================================================== */
/* SINGLE COMMENT TEMPLATE ================================================== */
/* ========================================================================== */
/**
 * ATENÇÃO: Não possui tag de fechamento da <li>
 * Inspirado no modelo do twenty-ten
 * 
 */
function boros_comment_template($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 40 ); ?>
			<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php printf( 'Em %1$s às %2$s', get_comment_date(),  get_comment_time() ); ?>
			</a> 
			<?php printf( ' %s <span class="says">disse:</span>', sprintf( '<br /> <cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
		</div><!-- .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em class="comment-awaiting-moderation">Seu comentário está aguardando aprovação</em>
			<br />
		<?php endif; ?>

		<div class="comment-body"><?php comment_text(); ?></div>

		<div class="comment-meta commentmetadata">
			<?php edit_comment_link( '(Editar)', '', ' - ' ); ?>
			<span class="reply">
				<?php
				$args['reply_text'] = 'Responder este comentário';
				comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
				?>
			</span><!-- .reply -->
		</div><!-- .comment-meta .commentmetadata -->

		</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p>Pingback: <?php comment_author_link(); ?><?php edit_comment_link( '(Editar)', ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
?>