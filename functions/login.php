<?php
/**
 * CONFIGURAÇÔES DE LOGIN
 * Configuração para a página de login
 * 
 * 
 * 
 */

// adicionar css
add_action( 'login_head', 'custom_login_head' );
function custom_login_head(){ 
    echo '<link rel="stylesheet" type="text/css" href="' . THEME . '/css/login.css" />'; 
}

// link do logo
add_filter( 'login_headerurl', 'custom_login_headerurl' );
function custom_login_headerurl(){
    return home_url();
}

// atributo title no logo -> o texto sempre será o nome do site
add_filter( 'login_headertext', 'custom_login_headertitle' );
function custom_login_headertitle(){
    return get_bloginfo('name');
}