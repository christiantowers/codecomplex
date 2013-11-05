/**
* functions.website.js
*
* Arquivo de funções globais do website.
*		
* @since 		08/08/2013
* @location		js.functions.website
*
* ================================================================================================		
*/

/**
* ajax_save_newsletter()
* Salva um email na lista de newsletter.
* @return void
*/
function ajax_save_newsletter()
{
	// Habilita loading
	enable_loading();
	
	var url_ajax = $('#URL_ROOT').val() + '/support/ajax-save-newsletter/';
	
	// Coleta valores
	var email = $('#form_newsletter input#email').val();
	
	$.ajax({
		url: url_ajax,
		data: 'email=' + email,
		context: document.body,
		cache: false,
		async: false,
		type: 'POST',
		success: function(data)
		{
			$('#form_newsletter').hide();
			$('#form_newsletter_message').show();
		}
	});
	
	// Desabilita o loading
	disable_loading();
}

/**
* ajax_save_feedback()
* Envia os dados de feedback de uma caixa de feedback.
* @return void
*/
function ajax_save_feedback()
{
	// Habilita loading
	enable_loading();
	
	var url_ajax = $('#URL_ROOT').val() + '/support/ajax-save-feedback/';
	
	// Coleta valores
	var name = $('#feedback_box input#name').val();
	var email = $('#feedback_box input#email').val();
	var subject = $('#feedback_box input#subject').val() == undefined ? '' : $('#feedback_box input#subject').val();
	var message = $('#feedback_box textarea#message').val();
	
	$.ajax({
		url: url_ajax,
		data: 'name=' + name + '&message=' + message + '&email=' + email + '&subject=' + subject,
		context: document.body,
		cache: false,
		async: false,
		type: 'POST',
		success: function(data)
		{
			$('#feedback_box').hide();
			$('#feedback_box_message').show();
		}
	});
	
	// Desabilita o loading
	disable_loading();
}

/**
* enable_form_validations()
* Habilita validações de formulário na página inteira. É necessário que o script 
* jquery.validationengine.js e jquery.validationengine.pt_BR.js estejam inclusos e que um 
* formulario de id form_default ou form_filter estejam criados.
* @param string id [optional]
* @return void
*/
function enable_form_validations(id)
{
	if($("#form_filter").length > 0)
		$("#form_filter").validationEngine({ inlineValidation:false , promptPosition : "centerRight", scroll : true });
	if($("#form_default").length > 0)
		$("#form_default").validationEngine({ inlineValidation:false , promptPosition : "centerRight", scroll : true });
	if(id != 'undefined' || id != 'null')
		$("#" + id).validationEngine({ inlineValidation:false , promptPosition : "centerRight", scroll : true });
}

/**
* enable_masks()
* Habilita máscaras na página inteira. É necessário que o script jquery.meiomask.js esteja incluso.
* @return void
*/
function enable_masks()
{
	$('input:text').setMask();
}

/**
* redirect()
* Redireciona página para url encaminhada.
* @param string url
* @return void
*/
function redirect(url)
{
	window.location.href = url;
}

/**
* show_area()
* Collapsa uma area de id indicado. Espera-se que exista uma imagem de id igual ao id 
* encaminhado + _img para que se possa fazer a troca da imagem do bullet.
* @param string id_area
* @return void
*/
function show_area(id)
{
	if($('#' + id).is(":visible"))
	{
		if($('#' + id + '_img').length > 0)
		{
			var src = $('#' + id + '_img').attr('src').replace("minus", "plus");
			$('#' + id + '_img').attr("src", src);
		}
		$('#' + id).hide();
	} else {
		if($('#' + id + '_img').length > 0)
		{
			var src = $('#' + id + '_img').attr('src').replace("plus", "minus");
			$('#' + id + '_img').attr("src", src);
		}
		$('#' + id).show();
	}
}

/**
* show_area_slide()
* Collapsa uma area de id indicado utilizando efeito slideUp/Down. Espera-se que exista uma imagem 
* de id igual ao id encaminhado + _img para que se possa fazer a troca da imagem do bullet (utiliza
* bullet arrow).
* @param string id_area
* @return void
*/
function show_area_slide(id, pages)
{
	if($('#' + id).is(":visible"))
	{
		if($('#' + id + '_img').length > 0)
		{
			var src = $('#' + id + '_img').attr('src').replace("down", "right");
			$('#' + id + '_img').attr("src", src);
		}
		$('#' + id).slideUp();
	} else {
		if($('#' + id + '_img').length > 0)
		{
			var src = $('#' + id + '_img').attr('src').replace("right", "down");
			$('#' + id + '_img').attr("src", src);
		}
		$('#' + id).slideDown({complete:function(){pages.resizeFix(true);}});
	}
}

