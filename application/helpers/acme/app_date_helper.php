<?php
/**
* -------------------------------------------------------------------------------------------------
* App_Date Helper
*
* Centraliza funções relativas à manipulação de datas da aplicação. Utiliza os métodos da biblioteca
* App_Date, do ACME Engine.
*
* A chamada das funções contidas neste arquivo ajudante são alias para os métodos de mesmo nome
* localizados na respectiva biblioteca (App_Date). Sendo assim, as instruções abaixo retornam o mesmo
* resultado esperado:
*	example_function(); // função localizada neste arquivo
* 	$this->app_date->example_function();
* 
* @since		15/07/2013
* @location		acme.helpers.app_date
*
* -------------------------------------------------------------------------------------------------
*/

/**
* get_month_to_words()
* Converte um numero inteiro de um mês na string do mês por extenso.	
* @param int month_number
* @return string month_word
*/
function get_month_to_words($month_number = 0)
{
	$CI =& get_instance();
	return $CI->app_date->get_month_to_words($month_number);
}