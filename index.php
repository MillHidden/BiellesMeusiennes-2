<?php

if(!isset($_SESSION)) { 
	session_start();
}

require $_SERVER['DOCUMENT_ROOT'].'/includes/common/recaptcha.php';
include_once($_SERVER['DOCUMENT_ROOT'].'/includes/common/verif_security.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/includes/common/mailing.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/includes/public/functions.php');

try {
	verif_origin_user();
} catch (Exception $e) {
	header('Location: http://biellesmeusiennes.com/error/1');
	die();
}

/* vérification du captcha*/
$captcha = new Recaptcha ('x', 'x');
if (!empty($_POST)) {
	if ($captcha->isValid($_POST['g-recaptcha-response']) == false) {
		header('Location: http://biellesmeusiennes.com/error/2');
		die();
	}

	if (isset($_POST['firstname'])  
		&& isset($_POST['lastname']) 		
		&& isset($_POST['email']) 	
		&& isset($_POST['city']) 
		&& isset($_POST['cp']) 
		&& isset($_POST['country']) 
		&& isset($_POST['newsletter']) 		
		&& isset($_POST['marque']) 
		&& isset($_POST['model']) 
		&& isset($_POST['type']) 
		&& isset($_POST['motorisation']) 
		&& isset($_POST['immat'])
		&& isset($_POST['date_circu']) 	
		&& isset($_POST['infos'])		
		) {


		/* sécurisation faille XSS*/
		if (isset($_POST['newsletter'])) {
			$newsletter = intval($_POST['newsletter']);
		} else {
			$newsletter = 0;
		}
		if (isset($_POST['concours1'])) {
			$concours1 = 1;
		} else {
			$concours1 = 0;
		}
		if (isset($_POST['concours2'])) {
			$concours2 = 1;
		} else {
			$concours2 = 0;
		}
		if (isset($_POST['concours3'])) {
			$concours3 = 1;
		} else {
			$concours3 = 0;
		}

		$exposant = [
			'firstname' => htmlspecialchars($_POST['firstname']), 
			'lastname' => htmlspecialchars($_POST['lastname']),			
			'email' => htmlspecialchars($_POST['email']),
			'city' => htmlspecialchars($_POST['city']),
			'cp' => htmlspecialchars($_POST['cp']),			
			'country' => htmlspecialchars($_POST['country']),
			'newsletter' => $newsletter,
			'club' => htmlspecialchars($_POST['club']),		
			'marque' => htmlspecialchars($_POST['marque']),
			'model' => htmlspecialchars($_POST['model']),
			'type' => htmlspecialchars($_POST['type']),
			'motorisation' => htmlspecialchars($_POST['motorisation']),
			'immat' => htmlspecialchars($_POST['immat']),
			'date_circu' => htmlspecialchars($_POST['date_circu']),
			'infos' => htmlspecialchars($_POST['infos']),
			'concours1' => $concours1,
			'concours2' => $concours2,
			'concours3' => $concours3
			];

		
		try {
			/* inscription dans la bdd*/

			$exposant_id = ajouter_inscription($exposant);

			try {

				/* envoi emails*/
			envoi_mail("inscription", $exposant_id);
			envoi_mail("nouvel_inscrit", $exposant_id);			
				/*  */

			/* retour à la page des Bielles Meusiennes avec un message de réussite ou d'erreur */
			
			header('Location: http://biellesmeusiennes.com/success');
			} catch (Exception $e) {
				/* effacer les données dans la bdd*/

				/* */
				header('Location: http://biellesmeusiennes.com/error/3');
			}
			
		} catch (Exception $e) {		
			
			header('Location: http://biellesmeusiennes.com/error/4');
		}
	} else {		
		header('Location: http://biellesmeusiennes.com/error/5');
	}
}

?>