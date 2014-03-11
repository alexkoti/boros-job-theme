<?php
/**
 * Artigo para entender melhor os hooks de comentários:
 * @link http://wpengineer.com/2205/comment-form-hooks-visualized/
 * 
 * Adicionar campos no form de comentários
 * @link http://wpengineer.com/2214/adding-input-fields-to-the-comment-form/
 */
?>
		<div id="comments">
			
		<?php
		if( post_password_required() ){
			echo '<p class="nopassword">Este post está protegido por senha.</p></div>';
			return;
		}
		?>

		<?php if( have_comments() ){ ?>
			<h2>Comentários</h2>
			
			<?php
			// comments navigations above
			if( get_comment_pages_count() > 1 && get_option( 'page_comments' ) )
				$paged_comments = true;
			if( isset($paged_comments) && $paged_comments ){
			?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( 'Comentários Antigos' ); ?></div>
				<div class="nav-next"><?php next_comments_link( 'Comentários Recentes' ); ?></div>
			</div> <!-- .navigation -->
			<?php } ?>

				<ol class="dl_list dl_list_comments">
					<?php
					// incluir modelo de comment single
					include_once('comment.php');
					wp_list_comments( array('callback' => 'boros_comment_template') );
					?>
				</ol>

			<?php
			// comments navigations below
			if( isset($paged_comments) && $paged_comments ){
			?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( 'Comentários Antigos' ); ?></div>
				<div class="nav-next"><?php next_comments_link( 'Comentários Recentes' ); ?></div>
			</div><!-- .navigation -->
			<?php } ?>

		<?php } else { ?>
		
			<?php if( ! comments_open() ){ ?>
			<p class="nocomments">Comentários fechados para este post.</p>
			<?php } ?>
			
		<?php } ?>

<?php
/**
 * Formulário de comments
 * 
 * 
 */
 
$commenter = wp_get_current_commenter();

$name_field_value = ( !empty($commenter['comment_author']) ) ? esc_attr( $commenter['comment_author'] ) : '';
$email_field_value = ( !empty($commenter['comment_author_email']) ) ? esc_attr( $commenter['comment_author_email'] ) : '';

$comment_type = 'comentario';
$fields =  array(
	'simple_text'	=>	'<p>Seu e-mail não será publicado. Seu comentário poderá ser moderado.</p>',
	'author'		=>	'<div id="comment-form-author" class="form_element form_element_text">
							<input id="author" class="form_element_input required" name="author" type="text" value="' . $name_field_value . '" placeholder="Seu nome" size="30" />
							<label for="author">Nome</label>
						</div>',
	'email'			=>	'<div id="comment-form-email" class="form_element form_element_text">
							<input id="email" class="form_element_input required email" name="email" type="text" value="' . $email_field_value . '" placeholder="Seu email" size="30" />
							<label for="email">E-mail</label>
						</div>',
	'url'			=>	'<div class="form_element form_element_text comment-form-url">
							<input id="url" class="form_element_input" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="Site(opcional)" size="30" />
							<label for="url">Site</label>
						</div>',
);

$args = array(
	'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
	'comment_field'        => ' <div id="comment-form-message" class="form_element form_element_textarea">
									<label for="comment">Comentário</label><br />
									<textarea aria-required="true" rows="8" cols="45" name="comment" id="comment" class="ipt_textararea form_element_input required" placeholder="Digite sua mensagem aqui"></textarea>
								</div>',
	'comment_notes_before' => '',
	'comment_notes_after'  => '',
	'id_submit'            => 'comment_submit',
	'label_submit'         => 'ok',
);
comment_form( $args );
?>

		</div>
