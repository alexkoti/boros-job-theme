/**
 * Jquery local para fall back caso do CDN da Google falhe.
 * A var 'jquery local' está declarada no head do documento.
 * 
 */

!window.jQuery && document.write(unescape( '%3Cscript src="' + jquery_local.url + '"%3E%3C/script%3E' ));

/**
if( $('.ie6').length > 0 ){alert('ie6!!! D:')};
if( $('.ie7').length > 0 ){alert('ie7!!! ):')};
if( $('.ie8').length > 0 ){alert('ie8!!! |:')};
alert( $('html:first').attr('class') );
/**/