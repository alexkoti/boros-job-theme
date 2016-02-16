<?php
/**
 * CONFIGURAÇÔES PARA O HEAD DO FRONTEND
 * Configurações a serem aplicadas do <head> do frontend, basicamente são as adições dos javascripts e stylesheets, 
 * mas incluindo as manipulações de qualquer elemento do <head>, como description, keywords, rels diversos, etc
 * Jquery e scripts dependentes são declarados aqui, mesmo sendo renderizados no final no wp_footer();
 * 
 * 
 * 
 */

/**
 * ==================================================
 * ADD ACTIONS/FILTERS ==============================
 * ==================================================
 * 
 * 
 */
if( !is_admin() ){
	add_action( 'init', 'add_frontend_scripts' );  // adicionar scripts ao header
	add_action( 'wp_head', 'work_opengraph', 99 ); // iniciar o opengraph, caso esteja ativado
	remove_action('wp_head', 'wp_generator');      // remover a assinatura de versão do wordpress
}

function work_opengraph(){
	if( get_option('og_active') == true ){
		opengraph_tags();
	}
}



/**
 * ==================================================
 * STYLESHEETS ======================================
 * ==================================================
 * 
 * 
 */
function add_frontend_scripts(){
    
    // CSS
	$css = new BorosCss();
	$css->vendor('bootstrap.min', 'bootstrap/css');
	$css->add('wp');
	$css->add('site');
	
	if( defined('LOCALHOST') and LOCALHOST == true ){
		$css->add('responsive_debug');
	}
	
	/** MODELO absolute / google fonts
	$args = array(
		'name' => 'fonts',
		'src' => 'http://fonts.googleapis.com/css?family=Dosis:400,700|Amatic+SC:400,700',
		'parent' => false,
		'version' => '1',
		'media' => 'screen',
	);
	$css->abs($args);
	/**/
	
	/** MODELOS
	//simples, sem dependencia
	$css->add('forms');
	
	//encadeamento de 2 styles child
	$css->add('lightbox', 'lightbox')->child('lights', 'lightbox/themes')->child('shadows', 'lightbox/themes')->media('all');
	
	//subpasta, media print, alternate stylesheet
	$css->add('animations', 'anims')->media('print')->alt();
	
	//ies, encadeando child ie6 condicional
	$css->add('ies', 'ie', 'all')->child('ie6', 'ie', 'handheld')->cond('lte IE 8');
	
	//child de ies, media all, condicional
	$css->child('ie7', 'ie', 'all', 'ies')->cond('lte 8');
	
	//debug
	global $wp_styles;pre($wp_styles);
	/**/
    
    // JAVASCRIPTS
	$js = new BorosJs();
    $js->vendor('jquery.validate.min', 'jquery-validation/dist');
	$js->vendor('bootstrap.min', 'bootstrap/js');
	$js->vendor('html5shiv.min', 'html5shiv');
	$js->jquery('functions');
	
	/**
	$js->jquery('myjqueryfuncs');                      //jquery novo
	$js->jquery('jquery-ui-core');                     //jquery já registrado
	$js->add('effects');                               //simples
	$js->add('lightbox', 'lightbox');                  //simples subpasta
	$js->add('thickbox')->child('extendthick');        //encadeado, simples
	$js->add('thickbox')->child('extendthick', 'ext'); //encadeado, subpasta
	global $wp_scripts;pre($wp_scripts); //debug
	/**/
	
	// enqueues comuns/absolutos
	/**
	wp_enqueue_script(
		$handle = 'twitter_api', 
		$src = 'http://platform.twitter.com/widgets.js', 
		$deps = false, 
		$ver = null, 
		$in_footer = true
	);
	wp_enqueue_script(
		$handle = 'facebook_api', 
		$src = 'http://connect.facebook.net/pt_BR/all.js#xfbml=1', 
		$deps = false, 
		$ver = null, 
		$in_footer = true
	);
	/**/
}



/**
 * Disable the emoji's
 */
add_action( 'init', 'disable_emojis' );
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );	
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}

function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}



