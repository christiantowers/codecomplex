<?php
/**
* logo_area()
* Fun��o de montagem do bloco do logotipo padr�o do sistema. Quando invocada pelo objeto template do 
* acme engine, recebe automaticamente a url da imagem e um link para redirecionamento, quando clicada.
* @param string img
* @param string link
* @return string logo
*/
function logo_area($img = '', $url = '')
{
	return '<a href="' . $url . '"><img src="' . $img . '" style="max-width:150px;" /></a>';
}