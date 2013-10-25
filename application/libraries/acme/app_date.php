<?php
/**
*
* Classe App_Date
*
* Esta biblioteca gerencia a manipulação de datas no sistema. Assume-se que para toda a aplicação
* o formato básico de datas seja "DD/MM/AAAA HH:mm:ss", ou "d/m/Y H:i:s". Operações em cima de datas
* serão feitas em cima deste formato.
* 
* @since		31/10/2012
* @location		acme.libraries.date
*
*/
class App_Date  {
	// Definição de Atributos
	var $codeigniter = null;
	
	/**
	* __construct()
	* Construtor de classe.
	* @return object
	*/
	public function __construct()
	{
	}
	
	/**
	* get_month_to_words()
	* Converte um numero inteiro de um mês na string do mês por extenso.	
	* @param int month_number
	* @return string month_word
	*/
    public function get_month_to_words($month_number = 0)
    {				 
		switch($month_number)
		{
			case 1:	$month_word = lang("Janeiro");
				break;
			case 2:  $month_word = lang("Fevereiro");
				break;
			case 3:  $month_word = lang("Março");
				break;
			case 4:  $month_word = lang("Abril");
				break;
			case 5:  $month_word = lang("Maio");
				break;
			case 6:  $month_word = lang("Junho");
				break;
			case 7:  $month_word = lang("Julho");
				break;
			case 8:  $month_word = lang("Agosto");
				break;
			case 9:  $month_word = lang("Setembro");
				break;
			case 10: $month_word = lang("Outubro");
				break;
			case 11: $month_word = lang("Novembro");
				break;
			case 12: $month_word = lang("Dezembro");
				break;			
			default: $month_word = '';
				break;
		}
		return $month_word;
    }
}