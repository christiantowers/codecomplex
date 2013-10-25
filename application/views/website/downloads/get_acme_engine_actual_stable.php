<?php echo load_html_component('feedback_box', array('width:285px;float:right;margin:20px 100px 50px 0')); ?>
<script type="text/javascript">
	$(document).ready(function(){
		// Abre download ;)
		download_file('<?php echo URL_ROOT ?>/downloads/acme-engine/actual-stable/download/<?php echo $hash ?>');
	});
</script>
<h2>Inicando o Download...</h2>
<div class="inline top" style="width:500px;">
	<p>O download do ACME Engine deve iniciar nos próximos segundos...</p>
	<p><div class="inline top">Caso você tenha problemas com o download do arquivo, clique</div><h6 class="inline top" style="margin:-4px 0 0 5px;"><a href="<?php echo URL_ROOT?>/downloads/acme-engine/actual-stable/get/<?php echo $hash; ?>">aqui</a></h6>.</p>
	
	<br />
	<br />
	<h5>Tudo certo com o Download. E agora ?</h5>
	<hr style="margin-bottom: 10px;" />
	<h6>&bull;&nbsp;<a href="<?php echo URL_ROOT ?>/documentation">Leia a Documentação desde o Início</a></h6>
	<h6>&bull;&nbsp;<a href="<?php echo URL_ROOT ?>/documentation/technical">Consulte a Documentação Técnica</a></h6>
	<h6>&bull;&nbsp;<a href="<?php echo URL_ROOT ?>/documentation/api">Consulte a API de desenvolvimento</a></h6>
	<h6>&bull;&nbsp;<a href="<?php echo URL_ROOT ?>/documentation/tutorials">Pratique exemplos através de tutoriais</a></h6>
</div>