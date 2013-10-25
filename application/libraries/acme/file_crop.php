<?php
/**
*
* Classe File_Crop
*
* Esta biblioteca gerencia funções de file_crop.
* 
* @since		03/04/2013
* @location		acme.libraries.file_crop
*
*/
class File_Crop {
	// Definição de Atributos	
	var $CI = null;
	
	var $file_name   = '';
	var $thumb_name  = '';
	var $error       = '';
	var $file_width  = '';
	var $file_height = '';
	
	// Prefixo da imagem 
	var $IMG_PREFIX  = "resize_";
	
	// Prefixo da miniatura
	var $TMB_PREFIX  = "thumbnail_";	
	
	// Limite das Dimensões da imagem
	var $MAX_FILE 	      = "4096"; 			
	var $MAX_WIDTH        = "2000";		
	var $MAX_WIDTH_RESIZE = "600";		
	var $MAX_HEIGHT       = "2000";
	
	// Altura e Largura da miniatura
	var $THUMB_WIDTH      = "80";
	var $THUMB_HEIGHT     = "80";
	
	/**
	* __construct()
	* Construtor de classe.
	* @return object
	*/
	public function __construct()
	{
	}	
	
	/**
	* resize_image()
	* Função redimensiona imagem carregada com base nas restrições definidas.
	* @param string image
	* @param integer width
	* @param integer height	
	* @param string scale 
	* @return string image
	*/
	public function resize_image($image, $width, $height, $scale)
	{
		list($imagewidth, $imageheight, $imageType) = getimagesize($image);
		$imageType = image_type_to_mime_type($imageType);
		$newImageWidth = ceil($width * $scale);
		$newImageHeight = ceil($height * $scale);
		$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
		switch($imageType)
		{
			case "image/gif":
				$source=imagecreatefromgif($image); 
			break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				$source=imagecreatefromjpeg($image); 
			break;
			case "image/png":
			case "image/x-png":
				$source=imagecreatefrompng($image); 
			break;
		}
		
		imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
		
		switch($imageType) 
		{
			case "image/gif":
				imagegif($newImage,$image); 
			break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				imagejpeg($newImage,$image,90); 
			break;
			case "image/png":
			case "image/x-png":
				imagepng($newImage,$image);  
			break;
		}
		
		chmod($image, 0777);
		return basename($image);
	}
	
	/**
	* resize_thumbnail_image()
	* Função que redimensiona miniatura com base nas coordenadas passadas por parâmetro
	* @param string thumb_image_name
	* @param string image
	* @param integer width	
	* @param integer height	
	* @param integer start_width	
	* @param integer start_height	
	* @param string scale 
	* @return void
	*/
	public function resize_thumbnail_image($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale)
	{
		list($imagewidth, $imageheight, $imageType) = getimagesize($image);
		$imageType = image_type_to_mime_type($imageType);
		$newImageWidth = ceil($width * $scale);
		$newImageHeight = ceil($height * $scale);
		$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
		switch($imageType) 
		{
			case "image/gif":
				$source=imagecreatefromgif($image); 
			break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				$source=imagecreatefromjpeg($image); 
			break;
			case "image/png":
			case "image/x-png":
				$source=imagecreatefrompng($image); 
			break;
		}
		
		imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
		
		switch($imageType) 
		{
			case "image/gif":
				imagegif($newImage,$thumb_image_name); 
			break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":				
				imagejpeg($newImage,$thumb_image_name,90); 
			break;
			case "image/png":
			case "image/x-png":
				imagepng($newImage,$thumb_image_name);  
			break;
		}		
	}
	
	/**
	* get_height()
	* Retorna a altura da imagem passada por parâmetro.
	* @param string image
	* @return integer height
	*/
	public function get_height($image) 
	{
		$size = getimagesize($image);
		$height = $size[1];
		return $height;
	}
	
