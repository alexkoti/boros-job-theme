<?php
/**
 * CONFIGURAÇÔES DE LOGIN
 * Configuração para a página de login
 * 
 * 
 * 
 */



/* ========================================================================== */
/* ADD ACTIONS/FILTERS ====================================================== */
/* ========================================================================== */
add_action( 'login_head', 			'custom_login_head' );
add_filter( 'login_headerurl', 		'custom_login_headerurl' );
add_filter( 'login_headertitle', 	'custom_login_headertitle' );



/* ========================================================================== */
/* ADICIONAR LOGIN PERSONALIZADO ============================================ */
/* ========================================================================== */
// adicionar css
function custom_login_head(){ 
	echo '<link rel="stylesheet" type="text/css" href="' . THEME . '/css/login.css" />'; 
}

// link do logo
function custom_login_headerurl(){
	return home_url();
}

// atributo title no logo -> o texto sempre será o nome do site
function custom_login_headertitle(){
	return get_bloginfo('name');
}