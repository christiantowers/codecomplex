<?php echo load_js_file('jquery.validationengine.js'); ?>
<?php echo load_js_file('jquery.validationengine.pt_BR.js'); ?>
<script type="text/javascript" language="javascript">
	$(document).ready(function(){
		// Validação e máscaras
		enable_form_validations();
 	});
</script>
<div class="inline top" style="width: 450px;margin-right:50px;">
	<h2>Downloads</h2>
	<p>Baixe a &uacute;ltima vers&atilde;o do ACME Engine e comece a construir sua nova aplica&ccedil;&atilde;o agora mesmo!</p>
	<p>O ACME Engine &eacute; gratu&iacute;to, e voc&ecirc; s&oacute; precisa completar um breve cadastro para utiliz&aacute;-lo.</p>
	<p>Para saber mais sobre a licen&ccedil;a de utiliza&ccedil;&atilde;o, clique <a href="http://www.acmeengine.org" target="_blank">aqui</a>.</p>
	
	<br />
	<br />
	<h5>Mais Downloads</h5>
	<hr style="margin-bottom: 10px;" />
	<h6>&bull;&nbsp;<a href="http://www.acmeengine.org/downloads/packages-updates">Atualiza&ccedil;&otilde;es</a></h6>
	<h6>&bull;&nbsp;<a href="http://www.acmeengine.org/downloads/old-versions">Vers&otilde;es anteriores do ACME Engine</a></h6>
	<h6>&bull;&nbsp;<a href="http://www.acmeengine.org/downloads/change-log">Hist&oacute;rico de Vers&otilde;es</a></h6>
	<h6></h6>
</div>
<div class="inline top" style="width: 400px;margin-top:30px">
  	<h6>Você pode utilizar o ACME Engine de graça. Tudo o que pedimos é que você complete o cadastro abaixo. É rápido, prometemos.</h6>
  	<form id="form_default" name="form_default" style="margin-top: 20px;" action="<?php echo URL_ROOT ?>/downloads/register" method="post">
		<div>
			<h6>Seu Nome*</h6>
			<div class="form_input"><input type="text" name="name" id="name" class="validate[required]" style="width: 200px;" /></div>
		</div>
      	<br />
		<div>
			<h6>Email*</h6>
			<div class="form_input"><input type="text" name="email" id="email" class="validate[required,custom[email]]" style="width: 200px;" /></div>
		</div>
      	<br />
		<div>
			<h6>Empresa</h6>
			<div class="form_input"><input type="text" name="company" id="company" style="width: 200px;" /></div>
		</div>
      	<br />
		<div>
			<h6>Função</h6>
			<div class="form_input"><input type="text" name="function" id="function" style="width: 200px;" /></div>
		</div>
      	<div style="margin:20px 0">
          	<div class="inline top" style="margin:2px 3px 0 0;"><input type="checkbox" name="newsletter" id="newsletter" value="Y" /></div>
        	<div style="width:350px;" class="font_11 inline top"><label for="newsletter">Desejo receber novidades sobre o desenvolvimento do ACME Engine, notícias e afins.</label></div>
		</div>
      	<br />
      	<div class="center">
        	<button style="width: 300px; font-size: 48px;padding:20px 100px">
              	<div><img src="<?php echo URL_IMG_WEBSITE ?>/icon_box_down.png" style="float:left;margin:20px 10px 0 -10px"/>Download</div>
              	<div class="font_11" style="margin-top:-5px">Última versão estável</div>
         	</button>
      	</div>
	</form>
</div>
