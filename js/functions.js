/*
Name: Javascript Functions
Version: 1.0
Description: Javascripts para o site
Author: Alex Koti
Author URI: http://alexkoti.com
 */


jQuery(document).ready(function($){
	//remover status de javascript do documento
	$('html').removeClass('no-js');
	
	
	
	/**
	 * CUSTOM MODERNIZR
	 * Workarounds para IEs
	 * 
	 * 
	 */
	// Aplicar index para nth-child
	$('ol').each(function(){
		$(this).find('li').each(function(){
			var index = $(this).index() + 1;
			$(this).addClass('item_' + index);
		});
	});
	function supportsAttr( element, attribute ){
		var test = document.createElement(element);
		if (attribute in test) {
			return true;
		} else {
			return false;
		}
	}
	
	// RESETAR INPUTS DE TEXTO IE 9-
	// Pega o valor padrão e remove em focus() e retoma em blur(), em caso de valor não preenchido
	if( !supportsAttr('input', 'placeholder') ){
		$( 'input[placeholder], textarea[placeholder]' ).each(function(){
			if( $(this).val().length === 0 )
				$(this).val( $(this).attr('placeholder') );
		}).blur(function(){
			if( $(this).val().length === 0 ){
				$(this).val( $(this).attr('placeholder') );
			}
		}).focus(function(){
			if( $(this).val() == $(this).attr('placeholder') ){
				$(this).val('');
			}
		});
	}
	
	
	
	/**
	 * VALIDATION
	 * Ações para validação js de comentários
	 * 
	 * 
	 * 
	 */
	$("#commentform").validate({
		rules: {
			// name_do_input: 'método de validação para aplicar, separado por espaços'
			comment: 'defaultInvalid'
		},
		messages: {
			// name_do_input: 'Mensagem'
			comment: 'Digite uma mensagem',
			author: 'Este campo é obrigatório',
			email: 'Digite um endereço de e-mail válido'
		},
		errorPlacement: function(label, element) {
			// label - elemento <label> gerado pelo validator
			// element - input validado
			// pegar o id do elmento e assim desconbrir o <label> como 'for' correspondente
			label.insertAfter( $('label[for=' + element.attr('id') + ']') );
		}
	});
	//remover label de error ao focar os inputs
	$('.form_element input, .form_element textarea').hover(function(){
		$(this).siblings('.error').fadeOut();
	});
	// método personalizado de validação - 'defaultInvalid'
	jQuery.validator.addMethod('defaultInvalid', function(value, element) {
		switch (element.value) {
			case 'Digite sua mensagem aqui':
				if (element.name == 'comment')
				return false;
				break;
			default: return true; 
				break;
		}
	});
	
	
	
	/**
	 * NEWSLETTER
	 * 
	 * 
	 */
	$('.newsletter_box form:not(.form_error) .hide').hide();
	$('.form_msg').click(function(){
		$(this).slideUp(function(){
			$(this).next('fieldset').slideDown(function(){
				$(this).find('.ipt_submit').fadeIn();
			});
		});
	});
	$('#ipt_email').click(function(){
		$('.newsletter_box .hide').slideDown();
	});
	
	
	
	/**
	 * TABS
	 * 
	 * 
	 */
	$('.tab_box .tab_content:gt(0)').hide();
	$tabs = $('.tab_box .tabs a');
	$tabs.first().addClass('active');
	
	$tabs.click(function(){
		change_tab( $(this).attr('href') );
		return false;
	});
	
	$('.aba_proxima').click(function(){
		var next = $(this).parent().next().attr('id');
		if( next == undefined ){
			next = $( '.tab_content:first', $(this).closest('.tab_box') ).attr('id');
		}
		change_tab( '#'+next );
	});
	
	function change_tab( id ){
		$tabs.attr('class', ''); // Remover class 'active' de todas as abas
		
		$button = $(id + '_anchor');
		$button.addClass('active'); // Atribuir 'active' ao link clicado
		
		//indicador
		$indicator = $(id).parent().find('.aba_indicador');
		var anchor_p = $button.position();
		$indicator.animate({
			left: (anchor_p.left + 33)
		});
		
		$('.tab_box .tab_content').hide();
		$(id).show(); // Mostrar conteúdo da aba requisitada
	}
	
	
	
	/**
	 * APLICAR BOROS SLIDER
	 * 
	 * 
	 */
	$('.slider_home_box').borosSlider({
		loop: true,
		slideshow: true,
		easing: 'boros',
		offset: 1
	});
	
	
	
});
