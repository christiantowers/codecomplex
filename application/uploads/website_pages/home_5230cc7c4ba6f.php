<script type="text/javascript" language="javascript">
	// Atacha eventos nas caixas coloridas
	$(document).ready(function(){
		$('#box_use').mouseover(function(){
			$(this).css('box-shadow', '3px 3px 30px rgb(150,150,150)');
			$(this).css('background-image', 'url(<?php echo URL_IMG_WEBSITE ?>/home-use-bg.png)')
			$(this).css('background-position', 'top center');
			$(this).css('background-color', '#fff');
			$('#box_details_use').slideDown(250);
		});
		
		$('#box_use').mouseleave(function(){
			$('#box_details_use').slideUp(250);
			$(this).css('box-shadow', 'none');
			$(this).css('background-image', 'url(<?php echo URL_IMG_WEBSITE ?>/home-use-line.png)')
			$(this).css('background-position', '0 147px');
			$(this).css('background-color', 'transparent');
		});
		
		$('#box_descubra').mouseover(function(){
			$(this).css('box-shadow', '3px 3px 30px rgb(150,150,150)');
			$(this).css('background-image', 'url(<?php echo URL_IMG_WEBSITE ?>/home-descubra-bg.png)')
			$(this).css('background-position', 'top center');
			$(this).css('background-color', '#fff');
			$('#box_details_descubra').slideDown(250);
		});
		
		$('#box_descubra').mouseleave(function(){
			$('#box_details_descubra').slideUp(250);
			$(this).css('box-shadow', 'none');
			$(this).css('background-image', 'url(<?php echo URL_IMG_WEBSITE ?>/home-descubra-line.png)')
			$(this).css('background-position', '0 147px');
			$(this).css('background-color', 'transparent');
		});
		
		$('#box_compartilhe').mouseover(function(){
			$(this).css('box-shadow', '3px 3px 30px rgb(150,150,150)');
			$(this).css('background-image', 'url(<?php echo URL_IMG_WEBSITE ?>/home-compartilhe-bg.png)')
			$(this).css('background-position', 'top center');
			$(this).css('background-color', '#fff');
			$('#box_details_compartilhe').slideDown(250);
		});
		
		$('#box_compartilhe').mouseleave(function(){
			$('#box_details_compartilhe').slideUp(250);
			$(this).css('box-shadow', 'none');
			$(this).css('background-image', 'url(<?php echo URL_IMG_WEBSITE ?>/home-compartilhe-line.png)')
			$(this).css('background-position', '0 147px');
			$(this).css('background-color', 'transparent');
		});
	});
	function fadeImage(id_image, src)
	{
		$('#' + id_image).fadeOut(100, function(){
			$(this).attr('src', src).bind('onreadystatechange load', function(){
				if (this.complete) $(this).fadeIn(100);
			});
		});
	}
</script>
<style type="text/css">
	.title-welcome {
		margin:50px 0px 30px 0 !important;
		font-family:'Open Sans Condensed Light';
  		font-size:52px !important;
		font-weight:100 !important;
		text-align:center;
	}
	
	#box_use, #box_descubra, #box_compartilhe {
		cursor:pointer;
		text-align:center;
		width:350px;
		min-height:250px;
		background-position:-100px 147px;
  		background-repeat:repeat-x;
		position:absolute;
		border-radius:3px 3px 0 0;
	}
	
	#box_use {
		width:295px;
		background-image:url(<?php echo URL_IMG_WEBSITE ?>/home-use-line.png);
	}
	
	#box_descubra {
		width:295px;
		margin-left:295px;
		background-image:url(<?php echo URL_IMG_WEBSITE ?>/home-descubra-line.png);
	}
	
	#box_compartilhe {
		width:290px;
		margin-left:590px;
		background-image:url(<?php echo URL_IMG_WEBSITE ?>/home-compartilhe-line.png);
	}
	
	#box_use h2, #box_descubra h2, #box_compartilhe h3 {
		margin:20px 0;
		color:#6f2928;
	}
	
	#box_details_use, #box_details_descubra, #box_details_compartilhe {
		display:none;
		color:#000;
		margin:20px 15px;
	}
	
