<?php
include($_SERVER['DOCUMENT_ROOT'].'/includes/common/verif_security.php');

/*try {
    verif_origin_user();
    $token =  $_POST['token'];
} catch (Exception $e) {
    header('Location: http://localhost/BiellesMeusiennes/BiellesMeusiennes/admin/index.php?message=errortoken&token=' . $_POST['token'] );
    die();
}*/

require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
use Core\Configure\Config;
use Core\Export\DataExporter;
include_once($_SERVER['DOCUMENT_ROOT'].'/includes/common/mailing.php');


if ($_POST && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'validate':            
            echo  validate( $_POST['type'], $_POST['id']);
            break;
        case 'exportCsv':
            export($_POST['model']);
            break;
    }
} else {
    header('Location: ../../404.html');
    exit();
}

function validate ($type, $id) {
    $params1 = false;
    $params2 = false;
    switch ($type) {
        case "valider":
            $message = ["message" =>  "Inscription valide"];
            $params1 = ['valid' => 1];
            $params2 = "validation";          
            break;
        case "refuser":
            $message = ["message" =>  "Inscription refuse"];
            $params1 = ['valid' => 2];
            $params2 = "refus";            
            break;
        case "supprimer":
            $message = ["message" =>  "Inscription supprimee"];
            break;
        case "toutsupprimer":
            $params2 = "suppression";
            break;
    }
    
    if ( $params1 ) {        
        $upd = Config::QueryBuilder()->update('exposants', ['id' => $id], $params1)->execute();
        envoi_mail ($params2, $id);
    } elseif ($params2) {
        $upd = Config::QueryBuilder()->deleteAll('exposants')->execute(); 
    } else {
        $upd = Config::QueryBuilder()->delete('exposants')->where(['id' => $id])->execute(); 
    }
    if ($upd) {
        if (!$message) {
            $message = ["message" =>  "Une erreur est survenue merci de contacter un administrateur"];
        }
    }
    $response = json_encode($message);
    return  $response;
}

function export($model)
{
    
    $exposants = Config::QueryBuilder()->findAll('Exposants')->execute();

    $csv = new DataExporter('sortie', 'csv');

    $titles = [
        "id"=> "id",
        "firstname"=> "Prenom",
        "lastname"=> "Nom",
        "email"=> "Email",
        "city"=> "Ville",
        "cp"=> "CP",
        "country"=> "Pays",
        "newsletter"=> "Newsletter",
        "club"=> "Club",
        "marque" => "Marque",
        "model" => "Model",
        "type" => "Type",
        "motorisation" => "Motorisation",
        "immat" => "Immatriculation",
        "date_circu" => "Date 1ere circulation",
        "infos" => "Infos comp.",
        "concours1" => "Claude Lorrenzini",
        "concours2" => "Coupe-Cabriolet",
        "concours3" => "Jeune -25 ans",
        "valid"=> "Valide"
    ];

    array_unshift($exposants, $titles);
    $csv->export($exposants);
    return true;
}



