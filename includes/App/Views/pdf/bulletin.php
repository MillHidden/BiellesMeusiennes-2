<?php
header('Content-type: text/html; charset=utf-8');
?>
<body style="width:90%; height:700px; margin:auto; border:1px rgb(0, 127, 255) solid; font-size:14px;">

 <img style=" width:20%; height:14%; margin-top:30px; margin-left:25px; margin-top:5px;" src="<?= $_SERVER['DOCUMENT_ROOT'].'/assets/img/logo_retro_meuse.png';?>"/>
	<h1 style="color: rgb(53, 122, 183);">Bulletin pour retirer votre plaque de rallye</h1>
	 <h2 style="color: rgb(53, 122, 183); ">A DEPOSER A L'ACCUEIL, MERCI</h2>



					 <div class="bulletin" style="margin-right:5%;margin-left:5%;">

						 <!--IDENTITE-->

		 <div class="identite">

			 <div class="prenom"style="float:left;width:45%;">
				 <p>Prénom: <?= $datas->firstname;?> </p>
			 </div>

			 <div class="nom" style="float:left;width:45%;">
				 <p>Nom: <?= $datas->lastname;?> </p>
			 </div>
		 </div>

						 <!--VILLE-->

		 <div class="ville">
			 <div class="postal" style="float:left;width:35%;">
				 <p>Code postal: <?= $datas->cp;?> </p>
			 </div>

			 <div class="ville"style="float:left;width:55%;">
				 <p>Ville: <?= $datas->city;?> </p>
			 </div>
		 </div>
						 <!--PAYS-->

			 <div class="pays" style="float:left;width:90%;">
				 <p>Pays: <?= $datas->country;?></p>
			 </div>


					 <!--MAIL-->
			 <div class="mail" style="float:left; width:90%;">
				 <p>Mail: <?= $datas->email;?>  </p>
			 </div>

					 <!--CLUB-->
			 <div class="club" style="float:left; width:90%;">
				 <p>Je suis membre du club:<?= $datas->club;?>  </p>
			 </div>
			 <div class="mailclub" style="float:left; width:90%;">
				 <p>adresse email du club: _____________@___________     </p>
			 </div>

					 <!--VEHICULE-->

			 <div class="marque" style="float:left; width:90%;">
				 <p style="float:left; width:15%;">Marque: <?= $datas->marque;?>   </p> <p style="float:left; width:15%;">Modèle: <?= $datas->model;?>   </p> <p style="float:left; width:15%;">Type: <?= $datas->type;?>   </p> <p style="float:left; width:15%;">Année: <?= $datas->date_circu;?>   </p>  <p style="float:left; width:15%;">Immatriculation: <?= $datas->immat;?>   </p>
			 </div>

				</div>

</body>
