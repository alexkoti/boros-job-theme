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
set_post_thumbnail_size( 300, 300, true );



/* ========================================================================== */
/* ADD IMAGE SIZES ========================================================== */
/* ========================================================================== */
/**
 * Adicionar novos tamanhos de imagens
 * @version 2.9+
 */
//add_image_size( 'tamanho_a', 400, 400, false );
//add_image_size( 'tamanho_b', 800, 800, false );
//add_image_size( 'tamanho_a', 1000, 1000, false );

//add_filter( 'image_size_names_choose', 'image_sizes_names' );
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
 * 
 * IMAGENS PADRÂO - definidas no admin
 * thumbnail 		- 280 >>> menor largura para o corpo
 * medium		- 400 >>> largura da primeira coluna
 * large			- 760 >>> coluna full
 *
 */
//add_action( 'admin_init', 'lock_media_sizes' );
function lock_media_sizes(){
    global $pagenow;
    if( $pagenow == 'options-media.php' ){
        update_option('large_size_w', '760');
        update_option('large_size_h', '2000');
        update_option('large_crop', false);

        update_option('medium_size_w', '400');
        update_option('medium_size_h', '1200');
        update_option('medium_crop', false);

        update_option('thumbnail_size_w', '280');
        update_option('thumbnail_size_h', '1000');
        update_option('thumbnail_crop', false);
    }
}



/**
 * Realizar upscale das imagens caso elas sejam menores que o tamanho requerido.
 * 
 * @link http://wordpress.stackexchange.com/a/64953
 * 
 * O exemplo foi modificado para lidar com altura livre(false). Basta marcar a nova altura como false ou zero e o $crop como true.
 * Caso o $crop não seja ativado, o WordPress irá lidar com o arquivo normalmente.
 * 
 */
add_filter( 'image_resize_dimensions', 'upscale_image_crop_dimensions', 10, 6 );
function upscale_image_crop_dimensions($default, $orig_w, $orig_h, $new_w, $new_h, $crop){
    if ( !$crop ) return null; // let the wordpress default function handle this

    $aspect_ratio = $orig_w / $orig_h;
    $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

    if( $new_h == 0 ){
    $new_h = round($new_w / $aspect_ratio);
    }

    $crop_w = round($new_w / $size_ratio);
    $crop_h = round($new_h / $size_ratio);

    $s_x = floor( ($orig_w - $crop_w) / 2 );
    $s_y = floor( ($orig_h - $crop_h) / 2 );

    return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
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
remove_shortcode( 'gallery', 'gallery_shortcode' );
// adicionar custom gallery
add_shortcode( 'gallery', 'custom_gallery_shortcode' );
function custom_gallery_shortcode($attr) {
    $post = get_post();
    
    if( $post->post_type == 'agenda' ){
        $attr['size'] = 'marugoto_foto';
        //return gallery_shortcode($attr);
    }

    static $instance = 0;
    $instance++;

    if ( ! empty( $attr['ids'] ) ) {
        // 'ids' is explicitly ordered, unless you specify otherwise.
        if ( empty( $attr['orderby'] ) )
            $attr['orderby'] = 'post__in';
        $attr['include'] = $attr['ids'];
    }

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
    
    $size = 'large';

    $id = intval($id);
    if ( 'RAND' == $order )
        $orderby = 'none';

    if ( !empty($include) ) {
        $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif ( !empty($exclude) ) {
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
    $icontag = tag_escape($icontag);
    $valid_tags = wp_kses_allowed_html( 'post' );
    if ( ! isset( $valid_tags[ $itemtag ] ) )
        $itemtag = 'dl';
    if ( ! isset( $valid_tags[ $captiontag ] ) )
        $captiontag = 'dd';
    if ( ! isset( $valid_tags[ $icontag ] ) )
        $icontag = 'dt';

    $columns = intval($columns);
    $itemwidth = $columns > 0 ? floor(100/$columns) : 100;
    $float = is_rtl() ? 'right' : 'left';

    $selector = "gallery-{$instance}";

    $gallery_style = $gallery_div = '';
    //if ( apply_filters( 'use_default_gallery_style', true ) )
    //	$gallery_style = "
    //	<style type='text/css'>
    //		#{$selector} {
    //			margin: auto;
    //		}
    //		#{$selector} .gallery-item {
    //			float: {$float};
    //			margin-top: 10px;
    //			text-align: center;
    //			width: {$itemwidth}%;
    //		}
    //		#{$selector} .gallery-caption {
    //			margin-left: 0;
    //		}
    //	</style>
    //	<!-- see gallery_shortcode() in wp-includes/media.php -->";
    $size_class = sanitize_html_class( $size );
    $gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class} row-fluid'>";
    $output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

    $i = 0;
    foreach ( $attachments as $id => $attachment ) {
    //	if( isset($attr['link']) && 'file' == $attr['link'] ){
    //		$link = wp_get_attachment_link($id, $size, false, false);
    //	}
    //	else{
    //		$link = wp_get_attachment_link($id, $size, true, false);
    //	}
        $large = wp_get_attachment_image_src($id, 'full');
        $thumb = wp_get_attachment_image_src($id, 'marugoto_foto');
        $link = "<a href='{$large[0]}' target='_blank' data-sizes='{$large[1]}x{$large[2]}'><img src='{$thumb[0]}' class='img-polaroid img-responsive img-thumbnail' alt='' /></a>";
        $output .= "<{$itemtag} class='gallery-item span3'>";
        $output .= "
            <{$icontag} class='gallery-icon'>
                $link
            </{$icontag}>";
        if ( $captiontag && trim($attachment->post_excerpt) ) {
            $output .= "
                <{$captiontag} class='wp-caption-text gallery-caption'>
                " . wptexturize($attachment->post_excerpt) . "
                </{$captiontag}>";
        }
        $output .= "</{$itemtag}>";
        if ( $columns > 0 && ++$i % $columns == 0 )
            $output .= '<br style="clear: both" />';
    }

    $output .= "
            <br style='clear: both;' />
        </div>\n";

    return $output;
}



/**
 * ==================================================
 * PHOTOSWIPE BOX ===================================
 * ==================================================
 * 
 * 
 */
add_action( 'wp_footer', 'photo_swipe_box' );
function photo_swipe_box(){
    ?>
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

    <!-- Background of PhotoSwipe. 
        It's a separate element as animating opacity is faster than rgba(). -->
    <div class="pswp__bg"></div>

    <!-- Slides wrapper with overflow:hidden. -->
    <div class="pswp__scroll-wrap">

        <!-- Container that holds slides. 
            PhotoSwipe keeps only 3 of them in the DOM to save memory.
            Don't modify these 3 pswp__item elements, data is added later on. -->
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
        <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">

                <!--  Controls are self-explanatory. Order can be changed. -->

                <div class="pswp__counter"></div>

                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                <!-- element will get class pswp__preloader--active when preloader is running -->
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                    <div class="pswp__preloader__cut">
                        <div class="pswp__preloader__donut"></div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div> 
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>

            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>

            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>

        </div>

    </div>

</div>
    <?php
}

