<?php
/**
* website_menu()
* Função de montagem dos menus do website atual. Quando invocada pelo objeto template, recebe 
* automaticamente um array de menus em organizados em formato de árvore, para construção em
* níveis e subníveis, utilizando recursão.
* @param array menus
* @return string html
*/
function website_menu($menus = array())
{
	// Verifica se o level atual é raiz ou não (PARENT <= 0)
	$linha_atual = isset($menus[0]) ? $menus[0] : array();
	$bool_root_level = (get_value($linha_atual, 'id_menu_parent') <= 0) ? true : false;
	
	// Inicializa bloco de menu
	$html = ($bool_root_level) ? '<div id="cssmenu" style="float:right">' : '';
	$html .= '<ul>';
	
	if(count($menus) > 0)
	{
		// Varre os menus cadastrados no banco, ordenados em formato de árvore
		foreach($menus as $menu)
		{
			// DEBUG: 
			// print_r($menu);
			
			// Contagem de menus-filho
			$count_menu_children = count(get_value($menu, 'children'));
			
			// Monta link
			$link = eval_replace(get_value($menu, 'link'));
			$target = (get_value($menu, 'target') != '') ? ' target="' . eval_replace(get_value($menu, 'target')) . '" ' : '';
			$img = (get_value($menu, 'url_img') != '') ? '<div style="display:inline-block !important;margin:0 10px 0 -3px;"><img src="' . eval_replace(get_value($menu, 'url_img')) . '" /></div>' : '';
			$title = eval_replace(get_value($menu, 'description'));
			$javascript = " " . eval_replace(get_value($menu, 'javascript')) . " ";
			$label = lang(get_value($menu, 'rotule'));
			
			// Monta o label conforme possui filho ou nao
			if($count_menu_children > 0)
			{
				$html .=  '<li class="has-sub"><h5><a href="' . $link . '"' . $target . $javascript . ' title="' . $title . '">' . $img . '<div style="display:inline-block !important">' . $label . '</div></a></h5>';
				$html .=  website_menu(get_value($menu, 'children')) . '</li>';
			} else {
				$html .=  '<li class="last"><h5><a href="' . $link . '"' . $target . $javascript . ' title="' . $title . '">' . $img . '<div style="display:inline-block !important">' . $label . '</div></a></h5></li>';
			}
		}
	} else {
		// Aviso de sem menus
		// $html .= '<li class="pureCssMenui"><a class="pureCssMenui" href="#" style="font-style:italic"><img src="' . URL_IMG . '/icon_warning.png" style="display:block" />' . lang('Nenhum menu cadastrado no banco de dados') . '</a></li>';
	}
	
	// Termina menu
	$html .= '</ul>';
	
	$html .= ($bool_root_level) ? '</div>' : '';
	
	return $html;
	
	/*
	ESTRUTURA PADRÃO DO CSSMENU
	<div id='cssmenu'>
	<ul>
	   <li><a href='#'><span>Home</span></a></li>
	   <li class='active has-sub'><a href='#'><span>Products</span></a>
		  <ul>
			 <li class='has-sub'><a href='#'><span>Product 1</span></a>
				<ul>
				   <li><a href='#'><span>Sub Product</span></a></li>
				   <li class='last'><a href='#'><span>Sub Product</span></a></li>
				</ul>
			 </li>
			 <li class='has-sub'><a href='#'><span>Product 2</span></a>
				<ul>
				   <li><a href='#'><span>Sub Product</span></a></li>
				   <li class='last'><a href='#'><span>Sub Product</span></a></li>
				</ul>
			 </li>
		  </ul>
	   </li>
	   <li><a href='#'><span>About</span></a></li>
	   <li class='last'><a href='#'><span>Contact</span></a></li>
	</ul>
	</div>
	*/
}