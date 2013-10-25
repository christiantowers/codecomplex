<?php
/**
* -------------------------------------------------------------------------------------------------
* Dir Helper
*
* Centraliza funções relativas a manipulação de diretórios dentro do sistema.
* 
* @since		19/07/2013
* @location		acme.helpers.dir
*
* -------------------------------------------------------------------------------------------------
*/

/**
* delete_dir()
* Deleta um diretório e todo o conteúdo interno, incluindo diretórios ocultos.
* @param string dir
* @param boolean delete_dir
* @return void
*/
function delete_dir($dir = '', $delete_dir = true)
{
    // Ajusta as permissões do diretório
	if(file_exists($dir))
	{
		chmod($dir, 0777);
		
		// Abre diretório e tenta apagar tudo de dentro
		if(!$dh = @opendir($dir)) return false;
		while (false !== ($obj = readdir($dh))) {
			
			// Não deleta diretorios anteriores
			if($obj == '.' || $obj == '..') {
				continue;
			}
			
			// Acerta as permissões do diretório para ser deletado
			if(file_exists($dir))
				chmod($dir . '/' . $obj, 0777);
			
			if(!@unlink($dir . '/' . $obj)) {
				delete_dir($dir . '/' . $obj, true);
			}
		}
		closedir($dh);
		if ($delete_dir){
			return @rmdir($dir);
		}
	}
	
	return true;
}