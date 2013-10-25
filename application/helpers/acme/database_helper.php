<?php
/**
* -------------------------------------------------------------------------------------------------
* Database Helper
*
* Centraliza funções quanto a manipulação do banco de dados em geral.
* 
* @since		05/03/2012
* @location		acme.helpers.database
*
* -------------------------------------------------------------------------------------------------
*/
	
/**
* query_array()
* Executa uma query encaminhada, retorna o resultado como array.
* @param string sql
* @return mixed result
*/
function query_array($sql = '')
{
	$CI =& get_instance();
	$data = $CI->db->query($sql);
	$data = $data->result_array();
	return (isset($data)) ? $data : array();
}