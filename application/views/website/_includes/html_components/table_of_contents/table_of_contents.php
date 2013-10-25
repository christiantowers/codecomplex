<?php
/**
* table_of_contents()
* Esta função é parecida com o breadcrumbs, exceto pelo fato de montar uma tabela de conteúdos, que é
* fixa começando pelo link URL /documentation. A partir deste nodo raiz todos os subnodos são montados,
* verificando se o nodo atual é igual a URL encaminhada (para montar os filhos da URL).
* @param string url
* @return string html
*/
function table_of_contents($url = '')
{
	$html = '';
	
	// Coleta instancia do objeto CI, para conectar no banco de dados
	$CI =& get_instance();
	
	// Coleta o id da pagina atual
	$sql = "SELECT p.id_page_url, 
				   p.id_page_url_parent,
				   p.url,
				   p.title,
				   CAST(IFNULL((SELECT COUNT(*) FROM cms_page_url p2 WHERE p2.id_page_url_parent = p.id_page_url), 0) AS UNSIGNED) AS count_children
			  FROM cms_page_url p
			 WHERE p.url = '" . $CI->db->escape_str($url) . "'";
	$page_url = query_array($sql);
	$page_url = isset($page_url[0]) ? $page_url[0] : array();
	
	// DEBUG:
	// print_r($page_url);
	
	// Conecta no banco e retorna todas as paginas de /documentation
	$sql = "SELECT p.id_page_url AS id,
				   IFNULL(p.id_page_url_parent, 0) AS id_parent,
				   p.url,
				   p.title
			  FROM cms_page_url p
		 LEFT JOIN wbs_toc_order o ON (o.id_page_url = p.id_page_url)
			 WHERE p.url like '/documentation%' AND p.dtt_inative IS null
		  ORDER BY p.id_page_url_parent, o.`order`";
	$toc = query_array($sql);
	
	// Converte tabela de conteudos para arvore
	$toc = build_tree_pages_url($toc);
	
	// Converte arvore para json
	$json = build_json_pages_url($toc);
	
	// Carrega arquivo da biblioteca de arvores
	$html .= load_js_file('jstree.1.0/jquery.jstree.js');
	
	// Seta o nodo que será aberto = url atual (ou o pai quando a atual não tiver filhos
	$initially_open = (get_value($page_url, 'count_children') <= 0) ? get_value($page_url, 'id_page_url_parent') : get_value($page_url, 'id_page_url');
	
	// Monta a arvore
	$html .= '
	<script type="text/javascript">
		$(document).ready(function(){
			/* Inicializa a árvore */
			$("#table_of_contents").jstree({
				
					"core" : {"initially_open" : "' . $initially_open . '"  },
					"json_data" : {
						"data" : [ ' . $json . ']
					},
					"plugins" : [ "themes", "json_data", "ui" ],
					"themes" : {"theme" : "website"}
			
			/* atacha evento para clique do link do nodo atual */
			}).bind("loaded.jstree", function (event, data) {
				/* Altera a fonte do link atual para negrito */
				$("#table_of_contents li#' . get_value($page_url, 'id_page_url') . ' > a").css("font-weight", "bold");
			}).bind("select_node.jstree", function(e,data) { 
				window.location.href = data.rslt.obj.find("a").attr("href"); 
			});
			
			$(window).scroll(function(){
				if($(window).scrollTop() >= 260){
					// alert("sss");
					$("#docs-table-of-contents").css({position:"fixed",top:"10px","margin-left":"4px"});
				} else {
					$("#docs-table-of-contents").css({position:"relative",margin:"-8px 0 0 0",top:0});
				}
			});
		});
	</script>';
	
	// Seta div target da tabela de conteudos (arvore)
	$html .= '<div id="table_of_contents" class="font_11"></div>';
	
	// Link de ir para o topo
	$html .= '<div class="font_11" style="background-color:#fff;height:50px;width:300px;float:left;margin:11px 0 0 -5px;"><a href="#site-header"><button type="button" style="margin:7px 0 10px 0px !important;"><img src="' . URL_IMG_WEBSITE . '/bullet_up.png" style="margin:-1px 5px 0 -7px" />Ir para o topo</button></a></div>';
	
	return $html;
}

/**
* build_json_pages_url()
* Monta uma string json com base no array de dados de URLs encaminhado. Espera-se que este array de
* dados esteja no formato semelhante ao de menu.
* @param array url_tree
* @return string json
*/
function build_json_pages_url($url_tree = array())
{
	$json = '';
	
	foreach($url_tree as $url)
	{
		// Inicia o nodo
		$json .= '
		{ 
			"attr" : { "id" : "' . get_value($url, 'id') . '" }, 
			"data" : { 
				"title" : "' . get_value($url, 'title') . '", 
				"attr" : { "href" : "' . URL_ROOT . get_value($url, 'url') .  '" }
			}';
		
		// Adiciona ao json o conteúdo dos filhos
		if(count(get_value($url, 'children')) > 0)
			$json .= ', "children": [ ' . build_json_pages_url(get_value($url, 'children')) . ' ]';		
		
		// Fecha o nodo
		$json .='},';
	}
	$json = trim($json, ',');
	
	return $json;
}

/**
* build_tree_pages_url()
* Recebe um resultset único de dados de páginas URL e os transforma em uma estrutura de array/arvore.
* @param array urls
* @return array url_tree
*/
function build_tree_pages_url(&$urls = array())
{
	$map = array(
		0 => array('childen' => array())
	);

	foreach ($urls as &$url) {
		$url['children'] = array();
		$map[$url['id']] = &$url;
	}

	foreach ($urls as &$url) {
		$map[$url['id_parent']]['children'][] = &$url;
	}

	return $map[0]['children'];
}