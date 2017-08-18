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
 * Já adaptado para bootstrap
 * 
 * @link http://www.codecheese.com/2013/11/wordpress-comment-form-with-twitter-bootstrap-3-supports/
 */
add_filter( 'comment_form_default_fields', 'bootstrap3_comment_form_fields' );
function bootstrap3_comment_form_fields( $fields ) {
    $commenter = wp_get_current_commenter();
    
    $req      = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $html5    = current_theme_supports( 'html5', 'comment-form' ) ? 1 : 0;
    
    $fields   =  array(
        'author' => '<div class="form-group comment-form-author">' . '<label for="author">' . __( 'Name' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
                    '<input class="form-control required" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></div>',
        'email'  => '<div class="form-group comment-form-email"><label for="email">' . __( 'Email' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
                    '<input class="form-control required" id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></div>',
        'url'    => '<div class="form-group comment-form-url"><label for="url">' . __( 'Website' ) . '</label> ' .
                    '<input class="form-control" id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></div>'        
    );
    
    return $fields;
}

/**
 * Campo de comentário por último
 * 
 */
add_filter( 'comment_form_fields', 'wpb_move_comment_field_to_bottom' );
function wpb_move_comment_field_to_bottom( $fields ) {
    $comment_field = $fields['comment'];
    unset( $fields['comment'] );
    $fields['comment'] = $comment_field;
    return $fields;
}

/**
 * HTML para o campo textatrea de acordo com o bootstrap
 * 
 */
add_filter( 'comment_form_defaults', 'bootstrap3_comment_form' );
function bootstrap3_comment_form( $args ) {
    $args['comment_field'] = '<div class="form-group comment-form-comment">
            <label for="comment">' . _x( 'Comment', 'noun' ) . '</label> 
            <textarea class="form-control required" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
        </div>';
    $args['class_submit'] = 'btn btn-default'; // since WP 4.1
    
    return $args;
}

comment_form();
?>

</div>
