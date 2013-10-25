<?php echo load_js_file('tinymce/tinymce.min.js') ?>
<?php echo $this->template->load_js_file('codemirror.3.12/lib/codemirror.js') ?>
<?php echo $this->template->load_js_file('codemirror.3.12/mode/xml/xml.js') ?>
<link type="text/css" rel="stylesheet" href="<?php echo URL_JS ?>/codemirror.3.12/lib/codemirror.css" />
<link type="text/css" rel="stylesheet" href="<?php echo URL_JS ?>/codemirror.3.12/theme/lesser-dark.css" />
<link type="text/css" rel="stylesheet" href="<?php echo URL_JS ?>/codemirror.3.12/theme/ambiance.css" />
<style type="text/css">
  .CodeMirror { border: 2px solid #000; height: auto;min-height:300px; line-height:18px; font-family:consolas, 'courier new';font-size:14px; }
  .CodeMirror-scroll { overflow-y: hidden; overflow-x: auto; }
</style>
<script type="text/javascript" language="javascript">
	var external_editor = null;
	$(document).ready(function(){
		// Validação e máscaras
		enable_form_validations();
		enable_masks();
		
		// Habilita o editor HTML Código
		var editor = CodeMirror.fromTextArea(document.getElementById("html_custom"), {
            mode: 'xml',
            lineNumbers: true,
            autoCloseTags: true,
			theme: 'ambiance',
			extraKeys: {
				"Ctrl-S": function(instance) { return; /*alert(instance.getValue());*/ }
			}
        });
		
		// Passa o editor para que possa ser utilizado no plano externo
		external_editor = editor;
		
		// Habilita o editor HTML Visual
		tinymce.init({
			selector: "#html_visual",
			valid_elements: "*[*]",
			extended_valid_elements: 'php',
			custom_elements : 'php',
			forced_root_block: false,
			force_p_newlines: false,
			force_br_newlines: true,
			remove_linebreaks: false,
			convert_newlines_to_brs: false,
			font_formats : "Andale Mono=andale mono,times;"+
                "Arial=arial,helvetica,sans-serif;"+
                "Arial Black=arial black,avant garde;"+
                "Book Antiqua=book antiqua,palatino;"+
                "Comic Sans MS=comic sans ms,sans-serif;"+
                "Courier New=courier new,courier;"+
                "Georgia=georgia,palatino;"+
                "Helvetica=helvetica;"+
                "Impact=impact,chicago;"+
                "Open Sans Condensed=open sans condensed;"+
                "Symbol=symbol;"+
                "Tahoma=tahoma,arial,helvetica,sans-serif;"+
                "Terminal=terminal,monaco;"+
                "Times New Roman=times new roman,times;"+
                "Trebuchet MS=trebuchet ms,geneva;"+
                "Verdana=verdana,geneva;"+
                "Webdings=webdings;"+
                "Wingdings=wingdings,zapf dingbats",
			plugins: [
						"advlist autolink lists link image charmap print preview anchor",
						"searchreplace visualblocks",
						"insertdatetime media table contextmenu paste code"
					],
			toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | styleselect | fontselect | fontsizeselect ",
			toolbar2: "hr | outdent indent | numlist | bullist | link image | print | preview | code",
			autosave_ask_before_unload: false,
			content_css: '<?php echo URL_CSS ?>/style.css',
			max_height: "auto",
			min_height: 500,
			height : 180
		});
		
		// Ataccha mensagens de ajuda nos links de saiba mais
		$('#saiba_mais_conteudo_pagina_link').mouseover(function(){
			$('#saiba_mais_conteudo_pagina_box').attr('style', 'display:inline-block !important;margin:0px 0 0 10px;position:absolute;');
		});
		$('#saiba_mais_conteudo_pagina_link').mouseout(function(){
			$('#saiba_mais_conteudo_pagina_box').attr('style', 'display:none !important;margin:0px 0 0 10px;position:absolute;');
		});
		$('#saiba_mais_exibicao_pagina_link').mouseover(function(){
			$('#saiba_mais_exibicao_pagina_box').attr('style', 'display:inline-block !important;margin:0px 0 0 10px;position:absolute;');
		});
		$('#saiba_mais_exibicao_pagina_link').mouseout(function(){
			$('#saiba_mais_exibicao_pagina_box').attr('style', 'display:none !important;margin:0px 0 0 10px;position:absolute;');
		});
	});
	
	// Função monta a URL da página, conforme o título
	function build_url()
	{
		// Coleta url do pai
		var url_parent = $('#id_page_url_parent > option:selected').html();
		url_parent = (url_parent == 'null' || url_parent == 'undefined' || url_parent == null || url_parent == undefined) ? '' : url_parent;
		var title = $('#title').val().toLowerCase();
		title = title.replace(/\s/g, '-');
		title = title.replace(/[^A-Za-z0-9 \s-._#~?]*/g, '');
		var url = (url_parent != '' || title != '') ? url_parent + '/' + title : '';
		url = url.replace(/\/\//g, '/');
		
		// Seta o label, o text e o hidden com a URL
		$('#url').val(url);
		$('#url_text').val(url);
		$('#url_label').html(url);
		
		// Caso a url esteja preenchida, mostra botão de alterar
		if(url.length > 0)
		{
			$('#link_alterar_url').show();
		} else {
			$('#link_alterar_url').hide();
			$('#url_label').html('<?php echo lang('Insira um título, logo mais abaixo.')?>');
		}
	}
	
	// Função chaveia as caixas de seleção de tipo de conteúdo
	function show_box_method_show()
	{
		$('#button_preview').hide();
		$('#box_load_file').hide();
		$('#box_html_visual').hide();
		$('#box_html_custom').hide();
		$('#box_controller_exec').hide();
		switch($('#method_show').val())
		{			
			case 'load_file':
				$('#box_load_file').slideDown();
			break;
			
			case 'html_visual':
				$('#box_html_visual').slideDown();
				$('#button_preview').show();
			break;
			
			case 'html_custom':
				$('#box_html_custom').slideDown();
				$('#button_preview').show();
			break;
			
			case 'controller_exec':
				$('#box_controller_exec').slideDown();
			break;
			
			default:
			break;
		}
	}
</script>
<div>
	<!-- CABEÇALHO DO MÓDULO e MENUS -->
	<div id="module_header">
		<div id="module_rotule" class="inline top">
			<h2 class="inline top font_shadow_gray"><a class="black" href="<?php echo URL_ROOT . '/' . $this->controller ?>"><?php echo lang($this->lang_key_rotule); ?></a></h2>
			<?php if($this->url_img != '') {?>
			<img src="<?php echo $this->url_img ?>" />
			<?php } ?>
		</div>
		<div id="module_menus" class="inline top">
			<div class="inline top module_menu_item" title="<?php echo lang('Nova Página URL')?>" style="margin:-7px 0 0 -15px">
				<h4 class="inline top font_shadow_gray"> > <?php echo lang('Nova Página URL') ?></h4>
				<div class="inline top comment" style="margin:12px 0 0 8px">(<a href="<?php echo URL_ROOT . '/' . $this->controller ?>"><?php echo lang('Voltar para o módulo')?></a>)</div>
			</div>
		</div>		
	</div>
	
	<!-- DESCRICAO DO FORM -->
	<div style="position:absolute;right:30px;width:300px;margin:<?php echo ($error_uploading != '') ? '162px' : '42px'?> 0 0 50px;padding:0 0 0 30px;background-color:#fff;">
	<?php echo start_box(lang('Ajuda') . '<img src="' . URL_IMG . '/icon_bullet_minus.png" id="box_ajuda_content_img" style="cursor:pointer;float:right;margin:4px -5px 0 0" onclick="show_area(\'box_ajuda_content\');"/>', URL_IMG. '/icon_help.png');?>
		<div id="box_ajuda_content" class="font_11" style="line-height:15px">
			<div class="font_error bold"><?php echo lang('O que é uma Página URL ?')?></div>
			<p style="margin-bottom:10px;"><?php echo lang('Uma URL do seu site representa um conteúdo cadastrado.') . ' <strong>' . lang('TODA E QUALQUER URL') . '</strong> ' . lang('do seu site deve ser mapeada como um conteúdo.') . ' <strong>' . lang('Tenha em mente isso.') . '</strong>'?></p>
			<div class="font_error bold"><?php echo lang('Como o conteúdo da URL será exibido ?')?></div>
			<p style="margin-bottom:10px"><?php echo lang('A forma como o conteúdo de uma URL é exibido é bastante flexível. Você pode cadastrar um conteúdo visualmente, fazer upload de um arquivo contendo código html/php ou definir um código html/php diretamente no editor de códigos disponível.')?></p>
			<div class="font_error bold"><?php echo lang('Uma URL pode ser processada manualmente ?')?></div>
			<p style="margin-bottom:10px"><?php echo lang('Você pode cadastrar um conteúdo e fazer com que sua URL de acesso seja redirecionada para um controlador/ação de sua preferência. Lá você tratará o processamento da forma que bem entender. É aí que mora a ') . ' <u>' . lang('flexibilidade') . '</u>.'?></p>	
		</div>
	<?php echo end_box(); ?>
	</div>
	
	<?php if($error_uploading != '') {?>
	<div style="margin:20px 0"><?php echo message('error', lang('ATENÇÃO!'), lang('Não foi possível cadastrar esta página URL. Verifique os problemas abaixo, corrija-os e tente novamente:') . $error_uploading); ?></div>
	<?php } ?>
	
	<!-- FORMULARIO -->
	<div style="margin-top:50px">
		<form id="form_default" name="form_default" action="<?php echo URL_ROOT ?>/cms_page_url/form_insert_custom_process" method="post" enctype="multipart/form-data">
			<h5><?php echo lang('Definição da Página URL')?></h5>
			<hr style="margin-bottom:10px;" />
			<div id="form_line">
				<div class="form_label font_11 bold" style="margin-top:5px;width:150px"><?php echo lang('URL')?>*</div>
				<div class="form_field" style="max-width:710px">
					<h5 class="inline top" style="height:20px;color:steelblue"><span id="url_label"><?php echo (get_value($form_data, 'url') != '') ? get_value($form_data, 'url') : lang('Insira um título, logo mais abaixo.'); ?></span></h5>
					<input type="hidden" name="url" id="url" class="validate[required]" maxlength="250" style="width:300px" value="<?php echo get_value($form_data, 'url'); ?>" />
					<input type="text" name="url_text" id="url_text" class="validate[required]" maxlength="250" style="margin:5px 0 0 -5px;display:none;width:300px" value="<?php echo get_value($form_data, 'url'); ?>" onkeyup="$('#url').val($(this).val());$('#url_label').html($(this).val());" />
					<div class="inline top font_11" style="margin-top:10px;"><a href="javascript:void(0);" id="link_alterar_url" style="display:none;" onclick="$('#url_label').hide();$('#url_text').show();$('#link_alterar_url_ok').show();$(this).hide();"><?php echo lang('Alterar URL')?></a></div>
					<div class="inline top font_11" style="margin-top:10px;"><button class="mini" type="button" id="link_alterar_url_ok" style="display:none;" onclick="$('#url_label').show();$('#url_text').hide();$('#link_alterar_url').show();$(this).hide();"><?php echo lang('OK')?></button></div>
					<div style="margin-top:10px;line-height:18px" class="font_11 comment"><?php echo lang('<strong>URL</strong> é chave de acesso a este conteúdo. Quando for digitada na barra de endereços do seu navegador, este conteúdo será carregado.')?> <a href="javascript:void(0);" Onclick="show_area('saiba_mais_url')"><?php echo lang('Saiba mais')?></a></div>
					<div id="saiba_mais_url" style="display:none;">
						<div style="margin-top:5px;line-height:18px" class="font_11 comment"><?php echo lang('<strong>Exemplo:</strong> quando a URL http://www.seusite.com<strong>/dowloads/latest-release</strong> for digitada na barra de endereços, a página com a chave URL <strong>/downloads/latest-release</strong> será carregada. O conteúdo será exibido dependendo do método de exibição definido para a página.')?></div>
						<div style="margin-top:5px;line-height:18px" class="font_11 comment">
							<?php echo lang('<strong>Exemplos de URLs:</strong>') ?><br />
							<div>/downloads/latest-release</div>
							<div>/support/contact/form-contact-us</div>
							<div>/documentation/how-this-site-works/be-careful-with-your-choices</div>
						</div>
					</div>
				</div>
			</div>
			<div id="form_line">
				<div class="form_label font_11 bold" style="width:150px"><?php echo lang('Página URL "Pai"')?></div>
				<div class="form_field" style="max-width:700px;">
					<select name="id_page_url_parent" id="id_page_url_parent" style="width:312px" onchange="build_url()"><?php echo $options_urls; ?></select>
					<div style="margin-top:5px;line-height:18px" class="font_11 comment"><?php echo lang('Caso esta página seja um conteúdo que está abaixo de outro conteúdo, então você deve informar o conteúdo "pai", ou superior.')?> <a href="javascript:void(0);" onclick="show_area('saiba_mais_url_parent')"><?php echo lang('Saiba mais')?></a></div>
					<div id="saiba_mais_url_parent" style="display:none">
						<div style="margin-top:5px;line-height:18px" class="font_11 comment"><?php echo lang('<strong>Por Exemplo:</strong> Suponha que a URL <strong>/downloads</strong> possua uma lista de opções de downloads. Uma destas opções leva para a URL <strong>/downloads/latest-release</strong>. Esta ultima URL tem como superior a URL <strong>/downloads</strong>, que deve ser informada aqui.')?></div>
						<div style="margin-top:5px;line-height:18px" class="font_11 comment"><?php echo lang('Desta forma é fácil de identificar e organizar o conteúdo das URLs do website, havendo a possibilidade de geração de um sitemap organizado.')?></div>
					</div>
				</div>
			</div>
			<div id="form_line">
				<div class="form_label font_11 bold" style="width:150px"><?php echo lang('Título')?>*</div>
				<div class="form_field" style="max-width:700px;">
					<input type="text" name="title" id="title" class="validate[required]" maxlength="250" style="width:300px" onkeyup="build_url();" value="<?php echo get_value($form_data, 'title'); ?>" />
					<div style="margin-top:5px;" class="font_11 comment"><?php echo lang('O título da página será exibido ao lado do nome do site, na aba do navegador. O título também se refere ao título deste conteúdo, para fácil identificação posterior.')?></div>
				</div>
			</div>
			<div id="form_line">
				<div class="form_label font_11 bold" style="width:150px"><?php echo lang('Categoria da Página')?>*</div>
				<div class="form_field"><select name="id_page_url_category" id="id_page_url_category" style="width:312px"><?php echo $options_categories; ?></select></div>
			</div>
			
			<br />
			<br />
			<br />
			<br />
			<h5 class="inline top"><?php echo lang('Conteúdo desta Página')?></h5>
			<div id="saiba_mais_conteudo_pagina_link" class="inline top" style="margin:9px 0 0 5px !important">
				<div class="inline top"><a href="javascript:void(0)" onclick="show_area('saiba_mais_conteudo_pagina')"/><?php echo lang('Saiba mais')?></a></div>
				<img src="<?php echo URL_IMG ?>/icon_help.png" style="margin:1px 0 0 2px" />
			</div>
			<div id="saiba_mais_conteudo_pagina_box" class="fb_title top font_11" style="display:none !important;margin:0px 0 0 10px;position:absolute;">
				<img src="<?php echo URL_IMG ?>/bg_arrow_form.png" style="float:left;margin:0 0 0 -18px" />
				<span><?php echo lang('Defina nesta seção o conteúdo desta página URL. Você pode selecionar a forma de exibição e tipo de conteúdo através do combo de seleção, abaixo.'); ?></span>
			</div>
			<hr style="margin-bottom:10px;" />
			<div id="form_line">
				<div class="form_label font_11 bold" style="width:150px;line-height:18px;"><?php echo lang('Tipo de Conteúdo')?>*</div>
				<div class="form_field">
					<select name="method_show" id="method_show" style="margin-top:5px;width:312px" class="validate[required]" onchange="show_box_method_show();">
						<!--option value="" <?php echo (get_value($form_data, 'method_show') == '') ? 'selected="selected"' : ''; ?>><?php echo lang('Selecione uma opção...') ?></option-->
						<option value="html_custom" <?php echo (get_value($form_data, 'method_show') == 'html_custom' || get_value($form_data, 'method_show') == '') ? 'selected="selected"' : ''; ?>><?php echo lang('Editor de Código HTML/PHP') ?></option>
						<option value="html_visual" <?php echo (get_value($form_data, 'method_show') == 'html_visual') ? 'selected="selected"' : ''; ?>><?php echo lang('Editor Visual (HTML)') ?></option>
						<option value="load_file" <?php echo (get_value($form_data, 'method_show') == 'load_file') ? 'selected="selected"' : ''; ?>><?php echo lang('Carregar Arquivo .php ou.html') ?></option>
						<option value="controller_exec" <?php echo (get_value($form_data, 'method_show') == 'controller_exec') ? 'selected="selected"' : ''; ?>><?php echo lang('Redirecionar para Contoller/Ação') ?></option>
					</select>
				</div>
				<div style="width:100%">
					<div id="box_load_file" style="background-color:#f5f5f5;padding:10px 20px 30px 20px;border-bottom:2px solid #ccc;border-top:2px solid #ccc;margin-top:15px;padding-top:5px;<?php echo (get_value($form_data, 'method_show') == 'load_file') ? 'display:block' : 'display:none'; ?>">
						<img src="<?php echo URL_IMG ?>/icon_arrow_balloon_up.png" style="float:left;margin:-16px 0 0 30px;position:absolute" />
						<h6 style="margin:10px 0 10px 0;"><?php echo lang('Faça o upload de um arquivo .php ou .html que será carregado quando a URL desta página for solicitada. O arquivo será inserido dentro da página master ou não, conforme a opção escolhida mais abaixo.')?></h6>
						<h6 style="margin:0 0 20px 0;" class="font_error"><?php echo lang('ATENÇÃO! Envie somente um arquivo na extensão .php ou .html. O limite de tamanho máximo de arquivo é de 4MB') ?></h6>
						<div class="inline top"><?php echo input_file('file_path_content', 'file_path_content', 'validate[required]')?></div>
					</div>
					<div id="box_html_visual" style="background-color:#f5f5f5;padding:10px 20px 30px 20px;border-bottom:2px solid #ccc;border-top:2px solid #ccc;margin-top:15px;padding-top:5px;<?php echo (get_value($form_data, 'method_show') == 'html_visual') ? 'display:block' : 'display:none'; ?>">
						<button id="button_preview" class="green rounded mini" type="button" onclick="ajax_build_preview_page_url(external_editor);return false;" style="float:right;position:absolute;right:50px;margin:10px 0 0 0 !important" title="<?php echo lang('Veja como esta página será exibida conforme as opções atualmente definidas.')?>"><?php echo lang('Preview desta Página URL')?><img style="float:right;margin:0px 0 0 5px" src="<?php echo URL_IMG?>/icon_bullet_external_link.png" /></button>
						<img src="<?php echo URL_IMG ?>/icon_arrow_balloon_up.png" style="float:left;margin:-16px 0 0 30px;position:absolute" />
						<h6 style="margin:10px 200px 20px 0;"><?php echo lang('Utilize o editor visual abaixo para definir o conteúdo da página. Este editor não suporta comandos em PHP. Para isso, utilize o') . ' <a href="javascript:void(0)" onclick="$(\'#method_show\').val(\'html_custom\');show_box_method_show()">' . lang('Editor de Códigos') . '</a>.'?></h6>
						<textarea name="html_visual" id="html_visual" class="validate[required] script"><?php echo get_value($form_data, 'html_visual')?></textarea>
					</div>
					<div id="box_html_custom" style="background-color:#f5f5f5;padding:10px 20px 30px 20px;border-bottom:2px solid #ccc;border-top:2px solid #ccc;margin-top:15px;padding-top:5px;<?php echo (get_value($form_data, 'method_show') == 'html_custom' || get_value($form_data, 'method_show') == '') ? 'display:block' : 'display:none'; ?>">
						<button id="button_preview" class="green rounded mini" type="button" onclick="ajax_build_preview_page_url(external_editor);return false;" style="float:right;position:absolute;right:50px;margin:10px 0 0 0 !important" title="<?php echo lang('Veja como esta página será exibida conforme as opções atualmente definidas.')?>"><?php echo lang('Preview desta Página URL')?><img style="float:right;margin:0px 0 0 5px" src="<?php echo URL_IMG?>/icon_bullet_external_link.png" /></button>
						<img src="<?php echo URL_IMG ?>/icon_arrow_balloon_up.png" style="float:left;margin:-16px 0 0 30px;position:absolute" />
						<h6 style="margin:10px 200px 20px 0;"><?php echo lang('Utilize o editor de código HTML abaixo para definir o conteúdo da página. Este editor também suporta comandos em PHP. Para definir o conteúdo da página visualmente, utilize o') . ' <a href="javascript:void(0)" onclick="$(\'#method_show\').val(\'html_visual\');show_box_method_show()">' . lang('Editor de HTML Visual') . '</a>.'?></h6>
						<textarea name="html_custom" id="html_custom" class="validate[required] script"><?php echo htmlspecialchars(get_value($form_data, 'html_custom'))?></textarea>
					</div>
					<div id="box_controller_exec" style="background-color:#f5f5f5;padding:10px 20px 30px 20px;border-bottom:2px solid #ccc;border-top:2px solid #ccc;margin-top:15px;padding-top:5px;<?php echo (get_value($form_data, 'method_show') == 'controller_exec') ? 'display:block' : 'display:none'; ?>">
						<img src="<?php echo URL_IMG ?>/icon_arrow_balloon_up.png" style="float:left;margin:-16px 0 0 30px;position:absolute" />
						<h6 style="margin:10px 0 10px 0;"><?php echo lang('Quando a URL desta página for solicitada, o carregamento da página será direcionado para o <span class="font_error">controlador/ação</span> definido abaixo.') ?></h6>
						<h6 style="margin:10px 0 20px 0;"><?php echo lang('Se for necessário definir uma URL que tenha um pedaço dinâmico, não há problema. Neste caso você deve fazer uso de <span class="font_error">wild-cards</span> na URL e apontar para onde este valor irá, no controlador/ação abaixo.') . ' <a href="javascript:void(0)" onclick="show_area(\'box_saiba_mais_redirect_controller_action\')">' . lang('Saiba mais') . '</a>.';?></h6>
						<div id="box_saiba_mais_redirect_controller_action" class="font_11" style="display:none;border:1px dashed #ccc;background-color:#fff;line-height:15px;padding:10px 20px;margin:-10px 0 20px 0">
							<p class="bold"><?php echo lang('Suponha o seguinte exemplo:')?></p>
							<p><?php echo lang('Durante a criação de um website, foi identificado a necessidade de que a URL <strong>/downloads/get-your-product/<span class="font_green">HASH</span></strong> seja tratada pelo controlador/ação <strong>downloads/get_your_product</strong>, que recebe como parâmetro um <span class="font_green">HASH</span> que tem seu valor mutável.')?></p>
							<br />
							<p class="bold"><?php echo lang('Neste caso, a definição da página URL deve ser:')?></p>
							<p><?php echo lang('<strong>Tipo de Conteúdo</strong> => Redirecionar para controlador/ação')?></p>
							<p><?php echo lang('<strong>URL</strong> => /downloads/get-your-product/(:any)')?></p>
							<p><?php echo lang('<strong>controlador/ação</strong> => /downloads/get_your_product/$1')?></p>
							<br />
							<p><?php echo lang('Na definição acima, tudo que for mapeado por <strong>(:any)</strong> será colocado no lugar de <strong>$1</strong>, recriando uma rota de URL.')?></p>
						</div>
						<input type="text" name="controller_action" id="controller_action" class="validate[required]" style="width:300px;" value="<?php echo get_value($form_data, 'controller_action')?>" />
						<div class="font_11 bold inline top" style="margin:5px 0 0 3px;"><?php echo lang('controlador/ação') ?></div>
					</div>
				</div>
			</div>
			
			<br />
			<br />
			<br />
			<br />
			<h5 class="inline top"><?php echo lang('Opções de Exibição')?></h5>
			<div id="saiba_mais_exibicao_pagina_link" class="inline top" style="margin:9px 0 0 5px !important">
				<div class="inline top"><a href="javascript:void(0)" onclick="show_area('saiba_mais_conteudo_pagina')"/><?php echo lang('Saiba mais')?></a></div>
				<img src="<?php echo URL_IMG ?>/icon_help.png" style="margin:1px 0 0 2px" />
			</div>
			<div id="saiba_mais_exibicao_pagina_box" class="fb_title top font_11" style="display:none !important;margin:0px 0 0 10px;position:absolute;">
				<img src="<?php echo URL_IMG ?>/bg_arrow_form.png" style="float:left;margin:0 0 0 -18px" />
				<span><?php echo lang('Defina nesta seção se o conteúdo previamente cadastrado estará embutido na página master do site.'); ?></span>
			</div>
			<hr style="margin-bottom:10px;" />
			<div id="form_line">
				<div class="form_label font_11 bold" style="width:150px"><?php echo lang('Utilizar Página Master ?')?>*</div>
				<div class="form_field">
					<input type="radio" name="show_master_page" id="show_master_page" class="validate[required]" value="Y" style="margin:7px 5px 0 0" <?php echo (get_value($form_data, 'show_master_page') == '' || get_value($form_data, 'show_master_page') == 'Y') ? 'checked="checked"' : ''; ?> /><h6 class="inline top"><?php echo lang('Sim') ?></h6>
					<br />
					<input type="radio" name="show_master_page" id="show_master_page" class="validate[required]" value="N" style="margin:7px 5px 0 0" <?php echo (get_value($form_data, 'show_master_page') == 'N') ? 'checked="checked"' : ''; ?> /><h6 class="inline top"><?php echo lang('Não') ?></h6>
				</div>
			</div>
			
			<div style="margin-top:35px">
				<hr />
				<div style="margin:10px 3px 0 0" class="inline top"><input type="submit" value="<?php echo lang('Salvar Página URL')?>" /></div>
				<div style="margin:18px 0px 0 0" class="inline top">ou <a href="<?php echo URL_ROOT . '/' . $this->controller ?>"><?php echo lang('cancelar')?></a></div>
			</div>
		</form>
	</div>
</div>