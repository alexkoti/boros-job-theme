<?php
/* ========================================================================== */
/* POST THUMBNAILS ========================================================== */
/* ========================================================================== */
/**
 * Adicionar suporte aos thumbnails + configurar as medidas
 * Verficar se existe o post-thumbnail: has_post_thumbnail() return bool
 */
// adicionar suporte
add_theme_support( 'post-thumbnails', array( 'post', 'page' ) );

// tamanho do post-thumb W, H, crop
set_post_thumbnail_size( 300, 300, true ); //slider home

if ( ! isset( $content_width ) ) $content_width = get_option('embed_size_w');



/* ========================================================================== */
/* ADD IMAGE SIZES ========================================================== */
/* ========================================================================== */
/**
 * Adicionar novos tamanhos de imagens
 * @version 2.9+
 */
add_image_size( 'tamanho_a', 400, 400, false );
add_image_size( 'tamanho_b', 800, 800, false );
//add_image_size( 'tamanho_a', 1000, 1000, false );

add_filter( 'image_size_names_choose', 'image_sizes_names' );
function image_sizes_names( $sizes ){
	$sizes['tamanho_a'] = 'Tamanho A';
	$sizes['tamanho_b'] = 'Tamanho B';
	$sizes['post-thumbnail'] = 'Post Thumbnail';
	return $sizes;
}



/* ========================================================================== */
/* CONFIGURE AND IMAGE SIZES ================================================ */
/* ========================================================================== */
/**
 * Configurar tamanhos e adicionar novos tamanhos de imagens
 * Configurações feitas aqui nos tamanhos core('thumbnail', 'medium', 'large') sempre irão sobrepor as configurações 
 * setadas em "Configurações > Mídia". Isso evita alterações indesejadas via admin. :D
 */
/**
add_action( 'admin_init', 'lock_media_sizes' );
function lock_media_sizes(){
	global $pagenow;
	if( $pagenow == 'options-media.php' ){
		update_option('large_size_w', 140);
		update_option('large_size_h', 1000);
		update_option('large_crop', false);

		update_option('medium_size_w', 140);
		update_option('medium_size_h', 1000);
		update_option('medium_crop', false);

		update_option('thumbnail_size_w', 140);
		update_option('thumbnail_size_h', 1000);
		update_option('thumbnail_crop', false);

		update_option('embed_size_w', 500);
		update_option('embed_size_h', 500);
	}
}
/**/



/**
 * ==================================================
 * EMBED RESPONSIVO =================================
 * ==================================================
 * Youtube e Vimeo apenas
 * 
 * @todo Verificar o método antigo de aplicar os filtros de the_content para ativar o oembed
 * 
 */
function responsive_video( $url, $echo = true ){
	if( filter_var($url, FILTER_VALIDATE_URL) === FALSE){
		$url = get_option('ao_vivo_video');
	}
	$video_url_vars = parse_url($url);
	$find_youtube = 'youtube';
	$pos = strpos($url, $find_youtube);
	if($pos === false){
		$video_tag = "<div class='videoWrapper'><iframe src='//player.vimeo.com/video{$video_url_vars['path']}' width='420' height='315' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>";
	}
	else{
		parse_str($video_url_vars['query'], $video_vars);
		$video_tag = "<div class='videoWrapper'><iframe width='420' height='315' src='http://www.youtube.com/embed/{$video_vars['v']}/' frameborder='0' allowfullscreen></iframe></div>";
	}
	
	if( $echo == false ){
		return $video_tag;
	}
	echo $video_tag;
}


/**
 * FORMATAÇÂO EXTRA DO THICKBOX
 * 
 * 1) Esconder o controle de galeria
 * 2) Formatar mensagem de special_image
 * 3) Esconder botão de editar imagem
 * 
 */
//add_action('admin_head-media-upload-popup', 'pop_media_scripts');
function work_pop_media_scripts(){
	echo '
<script>
jQuery(document).ready(function($){
$("#gallery-settings").hide();
});
</script>
<style type="text/css">
.special_image_message {
	background-color: #FFFFE0;
	border: 1px solid #E6DB55;
	border-radius: 3px 3px 3px 3px;
	display: inline-block;
	padding: 5px 8px;
}
.media-item-info .A1B1 p + p {
	display:none;
}
</style>
	';
}



/* ========================================================================== */
/* ATTACHMENT TEMPLATE ====================================================== */
/* ========================================================================== */
/**
 * Substituir o template padrão de anexo ao corpo do post
 *
 * @return string - HTML do anexo
 */
//add_shortcode('wp_caption', 'custom_img_caption_shortcode');
//add_shortcode('caption', 'custom_img_caption_shortcode');
function custom_img_caption_shortcode($attr, $content = null) {
	// Allow plugins/themes to override the default caption template.
	$output = apply_filters('img_caption_shortcode', '', $attr, $content);
	if ( $output != '' )
		return $output;

	extract(shortcode_atts(array(
		'id'	=> '',
		'align'	=> 'alignnone',
		'width'	=> '',
		'caption' => ''
	), $attr));

	if( 1 > (int) $width || empty($caption) )
		return $content;
 
	if( $id ) $id = "id='{$id}'";
	
	// pegar title dentro do shortcode. O tinymce reseta os valores do placeholder do shortcode
	preg_match( '/title="([^"]*)"/', $content, $titulo );
	
	//$style = ' style="width: ' . (10 + (int) $width) . 'px"'; // versão com padding
	$style = 'style="width: ' . $width . 'px"';
	$class = "class='anexo attachment {$align}'";
	$img = do_shortcode( $content );
	
	$html = "<figure {$id} {$class} {$style}>{$img}<p class='caption'>{$caption}</p></figure>";
	return $html;
}



/* ========================================================================== */
/* GALLERY TEMPLATE ========================================================= */
/* ========================================================================== */
/**
 * Substituir o template padrão de galeria. Pode-se continuar usando o shortcode do core normalmente.
 * Esta função é um clone de "gallery_shortcode()", presente em wp-includes/media.php
 * 
 * @OBS		É preciso formatar no CSS o layout da galeria. No estilo front-end do tema WPMODEL está no setor GALLERY_TEMPLATE
 * @link 	http://wpengineer.com/1802/a-solution-for-the-wordpress-gallery/
 * @return string - HTML da galeria
 */
// remover galeria core
//remove_shortcode('gallery', 'gallery_shortcode');
// adicionar custom gallery
//add_shortcode('gallery', 'custom_gallery_shortcode');
function custom_gallery_shortcode($attr) {
	global $post, $wp_locale;

	static $instance = 0;
	$instance++;

	// Allow plugins/themes to override the default gallery template.
	$output = apply_filters('post_gallery', '', $attr);
	if ( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => ''
	), $attr));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$columns = intval($columns);
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$output = apply_filters('gallery_style', "
		<div id='$selector' class='gallery galleryid-{$id}'>");

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		$link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);

		$output .= "\n\t\t<{$itemtag} class='gallery-item col-{$columns}'>";
		$output .= "
			<{$icontag} class='gallery-icon'>
				$link
			</{$icontag}>\n";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<{$captiontag} class='gallery-caption'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>\n";
		}
		$output .= "\t\t</{$itemtag}>";
		if ( $columns > 0 && ++$i % $columns == 0 )
			$output .= "\n\t\t<br />";
	}

	$output .= "</div>\n";

	return $output;
}