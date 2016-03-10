
<?php

include('./includes/common/connexion.php');

//fonction qui enregistre l'inscription dans la bdd  /!\ IL MANQUE DES CHAMPS A AJOUTER PAR RAPPORT AU FORMULAIRE /!\
function ajouter_inscription($donneesExposant) {


	$bdd = connexionbdd();


	/* On crée le nouveau propriétaire dans la bdd*/
	$requete = $bdd->prepare(
		'INSERT INTO exposants (firstname, lastname, email, city, cp, country, newsletter, club, marque, model, type, motorisation, immat, date_circu, infos, concours1, concours2, concours3, valid) 
		VALUES (:firstname, :lastname, :email, :city, :cp, :country, :newsletter, :club, :marque, :model, :type, :motorisation, :immat, :date_circu, :infos, :concours1, :concours2, :concours3, 0)');
	try {
		$requete->execute($donneesExposant);
	}
	catch (Exception $e) {			
		throw $e;
	}

	/* On retourne l'id de l'exposant nouvellement créé dans la table*/
	return $bdd->lastInsertId();
	
}

?>