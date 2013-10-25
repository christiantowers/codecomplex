<?php
	function custom_menu_tree($menus = array()) 
	{
		$html = '';
		
		// Varre os menus cadastrados no banco, ordenados em formato de árvore
		foreach($menus as $menu)
		{
			// Contagem de menus-filho
			$count_menu_children = count(get_value($menu, 'children'));
			
			// Monta o label conforme possui filho ou nao
			if($count_menu_children > 0)
			{
				$html .= '<li id="menu_li_' . get_value($menu, 'id_menu') . '" class="dd-item dd3-item" data-id="' . get_value($menu, 'id_menu') . '" order="' . get_value($menu, 'order') . '">';
				$html .= '<div class="dd-handle dd3-handle">Drag</div><div class="dd3-content">';
					$html .= (get_value($menu, 'dtt_inative') != '') ? '<span class="font_red">' . lang(get_value($menu, 'rotule')) . '</span>' : lang(get_value($menu, 'rotule'));
					$html .= '<div id="box_controls" style="float:right;display:none;">';
					$html .= '<img src="' . URL_IMG . '/icon_update.png" title="Editar Menu do Sistema" onclick="iframe_modal(\'' . lang('Editar Menu do Sistema') . '\', \'' . URL_ROOT . '/cms_website_menu/ajax_modal_website_menu_update/' . get_value($menu, 'id_menu') . '\', \'' . URL_IMG . '/icon_update.png\', 700, 600)" style="cursor:pointer;margin-right:10px" />';
					$html .= '<img src="' . URL_IMG . '/icon_delete.png" title="Deletar Menu" onclick="iframe_modal(\'' . lang('Deletar Menu do Sistema') . '\', \'' . URL_ROOT . '/cms_website_menu/ajax_modal_website_menu_delete/' . get_value($menu, 'id_menu') . '\', \'' . URL_IMG . '/icon_delete.png\', 700, 600)" style="cursor:pointer" />';
					$html .= '</div>';
				$html .= '</div>';
				$html .= '<ol class="dd-list">' . custom_menu_tree(get_value($menu, 'children')) . '</ol></li>';
			} else {
				$html .= '<li id="menu_li_' . get_value($menu, 'id_menu') . '" class="dd-item dd3-item" data-id="' . get_value($menu, 'id_menu') . '" order="' . get_value($menu, 'order') . '">';
				$html .= '<div class="dd-handle dd3-handle">Drag</div><div class="dd3-content">';
					$html .= (get_value($menu, 'dtt_inative') != '') ? '<span class="font_red">' . lang(get_value($menu, 'rotule')) . '</span>' : lang(get_value($menu, 'rotule'));
					$html .= '<div id="box_controls" style="float:right;display:none;">';
					$html .= '<img src="' . URL_IMG . '/icon_update.png" title="Editar Menu do Sistema" onclick="iframe_modal(\'' . lang('Editar Menu do Sistema') . '\', \'' . URL_ROOT . '/cms_website_menu/ajax_modal_website_menu_update/' . get_value($menu, 'id_menu') . '\', \'' . URL_IMG . '/icon_update.png\', 700, 600)" style="cursor:pointer;margin-right:10px" />';
					$html .= '<img src="' . URL_IMG . '/icon_delete.png" title="Deletar Menu" onclick="iframe_modal(\'' . lang('Deletar Menu do Sistema') . '\', \'' . URL_ROOT . '/cms_website_menu/ajax_modal_website_menu_delete/' . get_value($menu, 'id_menu') . '\', \'' . URL_IMG . '/icon_delete.png\', 700, 600)" style="cursor:pointer" />';
					$html .= '</div>';
				$html .= '</div>';
				$html .= '</li>';
			}
		}
		return $html;
	}