	/**
	* get_width()
	* Retorna a largura da imagem passada por parâmetro.	
	* @param string image
	* @return integer width
	*/
	public function get_width($image) 
	{
		$size = getimagesize($image);
		$width = $size[0];
		return $width;
	}

	/**
	* upload_file()
	* Função que realiza o upload da imagem 	
	* e a redimensiona nos tamanhos definidos.
	* @return boolean 
	*/
	public function upload_file()
	{
		$this->CI =& get_instance();	
		
		// carrega parâmetros para aplicar na imagem a ser carregada
		$config['upload_path']   = PATH_UPLOAD . '/user_photos/';
		$config['allowed_types'] = 'gif|jpeg|jpg|png';
		$config['max_size']	     = $this->MAX_FILE;
		$config['max_width']     = $this->MAX_WIDTH;
		$config['max_height']    = $this->MAX_HEIGHT;
		$config['file_name']     = $this->IMG_PREFIX.uniqid();
		
		$this->CI->load->library('upload', $config);
		
		if(!$this->CI->upload->do_upload('image'))
		{
			// caso ocorra algum erro ao carregar a imagem.
			$this->error = $this->CI->upload->display_errors();
			return false;
		} else {
			$file_data = $this->CI->upload->data();		
			$userfile_name    = $file_data['orig_name'];
			// $userfile_tmp  = $file_data['tmp_name'];
			$userfile_size    = $file_data['file_size'];
			$userfile_type    = $file_data['file_type'];
			$file_ext 		  = $file_data['file_ext'];
			$this->file_name  = $file_data['file_name'];			
			$this->thumb_name = $this->TMB_PREFIX.$file_data['file_name'];			
			
			// altura e largura
			$width = $this->get_width($file_data['full_path']);
			$height = $this->get_height($file_data['full_path']);
			
			// Scale the image if it is greater than the width set above
			if ($width > $this->MAX_WIDTH_RESIZE){
				$scale = $this->MAX_WIDTH_RESIZE/$width;
				$this->resize_image($file_data['full_path'], $width, $height, $scale);
			}else{
				$scale = 1;
				$this->resize_image($file_data['full_path'], $width, $height, $scale);
			}
			
			$this->file_width = $this->get_width($file_data['full_path']);
			$this->file_height = $this->get_height($file_data['full_path']);
			return true;
		}	
	}
	
	/**
	* save_thumb()
	* Salva miniatura e/ou redimensiona com base na imagem grande.	
	* @param array post
	* @return void
	*/
	public function save_thumb($post = array())
	{
		
		$file_name = $post['file_name']; 
		$thumb_name = $post['thumb_name'];
		
		// Get the new coordinates to crop the image.
		$x1 = $post["x1"];
		$y1 = $post["y1"];
		$x2 = $post["x2"];
		$y2 = $post["y2"];
		$w = $post["w"];
		$h = $post["h"];
		
		// Scale the image to the thumb_width set above
		$scale = $this->THUMB_WIDTH/$w;
		
		$cropped = $this->resize_thumbnail_image(PATH_UPLOAD."/user_photos/".$thumb_name, PATH_UPLOAD."/user_photos/".$file_name, $w, $h, $x1, $y1, $scale);
		return true;
	}

	/**
	* set_file_name()
	* Modifica o nome do arquivo imagem.
	* @param string name
	* @return void
	*/
	public function set_file_name($name = '')
	{
		$this->file_name = $name;
	}
	
	/**
	* set_thumb_name()
	* Modifica o nome da miniatura. 
	* @param string name
	* @return void
	*/
	public function set_thumb_name($name = '')
	{
		$this->thumb_name = $name;
	}
	
	/**
	* delete_file()
	* Deleta arquivos passado como parâmetro (caminho completo). 
	* @param string path_name
	* @return void
	*/
	public function delete_file($path_name = '')
	{
		// verifica se existe o arquivo
		if(file_exists($path_name)){
			return unlink($path_name) ? true : false;				
		}
	}
}