</style>
<div>
	<h1 class="title-welcome">Construir aplicações web em php nunca foi tão <strong>fácil</strong>.</h1>
	<div id="box_use" class="inline top" onclick="redirect('<?php echo URL_ROOT?>/downloads')">
      	<img src="<?php echo URL_IMG_WEBSITE ?>/home-use-hexa.png" style="margin-top:40px;" />
		<div id="box_details_use"><h5>Faça download da versão mais recente do ACME Engine ou procure por atualizações.</h5></div>
		<h2>USE</h2>
	</div>
	<div id="box_descubra" class="inline top" onclick="redirect('<?php echo URL_ROOT?>/documentation')">
      	<img src="<?php echo URL_IMG_WEBSITE ?>/home-descubra-hexa.png" style="margin-top:40px;" />
		<div id="box_details_descubra"><h5>Leia a documentação e siga os tutoriais, saiba como construir uma aplicação melhor.</h5></div>
		<h2>DESCUBRA</h2>
	</div>
	<div id="box_compartilhe" class="inline top" onclick="redirect('<?php echo URL_ROOT?>/support/share')">
      	<img src="<?php echo URL_IMG_WEBSITE ?>/home-compartilhe-hexa.png" style="margin-top:40px;" />
		<div id="box_details_compartilhe"><h5>Ajude a melhorar o ACME Engine. Seja um colaborador!</h5></div>
		<h3 style="margin-top:32px">COMPARTILHE</h3>
	</div>
  	
  	<div class="inline top" style="margin-top:430px;width:280px;">
		<h5 style="margin-bottom:5px">O que é o ACME Engine?</h5>
		<p>ACME Engine é um sistema básico com um conjunto pronto de funcionalidades, módulos e recursos que auxiliam na construção de uma nova aplicação web escrita em PHP.</p>
		<p>Após instalado, o ACME se torna a nova aplicação. Você conta com a vantagem de construir seu novo sistema utilizando os recursos prontos dele.</p>
		<p><h6><a href="<?php echo URL_ROOT ?>/documentation/more-about-acme-engine">Saiba mais!</a></h6></p>
	</div>
	
	<div class="inline top" style="margin-top:430px;width:280px;margin-left:20px;">
		<h5 style="margin-bottom:5px">Como o ACME Engine funciona?</h5>
		<p>Você baixa e instala o ACME Engine em seu servidor web. Depois de instalado o ACME Engine se torna sua nova aplicação. Simples assim.</p>
		<p>Uma API de funcionalidades do ACME fica disponível para os módulos da sua nova aplicação. Você ganha em tempo, flexibilidade e agilidade.</p>
		<p><h6><a href="<?php echo URL_ROOT ?>/documentation/more-about-acme-engine/organization-and-operation">Saiba mais!</a></h6></p>
	</div>
	
	<div class="inline top" style="margin-top:430px;width:270px;margin-left:20px;">
		<h6 style="margin-bottom:5px">Que funcionalidades o ACME possui?</h6>
		<p>ACME Engine disponibiliza uma série de recursos e funcionalidades para sua nova aplicação.</p>
		<div style="line-height:20px;margin-bottom:10px;">
			<div>&bull;&nbsp;API interna</div>
			<div>&bull;&nbsp;Gestão de usuários</div>
			<div>&bull;&nbsp;Gestão de permissões</div>
			<div>&bull;&nbsp;Menus de sistema</div>
			<div>&bull;&nbsp;Rastreador de erros</div>
			<div id="muito_mais" style="display:none">
				<div>&bull;&nbsp;Construtor de Módulos</div>
				<div>&bull;&nbsp;Administração de módulos</div>
				<div>&bull;&nbsp;Dashboard padrão</div>
				<div>&bull;&nbsp;Gestão de logs</div>
				<div>&bull;&nbsp;Internacionalização</div>
				<div>&bull;&nbsp;Módulo de relatórios</div>
				<div>&bull;&nbsp;Perfil de Usuário</div>
			</div>
		</div>
		<div><h6><a href="javascript:void(0)" onclick="show_area_slide('muito_mais')">E muito mais!</a></h6></div>
	</div>
</div>