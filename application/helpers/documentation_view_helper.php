<?php
/**
* -------------------------------------------------------------------------------------------------
* documentation_view Helper
*
* Centraliza funções de visualização de conteúdo da página de documentação. Disponível apenas no
* interior do website (conteúdos).
*
* @since		14/08/2013
* @location		helpers.documentation_view_helper
* -------------------------------------------------------------------------------------------------
*/

/**
* start_table_params()
* Inicializa uma tabela de listagem de parâmetros, contento as colunas: Param/Ret, Tipo, Nome e Descrição.
* @param string id
* @return string html
*/
function start_table_params($id = '')
{
	$id = $id != '' ? 'id="' . $id . '"' : '';
	$html = '<table class="table-params" ' . $id . '>
	<thead>
		<tr>
			<th class="nowrap" style="width:01%;">Param./Ret.</th>
			<th style="width:01%;">Tipo</th>
			<th style="width:01%;">Nome</th>
			<th style="width:97%;">Descrição</th>
		</tr>
	</thead>
	<tbody>';
	
	return $html;
}

/**
* add_row_param()
* Adiciona uma linha de parâmetro à tabela de parâmetros.
* @param string param_ret
* @param string type
* @param string name
* @param string description
* @return string html
*/
function add_row_param($param_ret = '', $type = '', $name = '', $description = '')
{
	$html = '
	<tr>
		<td>' . $param_ret . '</td>
		<td>' . $type . '</td>
		<td>' . $name . '</td>
		<td>' . $description . '</td>
	</tr>';
	
	return $html;
}

/**
* end_table_params()
* Finaliza uma tabela de listagem de parâmetros.
* @return string html
*/
function end_table_params()
{
	$html = '
	</tbody>
	</table>';
	
	return $html;
}

/**
* start_table_helpers_vs_methods()
* Inicializa uma tabela de listagem de comparação entre métodos de bibliotecas e respectivos ajudantes.
* @param string id
* @return string html
*/
function start_table_helpers_vs_methods($id = '')
{
	$id = $id != '' ? 'id="' . $id . '"' : '';
	$html = '<table class="table-params" ' . $id . '>
	<thead>
		<tr>
			<th class="nowrap" style="width:01%;">Biblioteca</th>
			<th class="nowrap" style="width:01%;">Método</th>
			<th style="width:98%;">Função Helper Equivalente</th>
		</tr>
	</thead>
	<tbody>';
	
	return $html;
}

/**
* add_row_helpers_vs_methods()
* Adiciona uma linha de comparação de método e biblioteca.
* @param string library
* @param string method
* @param string helper
* @return string html
*/
function add_row_helpers_vs_methods($library = '', $method = '', $helper = '')
{
	$html = '
	<tr>
		<td>' . $library . '</td>
		<td class="script nowrap">' . $method . '</td>
		<td class="bold script">' . $helper . '</td>
	</tr>';
	
	return $html;
}

/**
* end_table_helpers_vs_methods()
* Finaliza uma tabela de comparação método vs helper.
* @return string html
*/
function end_table_helpers_vs_methods()
{
	$html = '
	</tbody>
	</table>';
	
	return $html;
}

/**
* start_table_class_methods()
* Inicializa uma tabela de listagem de métodos de classe.
* @param string id
* @return string html
*/
function start_table_class_methods($id = '')
{
	$id = $id != '' ? 'id="' . $id . '"' : '';
	$html = '<table class="table-params" ' . $id . '>
	<thead>
		<tr>
			<th class="nowrap" style="width:01%;">Método</th>
			<th class="nowrap" style="width:99%;">Descrição Rápida</th>
		</tr>
	</thead>
	<tbody>';
	
	return $html;
}

/**
* add_row_class_methods()
* Adiciona uma linha de método da classe.
* @param string method
* @param string description
* @return string html
*/
function add_row_class_methods($method = '', $description = '')
{
	$html = '
	<tr>
		<td class="script nowrap" style="text-decoration:none !important">' . $method . '</td>
		<td>' . $description . '</td>
	</tr>';
	
	return $html;
}

/**
* end_table_class_methods()
* Finaliza uma tabela de métodos de classe.
* @return string html
*/
function end_table_class_methods()
{
	$html = '
	</tbody>
	</table>';
	
	return $html;
}

/**
* start_table_class_attributes()
* Inicializa uma tabela de listagem de atributos de classe.
* @param string id
* @return string html
*/
function start_table_class_attributes($id = '')
{
	$id = $id != '' ? 'id="' . $id . '"' : '';
	$html = '<table class="table-params" ' . $id . '>
	<thead>
		<tr>
			<th class="nowrap" style="width:01%;">Atributo</th>
			<th class="nowrap" style="width:99%;">Descrição Rápida</th>
		</tr>
	</thead>
	<tbody>';
	
	return $html;
}

/**
* start_table_helpers()
* Inicializa uma tabela de listagem de funções de determinado helper.
* @param string id
* @return string html
*/
function start_table_helpers($id = '')
{
	$id = $id != '' ? 'id="' . $id . '"' : '';
	$html = '<table class="table-params" ' . $id . '>
	<thead>
		<tr>
			<th class="nowrap" style="width:01%;">Função</th>
			<th class="nowrap" style="width:99%;">Descrição Rápida</th>
		</tr>
	</thead>
	<tbody>';
	
	return $html;
}