<?php
/**
* feedback_box()
* Monta uma caixa contendo um formulário de feedback.
* @param string style
* @return string html
*/
function feedback_box($style = '')
{
	$style = ($style == '') ? 'min-width:285px;' : $style;
	$html  = start_box(lang('Nós queremos escutar você!'), URL_IMG_WEBSITE . '/icon_feedback.png', $style);
	$html .= '<form id="feedback_box" name="feedback_box" action="javascript:ajax_save_feedback();" onsubmit="">';
	$html .= '<p class="font_11">'. lang('Envie-nos uma mensagem compartilhando sua sugestão ou ideia sobre o ACME Engine ou este site.') . '</p>';
	$html .= '<div class="font_11 bold">' . lang('Seu Nome') . '</div>';
	$html .= '<div style="padding:5px 5px 15px 0 !important"><input type="text" class="validate[required] mini" name="name" id="name" style="width:100%;height:18px"/></div>';
	$html .= '<div class="font_11 bold">' . lang('Seu Email') . '</div>';
	$html .= '<div style="padding:5px 5px 15px 0 !important"><input type="text" class="validate[required,custom[email]] mini" name="email" id="email" style="width:100%;height:18px"/></div>';
	$html .= '<div class="font_11 bold">' . lang('Mensagem') . '</div>';
	$html .= '<div style="padding:5px 5px 15px 0 !important"><textarea class="mini" name="message" id="message" style="width:100%;height:50px"></textarea></div>';
	$html .= '<hr style="margin-bottom:5px" />';
	$html .= '<input type="submit" class="mini" value="Enviar" />';
	$html .= '</form>';
	$html .= '<div id="feedback_box_message" class="font_11" style="display:none">' . message('success', '', lang('Recebemos sua mensagem! Logo mais lhe enviaremos uma resposta. Obrigado!')) . '</div>';
	$html .= '<script type="text/javascript">';
	$html .= '	$(document).ready(function(){ enable_form_validations(\'feedback_box\'); });';
	$html .= '</script>';
	$html .= end_box();
	return $html;
}