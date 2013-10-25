<style type="text/css">
	.package-list {
		padding:10px 10px 10px 10px;
	}
	
	.odd_list {
		border-top:2px solid #ddd;
		border-bottom:2px solid #ddd;
		background-color:#f5f5f5;
	}
</style>
<?php echo load_html_component('feedback_box', array('width:285px;float:right;margin:40px 60px 50px 0')); ?>
<h2>Pacotes de Atualização</h2>
<div class="inline top" style="width:650px;">
	<p>Localize nesta página os pacotes de atualização disponíveis para o ACME Engine. Cada pacote de atualização pode alterar componentes internos ou corrigir possíveis bugs, além de modificar a versão atual do ACME Engine. Se é a primeira vez que você está fazendo download do ACME Engine não se preocupe, a última versão do ACME já estará devidamente atualizada.</p>
	<p>Encontrou algum problema durante a utilização do ACME? Reporte-nos para que a correção seja inclusa na próxima atualização. Utilize a caixa ao lado para isso.</p>
	
	<h5 style="margin-top:50px">Pacotes Disponíveis</h5>
	<hr style="margin-bottom: 10px;" />
	<p>Cada pacote deve ser instalado da menor versão até a maior. Utilize o módulo de Atualizações disponível em sua instância do ACME Engine para instalar cada atualização. E mais: cada pacote de maior versão é dependente das suas versões anteriores.</p>
	<?php if(count($packages) > 0 ){?>
	<?php 
	$i = 0;
	foreach($packages as $version => $folder) {
	$class = ($i % 2 == 0) ? 'odd_list' : '';
	// Cada pacote de atualização possui sua versão como nome
	// Entretando vamos coletar a versão do arquivo change_log.ini
	$package = parse_ini_file('application/uploads/website_files/packages_updates/' . $version . '/change_log.ini');
	
	// Espera-se que em $package existam as chaves file, version e description
	$file = get_value($package, 'file');
	$version = get_value($package, 'version');
	$description = get_value($package, 'description');
	?>
	<div class="package-list <?php echo $class ?>">
		<h5 style="color:steelblue">Versão <?php echo $version ?></h5>
		<p><?php echo $description; ?></p>
		<p class="font_11 bold">Download: <a href="javascript:void(0);" onclick="download_file('<?php echo URL_ROOT ?>/downloads/packages-updates/get/<?php echo $version ?>')">arquivo <?php echo $file?></a></p>
	</div>
	<?php $i++; } ?>
	<?php } else { echo message('info', '', 'Nenhum pacote de atualização disponível até o momento. Fique de olho nas novidades do ACME Engine cadastrando-se na nossa newsletter, no rodapé do site.'); } ?>	
	
	<h5 style="margin-top:50px">Mais Downloads</h5>
	<hr style="margin-bottom: 10px;" />
  	<h6>&bull;&nbsp;<a href="<?php echo URL_ROOT?>/downloads">Baixe o ACME Engine</a></h6>
</div>