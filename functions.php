<?php
/**
 * THEME CUSTOM FUNCTIONS
 * Funções específicas para o tema.
 * 
 * 
 */



/**
 * Verificar plugin ativo
 * 
 */
add_action('init', function(){ 
    if( is_admin() ){
        return;
    }
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    if( !is_plugin_active('boros/boros.php') ) {
        die('O plugin dependente Boros não está ativo');
    }
});



/**
 * Constantes
 * 
 */
define( 'THEME',            get_template_directory_uri() );
define( 'CSS',              THEME . '/css' );
define( 'CSS_IMG',          THEME . '/css/img' );
define( 'FUNCTIONS',        THEME . '/functions' );
define( 'FUNCTIONS_IMG',    THEME . '/functions/css/img' );
define( 'SINGLE_PATH',      TEMPLATEPATH . '/single' ); // Define a constant path to our single template folder



/**
 * Includes
 * 
 */
include_once('functions/login.php');                    // login personalizado
include_once('functions/media.php');                    // funções de midia, imagens, galerias
include_once('functions/menus-sidebars-widgets.php');   // configuração de menus e sidebars e widgets
if( !is_admin() ){
    include_once('mini-templates.php');                 // pequenos pedaços de templates reutilizáveis - são chamados como funções, porém focadas apenas no output, por isso deixado junto com os arquivos de templates comuns
    include_once('functions/head.php');                 // configurações para o <head> (scripts, css, etc) para frontend e admin
    include_once('functions/frontend.php');             // funções específicas para o frontend site em questão e provavelmente não serão integradas ao framework
    //include_once('functions/template_redirects.php');	// redirecionamentos para templates adicionais
}