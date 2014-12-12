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

/* ========================================================================== */
/* ADD ACTIONS/FILTERS ====================================================== */
/* ========================================================================== */
if( !is_admin() ){
	add_action( 'wp_print_styles', 'add_frontend_styles' );		// adicionar styles ao header
	add_action( 'wp_print_scripts', 'add_frontend_scripts' );	// adicionar scripts ao header
	remove_action('wp_head', 'wp_generator');					// remover a assinatura de versão do wordpress
	add_action( 'wp_head', 'work_opengraph', 99 );
}

function work_opengraph(){
	if( get_option('og_active') == true ){
		opengraph_tags();
	}
}



/* ========================================================================== */
/* CONFIGURAÇÔES ============================================================ */
/* ========================================================================== */
// STYLESHEETS
function add_frontend_styles(){
	$css = new BorosCss();
	$css->add('bootstrap.min');
	$css->add('photoswipe');
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
	$css->add('forms');																							//simples, sem dependencia
	$css->add('lightbox', 'lightbox')->child('lights', 'lightbox/themes')->child('shadows', 'lightbox/themes')->media('all');	//encadeamento de 2 styles child
	$css->add('animations', 'anims')->media('print')->alt();															//subpasta, media print, alternate stylesheet
	$css->add('ies', 'ie', 'all')->child('ie6', 'ie', 'handheld')->cond('lte IE 8');										//ies, encadeando child ie6 condicional
	$css->child('ie7', 'ie', 'all', 'ies')->cond('lte 8');																//child de ies, media all, condicional
	global $wp_styles;pre($wp_styles); //debug
	/**/
}

// JAVASCRIPTS - todos os scripts serão adicionados ao wp_footer() por padrão;
function add_frontend_scripts(){
	$js = new BorosJs();
	$js->jquery('jquery.validate.min', 'libs');
	$js->jquery('bootstrap.min', 'libs');
	$js->jquery('klass.min', 'libs');
	$js->jquery('code.photoswipe-3.0.5.min', 'libs');
	$js->jquery('functions');
	$js->add('modernizr', 'libs', false, false);
	$js->add('html5', 'libs', false, false);
	/**
	$js->jquery('myjqueryfuncs');						//jquery novo
	$js->jquery('jquery-ui-core');						//jquery já registrado
	$js->add('effects');								//simples
	$js->add('lightbox', 'lightbox');					//simples subpasta
	$js->add('thickbox')->child('extendthick');			//encadeado, simples
	$js->add('thickbox')->child('extendthick', 'ext');	//encadeado, subpasta
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