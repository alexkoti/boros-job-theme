<?php
/**
 * Funções e configurações gerais, que são atreladas ao tema, mas afetam tanto o admin como o frontend.
 * 
 * 
 */

/**
 * ==================================================
 * ADD OPTIONS ======================================
 * ==================================================
 * Adicionar os defaults necessários ao ativar o tema.
 */
//add_action( 'init', 'my_direct_insert_options' );
function my_direct_insert_options(){
	date_default_timezone_set('Brazil/East');
	$now = date('d/m/Y H:i:s e P');
	$options_to_save = array(
		'my_default_option_1' => 'my_default_value_1',
		'my_default_option_2' => $now,
	);
	direct_insert_options( $options_to_save );
	
	$taxonomies = get_option('taxonomy_priorities');
	if( empty($taxonomies) ){
		$taxonomy_priorities = array(
			'regiao' => 'regiao',
			'category' => 'category',
			'post_tag' => 'post_tag',
			'nav_menu' => 'nav_menu',
			'link_category' => 'link_category',
			'post_format' => 'post_format',
		);
		update_option( 'taxonomy_priorities', $taxonomy_priorities );
	}
}

/**
 * Adicionar CSS as editor tiny_mce, integrando com o CSS do frontend
 * 
 */
add_filter( 'after_setup_theme', 'boros_setup_theme' );
function boros_setup_theme(){
	add_editor_style( 'css/site.css' );
	add_editor_style( 'css/editor.css' );
}

// Adicionar suporte aos feeds
add_theme_support( 'automatic-feed-links' );

// Esconder a barra de admin do admin/frontend
add_filter( 'show_admin_bar', '__return_false' );
