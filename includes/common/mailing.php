<?php

require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
include_once($_SERVER['DOCUMENT_ROOT'].'/includes/common/functions.php');

use Core\Mailer\Mail;
use Core\Configure\Config;
use Core\Export\DataExporter;

function envoi_mail ($action, $exposant_id) {

	switch ($action) {
		case "inscription" :

			$participant = Config::QueryBuilder()->findOne("exposants")->where(['id' => $exposant_id])->execute();
			$mail =	$participant->email;		
			
			//=====Définition du sujet.			
			$subject = "RétroMeus'auto 2016 - préinscription";
			//=========
			
			$content_text = "Bonjour " .$participant->firstname. " ".$participant->lastname.", \r\n nous avons bien pris en compte votre demande concernant le véhicule suivant : \r\n Marque : ".$participant->marque." \r\n Modèle : ".$participant->model."\r\n Immatriculation : ".$participant->immat."\r\n Date de mise en circulation : ".$participant->date_circu."\r\n Vous recevrez dans les prochains jours un email confirmant ou refusant votre inscription \r\n Cordialement. \r\n Pour plus d'infos : www.biellesmeusiennes.com \r\n L'équipe des Bielles Meusiennes.";
			$content_html = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/includes/App/Views/mails/base_mail_inscription.html');
			$content_html = mail_all_update($content_html, [
				["%user_name%", $participant->firstname . " " . $participant->lastname],				
				["%marque%", $participant->marque],
				["%model%", $participant->model],
				["%immat%", $participant->immat],
				["%date_circu%", $participant->date_circu]
				]);
			$pjs= [];
			break;

		case "nouvel_inscrit" :
			$participant = Config::QueryBuilder()->findOne("exposants")->where(['id' => $exposant_id])->execute();
			$mail = "inscription.rma@gmail.com";
			
			//=====Définition du sujet.			
			$subject = "RétroMeus'auto 2016 - Nouvel inscrit";
			//=========
			//=====Déclaration des messages au format texte et au format HTML.
			$content_text = "Un nouvel utilisateur s'est inscrit. Veuillez procéder à sa validation sur le site administratif";
			$content_html = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/includes/App/Views/mails/base_mail_inscription_admin.html');
			$content_html = mail_all_update($content_html, [
				["%marque%", $participant->marque],
				["%model%", $participant->model],
				["%immat%", $participant->immat],
				["%date_circu%", $participant->date_circu],
				["%user_name%", "Admin"],
				["%firstname%", $participant->firstname],
				["%lastname%", $participant->lastname]
				]);
			//==========			
			$pjs= [];
			
			break;

		case "validation" :

			$participant = Config::QueryBuilder()->findOne("exposants")->where(['id' => $exposant_id])->execute();
			$mail =	$participant->email;

			$pdf = new DataExporter('output/fiche_auto', 'pdf');
			$pdf->setPdfAttributes('p', 'A4', 'fr', 'fiche_auto');
			$resultPdf = $pdf->save($participant);
			$pdf2 = new DataExporter('output/bulletin', 'pdf');
			$pdf2->setPdfAttributes('p', 'A4', 'fr', 'bulletin');
			$resultPdf2 = $pdf2->save($participant);
			$pjs = [
			    [
			        'path' => $resultPdf,
			        'name' => $pdf->filename.'.pdf'
			    ],
				[
					'path' => $resultPdf2,
					'name' => $pdf2->filename.'.pdf'
				]
			];

			//=====Définition du sujet.			
			$subject = "Validation de votre inscription à l'évênement des Bielles Meusiennes";
			//=========
			
			$content_text = "Bonjour " .$participant->firstname. " ".$participant->lastname.", \r\n Félicitation ! \r\n Le véhicule suivant est inscrit sur le site des Bielles Meusiennes: \r\n Marque : ".$participant->marque." \r\n Modèle : ".$participant->model."\r\n Immatriculation : ".$participant->immat."\r\n Date de mise en circulation : ".$participant->date_circu." \r\n Vous trouverez joint à ce mail deux documents : \r\n   - le premier est à afficher sur le pare-brise du véhicule lors de la manifestation. \r\n   - le second est à donner aux bénévoles présents à l'entrée du site. \r\n Enfin, pour retirer une plaque rallye, veuillez vous présenter à l'espace spécifique sur le site muni de cet email. \r\n Au plaisir de vous retrouver lors de RetroMeuse' Auto 2016 ! \r\n Pour plus d'infos : www.biellesmeusiennes.com \r\n L'équipe des Bielles Meusiennes.";
			$content_html = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/includes/App/Views/mails/base_mail_validation.html');
			$content_html = mail_all_update($content_html, [
				["%user_name%", $participant->firstname . " " . $participant->lastname],				
				["%marque%", $participant->marque],
				["%model%", $participant->model],
				["%immat%", $participant->immat],
				["%date_circu%", $participant->date_circu]
				]);
			//==========	
			break;

		case "refus" :
			$participant = Config::QueryBuilder()->findOne("exposants")->where(['id' => $exposant_id])->execute();
			$mail =	$participant->email;
			
			//=====Définition du sujet.			
			$subject = "RétroMeus'auto 2016 - refus d'inscription d'un véhicule";
			//=========
			$content_text = "Bonjour " .$participant->firstname. " ".$participant->lastname.", \r\n Vous nous avez soumis une demande d'inscription du véhicule suivant : \r\n Marque : ".$participant->marque." \r\n Modèle : ".$participant->model."\r\n Immatriculation : ".$participant->immat."\r\n Date de mise en circulation : ".$participant->date_circu."\r\n Malheureusement, celui-ci ne corespond pas aux critères précisés dans le règlement intérieur de la manifestation. \r\n Par conséquent, nous sommes au regret de vous informer que celui-ci ne pourra être inscrit pour le RetroMeus'Auto 2016. \r\n Vous avez toutefois la possibilité d'inscrire un autre véhicule lors de la manifestation, ou de nous rejoindre en tant que visiteur. \r\n Pour plus d'infos : www.biellesmeusiennes.com \r\n L'équipe des Bielles Meusiennes.";
			$content_html = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/includes/App/Views/mails/base_mail_refus.html');
			$content_html = mail_all_update($content_html, [
				["%user_name%", $participant->firstname . " " . $participant->lastname],				
				["%marque%", $participant->marque],
				["%model%", $participant->model],
				["%immat%", $participant->immat],
				["%date_circu%", $participant->date_circu]
				]);
			$pjs= [];
			
			break;
		default : 
			throw new Exception ("error");
			break;
	}	
	
	$receiver_mail = $mail;
	$receiver_name = $participant->firstname . " " . $participant->lastname;	

	$mail = new Mail();
	try {
		$mail->send($receiver_mail, $receiver_name, $subject, $content_text, $content_html, $pjs); //pjs est optionnel

	} catch (Exception $e) {
		throw $e;
	}
}

?>