/**
* get_checked_value()
* Coleta o valor de um checkbox que está marcado, através do nome do elemento check.
* @param string name
* @return mixed value
*/
function get_checked_value(name)
{
	var retorno;
	$('input[name="' + name + '"]').each(function(){
		if($(this).is(':checked'))
		{ 
			retorno = $(this).val(); 
		}
	});
	return retorno;
}

/**
* open_modal()
* Cria uma modal on the fly colocando conteudo dentro dela. Espera-se que o script jquery.modal.js
* esteja incluso.
* @param string titulo
* @param string conteudo
* @param string imagem
* @param int width
* @param int height
* @param boolean close
* @return void
*/
function open_modal(titulo, conteudo, imagem, width, height, close)
{
	var html  = '';
	var style_modal = '';
	var style_content = '';
	
	// Seta altura e largura
	height = (height != '' && height != null) ? height : 650;
	width = (width  != '' && width  != null) ? width : 800;
	style_modal = ' style="width:' + width + 'px;height:' + height + 'px"';
	style_content = ' style="height:' + (height - 80) + 'px !important;"';
	
	// Seta o boolean para close
	var bool_close = (close == false) ? false : true;
	
	// Img to close
	var close_img  = (close == false) ? '' : '<img src="' + $("#URL_IMG").val() + '/icon_close.png" id="img_close" class="img_close" title="Fechar" />';
	
	// Inicializa html da janela modal
	html += '<div class="modal" id="modal"' + style_modal + '>';
	html += '<div id="header">' + close_img;
	html += (imagem != undefined && imagem != null && imagem != '') ? '<img src="' + imagem + '" />' : '';
	html += '<h6>' + titulo  + '</h6></div>';
	html += '<div id="content" ' + style_content + '>' + conteudo + '</div><div id="footer"></div>';
	html += '</div>';
	
	// Inicializa a própria janela modal
	$.modal(html, {opacity: 60, zIndex: 10000, closeClass: 'img_close', escClose: bool_close, position: [0,0], close: bool_close});
}

/**
* iframe_modal()
* Cria uma modal on the fly colocando o conteudo de uma URL encaminhada em uma pagina iframe. 
* Espera-se que o script jquery.modal.js esteja incluso.
* @param string titulo
* @param string conteudo
* @param string imagem
* @param int width
* @param int height
* @param boolean close
* @return void
*/
function iframe_modal(titulo, url_param, imagem, width, height, close)
{
	var html  = '';
	var style_modal = '';
	var style_content = '';
	
	// Seta altura e largura
	height = (height != '' && height != null) ? height : 650;
	width = (width  != '' && width  != null) ? width : 800;
	style_modal = ' style="width:' + width + 'px;height:' + height + 'px"';
	style_content = ' style="height:' + (height - 80) + 'px !important;"';
	
	// Seta o boolean para close
	var bool_close = (close == false) ? false : true;
	
	// Img to close
	var close_img  = (close == false) ? '' : '<img src="' + $("#URL_IMG").val() + '/icon_close.png" id="img_close" class="img_close" title="Fechar" />';
	
	// Inicializa html da janela modal
	html += '<div class="modal" id="modal"' + style_modal + '>';
	html += '<div id="header">' + close_img;
	html += (imagem != undefined && imagem != null && imagem != '') ? '<img src="' + imagem + '" />' : '';
	html += '<h6>' + titulo  + '</h6></div>';
	html += '<div id="content" ' + style_content + '><iframe src="' + url_param + '" style="width:100%;height:' + (height - 80) + 'px !important;max-height:670px;border:0px solid green;z-index:10006;position:relative" align="left" frameborder="no"></iframe></div><div id="footer"></div>';
	html += '</div>';
	
	// Inicializa a própria janela modal
	$.modal(html, {opacity: 60, zIndex: 10000, closeClass: 'img_close', escClose: bool_close, position: [0,0], close: bool_close});
}

