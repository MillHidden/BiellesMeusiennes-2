<?php


/* Création de la fonction pour la connexion à la base de données*/
function connexionbdd() {
	try	{
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO('mysql:host=xxxxx;dbname=xxxx', 'biellesmhjinscri', 'xxxx', $pdo_options);
		$bdd->query("SET NAMES 'utf8'");
		return ($bdd);
	}
	catch (Exception $e) 
	{		
    	throw new Exception ('Erreur : ' . $e->getMessage()); 
	}
}
?>
