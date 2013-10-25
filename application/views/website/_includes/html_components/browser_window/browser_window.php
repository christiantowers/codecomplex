<?php
/**
* browser_window()
* Monta uma janela estilo browser, inserindo o conteudo recebido como parametro em seu interior.
* @param string content
* @return string html
*/
function browser_window($content = '')
{
    $html = '
    <link type="text/css" rel="stylesheet" href="' . URL_CSS_WEBSITE . '/chrome.window.css" />
    
    <div class="ChromeContainer">
        <div class="Chrome">
            <div class="ChromeBar">
                <div class="Back"><a href="#"></a></div>
                <div class="Next"><a href="#"></a></div>
                <div class="Refresh"><a href="#"></a></div>
            </div>
            <div class="ChromeContent">' . $content . '</div>
        </div>
    </div>';

    // Retorno da função é o HTML da janela
    return $html;
}