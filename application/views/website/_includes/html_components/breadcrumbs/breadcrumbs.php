<?php
/**
* breadcrumbs()
* Esta função monta o breadcrumbs (migalhas de pão) do website com base na URL atualmente encaminhada.
* O processamento padrão é montar todos os links superiores até que não haja mais nenhum link "pai".
* @param string url
* @return string html
*/
function breadcrumbs($url = '')
{
	$html = '';
	
	// Coleta instancia do objeto CI, para conecta no banco de dados
	$CI =& get_instance();
	
	// Conecta no banco e retorna a página de URL encaminhada
	$page_url = $CI->db->get_where('cms_page_url', array('url' => $url));
	$page_url = $page_url->result_array();
	$page_url = (isset($page_url[0])) ? $page_url[0] : array();
	
	// verifica se existe um nível pai, primeiramente
	if(get_value($page_url, 'id_page_url_parent') != '' && get_value($page_url, 'id_page_url_parent') != 0)
	{
		$pup = $CI->db->get_where('cms_page_url', array('id_page_url' => get_value($page_url, 'id_page_url_parent')));
		$pup = $pup->result_array();
		$pup = (isset($pup[0])) ? $pup[0] : array();
		
		// Adiciona o pai primeiramente
		$html .= breadcrumbs(get_value($pup, 'url')) . '<div class="inline top" style="margin:1px 5px 0 5px;font-size:09px !important">></div>';
	}
	
	// Monta a linha com o link para a página atual
	$html .= '<div class="font_11 inline top"><a href="' . URL_ROOT . '/' . trim(get_value($page_url, 'url'), '/') . '" style="text-decoration:none;">' . get_value($page_url, 'title') . '</a></div>';
	return $html;
}