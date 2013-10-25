<script type="text/javascript" language="javascript">
	// Atacha eventos nas caixas coloridas
	$(document).ready(function(){
		$('#box_use').mouseover(function(){
			$(this).css('box-shadow', '3px 3px 30px rgb(150,150,150)');
			$('#box_details_use').slideDown(250);
		});
		
		$('#box_use').mouseleave(function(){
			$('#box_details_use').slideUp(250);
			$(this).css('box-shadow', 'none');
		});
		
		$('#box_descubra').mouseover(function(){
			$(this).css('box-shadow', '3px 3px 30px rgb(150,150,150)');
			$('#box_details_descubra').slideDown(250);
		});
		
		$('#box_descubra').mouseleave(function(){
			$('#box_details_descubra').slideUp(250);
			$(this).css('box-shadow', 'none');
		});
		
		$('#box_compartilhe').mouseover(function(){
			$(this).css('box-shadow', '3px 3px 30px rgb(150,150,150)');
			$('#box_details_compartilhe').slideDown(250);
		});
		
		$('#box_compartilhe').mouseleave(function(){
			$('#box_details_compartilhe').slideUp(250);
			$(this).css('box-shadow', 'none');
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
		margin:0px 0 30px 0 !important;
		font-family:'Open Sans Condensed Light';
		font-weight:100 !important;
		text-align:center;
	}
	
	#box_use {
		cursor:pointer;
		text-align:center;
		width:220px;
		min-height:250px;
		background-image:url(<?php echo URL_IMG_WEBSITE ?>/button_use.png);
		background-position:top center;
		position:absolute;
	}
	
	#box_descubra {
		cursor:pointer;
		text-align:center;
		margin-left:220px;
		width:220px;
		min-height:250px;
		background-image:url(<?php echo URL_IMG_WEBSITE ?>/button_descubra.png);
		background-position:top center;
		position:absolute;
	}
	
	#box_compartilhe {
		cursor:pointer;
		text-align:center;
		margin-left:440px;
		width:220px;
		min-height:250px;
		background-image:url(<?php echo URL_IMG_WEBSITE ?>/button_compartilhe.png);
		background-position:top center;
		position:absolute;
	}
	
	#box_details_use, #box_details_descubra, #box_details_compartilhe {
		display:none;
		color:#000;
		margin:0px 15px 15px 15px;
	}
</style>
<div>
	<h1 class="title-welcome">Construir aplicações web nunca foi tão <strong>fácil</strong>.</h1>
	<div class="inline top" style="margin-right:25px;width:220px;">
		<p><h6>ACME Engine é um sistema básico voltado para a construção de novas aplicações web escritas em PHP.</h6></p>
		<p><h6>A partir do ACME Engine é possível construir uma nova aplicação em um tempo muito menor.</h6></p>
		<p><h6>Leia a documentação e <a href="<?php echo URL_ROOT ?>/documentation">saiba mais!</a></h6></p>
	</div>
	<div id="box_use" class="inline top">
		<div style="margin-top:175px;"><h2>USE</h2></div>
		<div id="box_details_use" class="font_11">Faça download da versão mais recente do ACME Engine ou procure por atualizações.</div>
	</div>
	<div id="box_descubra" class="inline top">
		<div style="margin-top:175px;"><h2>DESCUBRA</h2></div>
		<div id="box_details_descubra" class="font_11">Leia a documentação e siga os tutoriais, saiba como construir uma aplicação melhor.</div>
	</div>
	<div id="box_compartilhe" class="inline top">
		<div style="margin-top:185px;"><h3>COMPARTILHE</h3></div>
		<div id="box_details_compartilhe" class="font_11" style="display:none;color:#000;margin:0px 15px 15px 15px">Ajude a melhorar o ACME Engine. Seja um colaborador!</div>
	</div>
	
	<div style="width:450px;margin-top:70px;">
		<h5>Como Utilizar ?</h5>
		<hr />
		<p>1.Analise a problemática do software que deverá ser construído</p>
		<p>2. Verifique se a modelagem do ACME Engine se enquadra na solução do problema</p>
		<p>3. Instale o ACME Engine em seu servidor web</p>
		<p>4. Desenvolva sua nova aplicação a partir do ACME Engine, consultando a documentação de uso e funcionalidades da API interna</p>
	</div>
</div>