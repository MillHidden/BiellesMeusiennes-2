<?php
header('Content-type: text/html; charset=utf-8');
?>
<page style="font-size:50px; color: rgb(85, 114, 182);">
	<div class="fiche" style="line-height:100px;">
		<div style=" text-align:center;">
			<img style="width:120px; height:100px;" src="<?=$_SERVER['DOCUMENT_ROOT'].'/assets/img/logo_retro_meuse.png';?>"/>

		</div>
		<div class="marque">
			<p><b>Marque :</b>   <?= $datas->marque;?>   </p>
		</div>

		<div class="modele">
			<p><b>Modèle :</b>  <?= $datas->model;?>    </p>
		</div>

		<div class="type">
			<p><b>Type :</b>  <?= $datas->type;?>    </p>
		</div>

		<div class="annee">
			<p><b>Année :</b>   <?= $datas->date_circu;?>   </p>
		</div>

		<div class="club">
			<p><b>Club :</b>   <?= $datas->club;?>   </p>
		</div>

	</div>
</page>