/**
* ajax_modal()
* Cria uma modal on the fly carregando em ajax o conteudo de uma URL para seu interior. 
* Espera-se que o script jquery.modal.js esteja incluso.
* @param string titulo
* @param string url
* @param string imagem
* @param int width
* @param int height
* @param boolean close
* @return void
*/
function ajax_modal(titulo, url_param, imagem, width, height, close)
{
	var html  = '';
	var style_modal = '';
	var style_content = '';
	
	// Habilita loading
	enable_loading();

	// Dispara o ajax da url
	$.ajax({
		url: url_param,
		context: document.body,
		cache: false,
		async: false,
		// data: 'email=' + prUser,
		type: 'POST',
		success: function(data){
			// alert(txt);
			// Seta altura e largura
			height = (height != '' && height != null) ? height : 650;
			width = (width  != '' && width  != null) ? width : 800;
			style_modal = ' style="width:' + width + 'px;height:' + height + 'px"';
			style_content = ' style="height:' + (height - 80) + 'px !important;"';
			
			// Seta o boolean para close
			var bool_close = (close == false) ? false : true;
			
			// Img to close
			var close_img  = (close == false) ? '' : '<img src="' + $("#URL_IMG").val() + '/icon_close.png" id="img_close" class="img_close" title="Fechar" />';
			
			// Inicializa html da janela modal
			html += '<div class="modal" id="modal"' + style_modal + '>';
			html += '<div id="header">' + close_img;
			html += (imagem != undefined && imagem != null && imagem != '') ? '<img src="' + imagem + '" />' : '';
			html += '<h6>' + titulo  + '</h6></div>';
			html += '<div id="content" ' + style_content + '>' + data + '</div><div id="footer"></div>';
			html += '</div>';
			
			// Inicializa a própria janela modal
			$.modal(html, {opacity: 60, zIndex: 10000, closeClass: 'img_close', position: [0,0], escClose: bool_close, close: bool_close});
			
			// Desabilita o loading
			disable_loading();
		}
	});
}

/**
* close_modal()
* Destrói a janela modal corrente da tela.
* @return void
*/
function close_modal()
{
	// Linha utilizada para fechamento de dialog quando chamada por iframe
	$.modal.close();
	
	// Linha utilizada para fechamento de dialog quando chamada por ajax ou conteudo
	$('#simplemodal-overlay').remove();
	$('#simplemodal-container').remove();
}

/**
* enable_loading()
* Habilita layer de loading.
* @return void
*/
function enable_loading()
{
	// Append do conteudo no body
	if($("#loading_layer").length <= 0)
	{
		// alert('asas');
		$('body').append('<div id="loading_layer"></div><div id="loading_box"><h4 class="inline top font_shadow_black" style="color:#ddd;vertical-align:middle !important">Carregando...</h4></div>');
		$("#loading_layer").show(); 
		$("#loading_box").show(); 
	} else {
		$("#loading_layer").show(); 
		$("#loading_box").show(); 
	}
}

/**
* disable_loading()
* Desabilita loading.
* @return void
*/
function disable_loading()
{
	// Verifica se o elemento existe, para fazer display:none;
	if($("#loading_layer").length > 0)
	{
		$("#loading_layer").hide();
		$("#loading_box").hide();
	}
}

/**
* round_number()
* Arredonda um numero em duas casas decimais.
* @param double rnum
* @return double rnum
*/
function round_number(rnum)
{
	return Math.round(rnum*Math.pow(10,2))/Math.pow(10,2);
}

/**
* to_moeda()
* Passa um numero para formato moeda.
* @param double num
* @return string rnum
*/
function to_moeda(num) 
{
    x = 0;
    if(num<0) {
		num = Math.abs(num);
		x = 1;
	}
	if(isNaN(num)) num = "0";
		cents = Math.floor((num*100+0.5)%100);

	num = Math.floor((num*100+0.5)/100).toString();

	if(cents < 10) cents = "0" + cents;
		for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
			num = num.substring(0,num.length-(4*i+3))+'.'+num.substring(num.length-(4*i+3));
	
	ret = num + ',' + cents;
	if (x == 1) ret = ' - ' + ret; return ret;
}

/**
* to_float()
* Passa um numero para formato float, recebido como moeda.
* @param string moeda
* @return double rnum
*/
function to_float(moeda)
{
	moeda = moeda.replace(".","");
	moeda = moeda.replace(",",".");
	return parseFloat(moeda);
}

/**
* get_enter()
* Dispara uma funcao quando a tecla pressionada é 13 (enter).
* @param string event
* @param string function_eval
* @return void
*/
function get_enter(event, function_eval)
{
	if(event.which == 13) {	eval(function_eval); /*event.preventDefault();*/ }
}

/**
* download_excel()
* Cria um iframe em tempo real onde o target será uma página que exporta um arquivo excel.
* @param string url_export
* @return void
*/
function download_excel(url_export)
{
	$("body").append('<iframe src="' + url_export + '" style="display:none"></iframe>');
}

/**
* download_file()
* Cria um iframe em tempo real onde o target será uma página que exporta um file.
* @param string url_export
* @return void
*/
function download_file(url_export)
{
	$("body").append('<iframe src="' + url_export + '" style="display:none"></iframe>');
}
/**
* submit_newsletter()
* Envia o formulário de newsletter com o email.
* @return void
*/
function submit_newsletter()
{
	$("form_newsletter").submit();
}