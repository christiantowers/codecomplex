<?php
/**
* website_footer()
* Monta o footer do site.
* @param string url
* @return string html
*/
function website_footer($url='')
{	
	$html = '
	<style type="text/css">
	
	#site-what-the-peaple-say{	
		overflow:auto; 
		height:393px; 
		border-top: thin solid #aaa; 
		padding-top: 6px; 
		background-image: url(\''. URL_IMG_WEBSITE .'/border-square.png\');  
		background-position: top;  
		background-repeat: repeat-x;
	}

	#circle-wps{
		position: relative;  
		background-image: url(\''. URL_IMG_WEBSITE .'/what-the-peaple-say.png\'); 
		width: 140px; 
		height: 140px; 
		margin-top: 110px; 
		margin-left: 25%;"
	}
	
	#content-wps{
		position: relative; 
		width: auto; 
		height: 163px; 
		margin-top: -150px; 
		margin-left: 40%;	
	}
	
	.baloom{
		background-image: url(\''. URL_IMG_WEBSITE .'/baloom.png\'); 
		width: 298px; 
		height: 163px; 
		float: left; 
		text-align:center; 
		display:inline; 
	}
	
	.name-baloom{
		position: relative;  
		width: 292px; 
		height: 70px; 
		margin-top: 50px; 
		text-align:center;
	}
	
	#arrow-wps{
		position:absolute; 
		right: 0; 
		margin-top: -105px; 
		width: 34px; 
		height: 51px;
		background-image:url(\''. URL_IMG_WEBSITE .'/arrow-small.png\');
	}
	
	#line-wps{
		margin-top: -85px; 
		width: 100%; 
		height: 6px; 
		background-color:#d3e1e4;
	}
	
	.text-wps{
		font-family: \'Open Sans Condensed Light\'; 
		color:#898989;
	}
	
	#footer-col-news{
		margin-left:5px;
		width:380px; 
		padding-right:20px; 
		margin-top:20px; 
		text-align:center;
	}
	
	#site-footer-text-news{
		font-family: \'Open Sans Condensed Light\'; 
		color:#898989; 
		font-size:28px; 
		line-height:90%;
	}
	#footer-col-img{
		position: relative; 
		height: 90px; 
		width: 100%; 
		margin-top: 15px;	
	}
	
	#footer-col-about{
		height: 100px; 
		margin-left:5px;
		width:300px; 
		padding-right:20px; 
		padding-left:20px; 
		margin-top:20px; 
		text-align:center; 
		border-left:thin solid #898989; 
		border-right:thin solid #898989;
	}
	
	p .about{
		font-family: \'Open Sans Condensed\'; 
		color:#eee; 		 		
	}
	
	#line-about{
		position: relative; 
		width: 80px; 
		border-bottom:medium solid #eee; 
		margin: auto; 
		padding-top: 15px;
	}
	
	#footer-text-about{
		padding: 0 20px 0 20px; 
		font-family: tahoma; 
		color:#898989; 
		font-size:12px; 
		line-height:120%; 
		margin-top:20px;	
	}
	
	#footer-col-map{
		margin-left:5px;width:200px;  
		padding-left:20px; 
		margin-top:20px; 
	}
	
	#footer-map{
		color: #898989; 						
	}
	
	
	</style>
	
	<table style=" height:'.((basename($url)!='home')?'363px;': '100%').'; width:100%; ">

		<tr height="393" style="background-color:#eeeeee; '.((basename($url)!='home')?'display:none; ': '').' " >
		<td>

		<div id="site-what-the-peaple-say" >

		<div id="circle-wps" style="" ></div>

		<div id="content-wps" style="">

			<div class="baloom" style="position: relative;" >
				<div style="padding: 10px; margin-top:10px">
					<p class="font_11" style="color:#898989;">Pela minha experiência em desenvolvimento, acredito que a codecomplex supre qualquer necessidade no contexto de soluções de TI para qualquer pequena empresa de pequeno e médio porte.</p>
				</div>
				<div class="name-baloom" style="" >
					<table style="text-align: left; margin-left: auto; margin-right: auto;">
						<tr>
						<td>
							<img style="margin: 5px 5px 5px 5px;  " src="'. URL_IMG_WEBSITE .'/baloom-photo-1.png">
						</td>
						<td>
							<h5>Christian Torres</h5>	
							<p class="text-wps" style="">Co-fundador e CEO.</p>
						</td>
						</tr>
					</table>
				</div>
			</div>

			<div class="baloom" style="position: absolute; margin-left: 25px; " >
				<div style="padding: 10px; margin-top:10px">
					<p class="font_11" style="color:#898989;" >Pela minha experiência em desenvolvimento, acredito que a codecomplex supre qualquer necessidade no contexto de soluções de TI para qualquer pequena empresa de pequeno e médio porte.</p>
				</div>
				<div class="name-baloom" style="" >
					<table style="text-align: left; margin-left: auto; margin-right: auto;">
						<tr>
						<td>
							<img style="margin: 5px 5px 5px 5px;  " src="'. URL_IMG_WEBSITE .'/baloom-photo-2.png">
						</td>
						<td>
							<h5>Leandro Antunes</h5>	
							<p class="text-wps" style="">Co-fundador e CTO.</p>
						</td>
						</tr>
					</table>
				</div>
			</div>

		</div>

		<div id="arrow-wps" style="" ></div>

		<div  id="line-wps" style=" " >	</div>

		</div>
		</td>
		</tr>
		<tr height="303">
		<td id="site-footer-content" style="background-color:#1a212b;">
		<div class="font_shadow_black" id="site-footer">
			<div id="footer-col-news" style="" class="inline top">
				<p id="site-footer-text-news" style=" ">News: Lançamento do ACME Engine 1.0 Sistema dorsal para construção de sistemas.</p>
				<a href="#">http://goo.gl/eDmlR1</a>
				
				<div id="footer-col-img" style="">
					<a target="_blank" href="https://twitter.com/acmeengine" >      <img style="margin: 5px 15px 5px 5px;   display:inline;"  src="'. URL_IMG_WEBSITE .'/share-twitter.png"></a>
					<a target="_blank" href="https://www.facebook.com/codecomplex" ><img style="margin: 5px 15px 5px 15px;  display:inline;"  src="'. URL_IMG_WEBSITE .'/share-facebook.png"></a>
					<a target="_blank" href="https://www.facebook.com/codecomplex" ><img style="margin: 5px 5px 5px 15px;   display:inline;"  src="'. URL_IMG_WEBSITE .'/share-googleplus.png"></a>
				</div>
				
			</div>
			
			<div id="footer-col-about" style=" " class="inline top">
				<p class="about" style="font-size:18px; line-height:90%" >SOBRE CODE COMPLEX</p>		
				<div id="line-about" style=""></div>		
				
				<p id="footer-text-about" style="">Especializada em aplicações web, a Code Complex é a alternativa que combina rapidez e usabilidade na construção do site ou sistema que você precisa.</p>	
				
				<p class="about" style="font-size:18px; margin-top: 20px;">NEWSLETTER </p>	
				<div>
					<form action="javascript:ajax_save_newsletter();" id="form_newsletter" name="form_newsletter">
						<input id="email" name="email" onkeydown="get_enter( event , \'submit_newsletter()\')" type="text" size="30" style="background:#0a1923; border:1px solid #222932; color:#f5f5f5; " placeholder="digite seu email + enter" />
					</form>
					<div style="text-shadow:none;display:none" class="font_11" id="form_newsletter_message"><div id="message_general_5277d9956cb34"><div style="background-color: #BED7DE;  border: 1px solid #BED7DE;" class="msg_general"><div><img src="http://www.acmeengine.org/application/views/vertigo/_includes/img/icon_success.png"><div>Feito! A partir de agora vamos encaminhar para você notícias e novidades por email. Fique atento!</div></div></div></div></div>
				</div>
			</div>	
			
			<div id="footer-col-map" style="" class="inline top">
				
				<a class="footer-map" style="display: block; margin-bottom:10px;" href="'.URL_ROOT.'/home">Home</a>    
				<a class="footer-map" style="display: block; margin-bottom:10px;" href="'.URL_ROOT.'/servicos">Serviços</a>
				<a class="footer-map" style="display: block; margin-bottom:10px;" href="'.URL_ROOT.'/produtos">Produtos</a>
				<a class="footer-map" style="display: block; margin-bottom:10px;" href="'.URL_ROOT.'/empresa">Empresa</a>   
				<a class="footer-map" style="display: block; margin-bottom:10px;" href="'.URL_ROOT.'/contato">Contato</a> 
				
			</div>
		</div>

		</td>
		</tr> 
		<tr height="60">
			<td id="site-footer-logo" style="background-color:#0a1923;">
				<div class="font_shadow_black" id="site-footer" style="height:60px;">
					<img style="float:left;" src="'. URL_IMG_WEBSITE .'/logo_codecomplex_gray.png">
					<img style="float:right;" src="'. URL_IMG_WEBSITE .'/logo_acmecms_gray.png">
				</div>	
			</td>	
		</tr> 
		</table>';
	
	
	return $html;
}