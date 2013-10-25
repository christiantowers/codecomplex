<?php
/**
* chrome_window_start()
* Inicializa uma janela estilo chrome browser, para utilizar como efeito screenshot.
* @return string html
*/
function chrome_window_start()
{
	$html = '
	<div class="ChromeContainer">
		<div class="Chrome">
			<div class="ChromeBar">
				<div class="Back"><a href="#"></a></div>
				<div class="Next"><a href="#"></a></div>
				<div class="Refresh"><a href="#"></a></div>
			</div>
			<div class="ChromeContent">
			';
	return $html;
}