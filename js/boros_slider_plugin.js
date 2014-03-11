/**
 * Recursos
 * OK:
 * atributos css manipulados pelo script: ul.slider.width(inicial) e ul.slider.left(animação de slide) apenas, restante é direto pelo css
 * slideshow
 * fixed or fluid slides
 * prev-next button
 * numeric buttons
 * looping
 * 
 * 
 * TODO:
 * selecionar slide inicial
 * altura flexível[ver coda-slider]
 * slideshow controls
 * lightbox[?]
 * melhorar configuração de looping
 * permitir slider sem navegação(apenas slideshow)
 * animated caption
 * thumbnails
 * acessibilidade
 * permalinks
 * keyboard navigation
 * 
 * 
 */

(function($) {
	
	$.fn.borosSlider = function( config ){
		var defaults = {
			loop: true,
			numeric_nav: true,
			slideshow: true,
			easing: 'swing',
			offset: 1
		}
		config = $.extend( defaults, config );
		
		// array de intervals.
		var timers = new Array();
		
		//console.log( config );
		
		// buscar todos os sliders, caso exista mais de um
		return this.each(function(){
			var $slider = $(this);
			
			//adicionar class de slider aplicado
			$slider.addClass('boros_slider_active');
			
			// largura do holder, com slides de largura flexivel
			var strip 	= $slider.find('.boros_slider_strip');
			var holderw = 0;
			var numeric_nav = '';
			$slider.find('.slide').each(function(index){
				index = (index + 1);
				holderw += $(this).outerWidth(true);
				
				// criar botões de navegação numérica
				if( config.numeric_nav ){
					numeric_nav += ' <span class="btn_nav btn_number" rel="'+index+'">'+index+'</span> ';
				}
			});
			strip.width( holderw );
			
			config.strip_width 	= holderw;
			config.viewport 	= $slider.find('.boros_slider_holder').width();
			config.max_move 	= (config.strip_width - config.viewport);
			
			// indicar primeiro slide ativo
			$slider.find('.slide:first').addClass('active');
			
			// classes da navegação
			if( $slider.find('.boros_slider_nav').is('.numeric') ){
				$slider.find('.btn_nav:first').after(numeric_nav);
				$slider.find('.btn_number:first').addClass('active');
			}
			
			// atribuir ação de click
			$slider.find('.boros_slider_nav .btn_nav').click(function(){
				// parar apenas o timer específico
				stop_slideshow( $slider.index() );
				slide_go( $(this) );
			});
			
			if( config.slideshow ){
				start_slideshow( $slider );
			}
		});
		
		
		
		function slide_go( obj ){
			var slider = $(obj).closest('.boros_slider');
			
			// impedir encadeamento de animações
			if( slider.is('.sliding') ){
				//console.log('hold action!');
				return false;
			}
			
			var strip 			= slider.find('.boros_slider_strip');	// faixa com os slides
			var slides 			= strip.find('.slide')					// grupo total de slides
			var slides_total	= (slides.length - 1)					// total de slides, 0 index
			var active_index 	= slides.filter('.active').index();		// indice do slide ativo
			var new_active		= active_index;							// novo index, por enquanto = ao indice ativo
			
			// definir direção e valor (prev|next)
			var direction = $(obj).attr('rel');
			
			// decalara variável do alvo da ativação
			var $activated;
			var width = 0;
			
			//console.log( 'active_index a: '+active_index);
			//console.log( 'config.offset: '+config.offset);
			//console.log( 'slides_total: ' + slides_total );
			//console.log( 'active_index: ' + active_index );
			//console.log( 'slides.size(): ' + slides.size() );
			//console.log( 'config.loop: ' + config.loop);
			
			// avançar
			if( direction == 'next' ){
				// próximo
				if( active_index < slides_total ){
					// próximo slide
					new_active = active_index + config.offset;
				}
				// retornar ao início
				else{
					// reativar o primeiro slide
					new_active = 0;
					
					//console.log( 'active_index: ' + active_index );
					//console.log( 'slides_total: ' + slides_total );
					
					if( (active_index == slides_total) && config.loop == false ){
						//console.log('end, no loop');
						return false;
					}
				}
			}
			// retroceder
			else if( direction == 'prev' ){
				// anterior
				if( active_index >= config.offset ){
					
					// se estiver no último
					if( active_index == slides_total ){
						//console.log('a');
						
						// calcular o ultimo antes do viewport, slided
						var last_index_before = 0;
						var last_width_before = 0;
						slides.each(function(){
							//console.log( 'last_index_before: ' + last_index_before );
							//console.log( 'last_width_before: ' + last_width_before );
							if( last_width_before < config.max_move ){
								last_width_before += $(this).outerWidth(true);
							}
							else{
								if( last_index_before == 0 ){
									last_index_before = $(this).index();
								}
								//console.log( 'FINAL: ' + $(this).index() );
							}
						});
						
						//console.log('last_index_before: '+last_index_before);
						new_active = (last_index_before - 1);
					}
					// se ainda não estiver no ultimo, voltar um
					else{
						//console.log('b');
						// slide anterior
						new_active = (active_index - config.offset);
					}
				}
				else{
					//console.log('z');
					if( active_index == 0 ){
						//console.log('za');
						new_active = slides.size();
						
						if( config.loop == false ){
							//console.log('end, no loop');
							return false;
						}
					}
					else{
						//console.log('zb');
						new_active = 0;
					}
				}
										
			}
			// link direto -> botão numérico
			else{
				new_active = (direction - 1);
			}
			
			
			
			
			// calcular todas as medidas importantes
			//console.log('viewport: ' + config.viewport);
			//console.log(' - - - - - -');
			//console.log('status: ' + active_index + ' - ' + slides_total);
			//console.log('config.max_move: ' + config.max_move);
			var tail = 0;
			slides.slice( new_active ).each(function(){
				tail += $(this).outerWidth(true);
			});
			//console.log('tail: ' +  tail);
			
			
			
			//console.log('new_active: '+new_active);
			// medida do próximo slide
			slides.slice(0, new_active).each(function(){
				width += $(this).outerWidth(true);
			});
			// novo slide ativo
			$activated = slides.filter('.slide:eq('+new_active+')');
			//console.log(width);
			
			
			
			
			// corrigir posição caso pegue os ultimos slides
			//console.log( config.strip_width );
			//console.log( position.left );
			var move = width;
			//console.log('move: ' + move);
			if( move > config.max_move ){
				width = config.max_move;
				new_active = slides_total;
				$activated = slides.filter('.slide:last');
				//console.log('passou!');
			}
			
			// mover slide
			//console.log('start action!');
			
			// remover classes de ativação apenas se a troca de slide for ativada
			slides.removeClass('active');
			// flag para marcar movimentação
			slider.addClass('sliding');
			//console.log('new_left: -' + width);
			strip.animate({'left': '-'+width }, 1000, config.easing, function(){
				//console.log('end action!');
				// classes da navegação
				$activated.addClass('active');
				obj.parent().find('.btn_nav').removeClass('active');
				obj.parent().find('[rel='+(new_active+1)+']').addClass('active');
				slider.removeClass('sliding');
			});
			
		}
		
		function start_slideshow( obj ){
			timers[obj.index()] = window.setInterval( function(){
				auto_slide(obj);
			}, 7000 );
		}
		
		function auto_slide( obj ){
			var btn = obj.find('[rel=next]');
			slide_go( btn );
		}
		
		function stop_slideshow( index ){
			clearInterval( timers[index] );
		}
	}
	
	
	$.easing.boros = function(x, t, b, c, d) {
		var s = 1.70158; 
		if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(.5))+1)*t - s)) + b;
		return c/2*((t-=2)*t*(((s*=(.5))+1)*t + s) + 2) + b;
	}
})(jQuery);