?>
<?php echo $this->template->load_js_file('jquery.nestable.js'); ?>
<script type="text/javascript" language="javascript">
	$(document).ready(function() {
		// Monta o nestable na lista de menus
		$('.dd').nestable({
			dropCallback: function(details) {
				// alert(details.destId);
				ajax_reorder_website_menu(details.sourceId, details.destId);
			}
		});
		
		$('.dd3-content').on('mouseover', function() {
			$(this).find('#box_controls').show();
		});
		
		$('.dd3-content').on('mouseout', function() {
			$(this).find('#box_controls').hide();
		});
	});
</script>
<div>
	<input type="hidden" id="last_item_changed" value="" />
	<!-- CABEÇALHO DO MÓDULO e MENUS -->
	<div id="module_header">
		<div id="module_rotule" class="inline top">
			<h2 class="inline top font_shadow_gray"><a class="black" href="<?php echo URL_ROOT . '/' . $this->controller ?>"><?php echo lang($this->lang_key_rotule); ?></a></h2>
			<?php if($this->url_img != '') {?>
			<img src="<?php echo $this->url_img ?>" />
			<?php } ?>
		</div>
		
		<!-- MENUS DO MODULO -->
		<div id="module_menus" class="inline top">
			<div class="inline top module_menu_item" title="<?php echo lang('Novo Menu')?>">
				<img class="inline top" src="<?php echo URL_IMG ?>/icon_insert.png" />
				<h6 class="inline top"><a href="javascript:void(0)" onclick="iframe_modal('<?php echo lang('Novo Menu')?>', '<?php echo URL_ROOT ?>/cms_website_menu/ajax_modal_website_menu_new', '<?php echo URL_IMG ?>/icon_insert.png', 700, 600);"><?php echo lang('Novo Menu')?></a></h6>
			</div>
		</div>
	</div>
	
	<!-- DESCRICAO DO MODULO -->
	<div id="module_description"><?php echo $this->description; ?></div>
	
	<!-- LISTAGEM DE MENUS -->
	<br />
	<table>
	<tr>
		<td width="99%">
		<?php if(count($menus) > 0 ) {?>
		<div class="dd"><ol class="dd-list"><?php echo custom_menu_tree($menus); ?></ol></div>
		<?php } else { echo message('info', lang('Consulta Vazia'), lang('Nenhum menu cadastrado para o website.'), false, 'margin-top:5px'); } ?>
		</td>
		<td width="400" style="padding-left:50px">
			<!-- ÁREA DE CONTROLES -->
			<?php echo $this->template->start_box(lang('Guia Rápido'), URL_IMG . '/icon_info.png', 'width:400px;margin:5px 0 0 30px;clear:both');?>
				<div style="line-height:17px;">
					<h6><?php echo lang('Como utilizar este módulo?')?></h6>
					<div class="font_11"><?php echo lang('Ao lado está organizada a lista de menus do website. Este conjunto de menus é exibido nas páginas do website, quando é solicitado sua exibição.')?></div>
					<div class="font_11" style="margin-top:5px"><?php echo lang('Menus em <span class="font_red bold">vermelho</span> indicam sua inatividade. Ou seja, estes menus estão cadastrados mas não aparecem no site.')?></div>
					
					<br />
					<h6><?php echo lang('Mais Ações')?></h6>
					<h6>&bull;&nbsp;<a href="javascript:void(0)" onclick="iframe_modal('<?php echo lang('Novo Menu')?>', '<?php echo URL_ROOT ?>/cms_website_menu/ajax_modal_website_menu_new/', '<?php echo URL_IMG ?>/icon_insert.png', 700, 600);"><?php echo lang('Novo Menu')?></a></h6>
					<br />
				</div>
			<?php echo $this->template->end_box();?>
			<br />
			<div id="message_error_template" style="display:none;width:400px;margin:5px 0 0 30px;clear:both"><?php echo message('error', '', lang('Ops! Você não possui permissões para executar esta ação.'))?></div>
			<div id="message_success_template" style="display:none;width:400px;margin:5px 0 0 30px;clear:both"><?php echo message('success', '', lang('Menu reordenado com sucesso! Recarregue a página para visualizar o resultado.'))?></div>
		</td>
	</tr>
	</table>
</div>