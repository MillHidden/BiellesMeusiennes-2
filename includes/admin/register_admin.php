<?php

//Cette fonction doit être appelée avant tout code html
session_start();

include($_SERVER['DOCUMENT_ROOT'].'/includes/common/connexion.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<div xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="language" content="fr" />
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
</head>

<?php

include('../includes/common/header.php'); //contient le header.

//Attribution des variables de session

$id=(isset($_SESSION['id']))?(int) $_SESSION['id']:0;
$username=(isset($_SESSION['username']))?$_SESSION['username']:'';

//On inclue les 2 pages restantes
include("../includes/admin/functions.php");
include("../includes/admin/constants.php");
?>

<?php
if (empty($_POST['username'])) // Si on la variable est vide, on peut considérer qu'on est sur la page de formulaire
{
	echo '<div class="form-register">';
	echo '<div class="panel panel-default">';
	echo '<h3>Enregistrement d\'un nouvel administrateur</h3>';
	echo '<div class="container-fluid">';
	echo '<form method="post" action="register_admin.php" enctype="multipart/form-data">

	<fieldset><legend>Identifiants</legend>
	<label for="username">* Identifiant :</label>  <input class="form-control input-sm" id="inputsm" name="username" type="text" id="username" /> <i>(L\' identifiant doit contenir entre 3 et 15 caractères)</i><br />
	<label for="password">* Mot de Passe :</label><input class="form-control input-sm" id="inputsm" type="password" name="password" id="password" /><br />
	<label for="confirm">* Confirmer le mot de passe :</label><input class="form-control input-sm" id="inputsm" type="password" name="confirm" id="confirm" />
	</fieldset>

	</br>

	<fieldset><legend>Contacts</legend>
	<label for="email">* Votre adresse Mail :</label><input class="form-control input-sm" id="inputsm" type="text" name="email" id="email" /><br />
	</fieldset>

	<p><i>* champs obligatoires</i></p>
	<p><input type="submit" class="btn btn-primary" value="S\'enregistrer" /> <input type="reset" class=" btn btn-warning" value="Effacer" /></p></p></form>
	</div></div></div></div>';


} //Fin de la partie formulaire

else //On est dans le cas traitement
{
    $username_erreur1 = NULL;
    $username_erreur2 = NULL;
    $password_erreur = NULL;
    $email_erreur1 = NULL;
    $email_erreur2 = NULL;

?>
<?php

    //On récupère les variables
    $i = 0;
    $temps = time();
    $username=$_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $confirm = md5($_POST['confirm']);

    //Vérification de l'identifiant
	$bdd = connexionbdd();
    $query=$bdd->prepare('SELECT COUNT(*) AS nbr FROM users WHERE username =:username');
    $query->bindValue(':username',$username, PDO::PARAM_STR);
    $query->execute();
    $username_free=($query->fetchColumn()==0)?1:0;
    $query->CloseCursor();
    if(!$username_free)
    {
        $username_erreur1 = "Votre Identifiant est déjà utilisé";
        $i++;
    }

    if (strlen($username) < 3 || strlen($username) > 15)
    {
        $username_erreur2 = "Votre identifiant est soit trop grand, soit trop petit";
        $i++;
    }

    //Vérification du mdp
    if ($password != $confirm || empty($confirm) || empty($password))
    {
        $password_erreur = "Votre mot de passe et votre confirmation diffèrent, ou sont vides";
        $i++;
    }
	  //Vérification de l'adresse email

    //Il faut que l'adresse email n'ait jamais été utilisée
    $query=$bdd->prepare('SELECT COUNT(*) AS nbr FROM users WHERE email =:email');
    $query->bindValue(':email',$email, PDO::PARAM_STR);
    $query->execute();
    $email_free=($query->fetchColumn()==0)?1:0;
    $query->CloseCursor();

    if(!$email_free)
    {
        $email_erreur1 = "Votre adresse email est déjà utilisée ";
        $i++;
    }
    //On vérifie la forme maintenant
    if (!preg_match("#^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-z]{2,4}$#", $email) || empty($email))
    {
        $email_erreur2 = "Votre adresse E-Mail n'a pas un format valide";
        $i++;
    }

?>
<?php
   if ($i==0)
   {
        echo '<div class="form-register-ok">';
        echo '<div class="panel panel-default">';
        echo'<h3>Enregistrement terminée</h3>';
        echo '<div class="container-fluid">';
        echo'<div class="alert alert-success">';
        echo'<p> '.stripslashes(htmlspecialchars($_POST['username'])).' vous êtes enregistré</p>';
        echo'</div>';
        echo '<p>Cliquez <a href="../../admin/index.php">ici</a> pour revenir à la page d accueil</p>';
        echo'</div></div></div>';

        //La ligne suivante sera commentée plus bas


        $query=$bdd->prepare('INSERT INTO users (username, password, email)
        VALUES (:username, :password, :email)');
    $query->bindValue(':username', $username, PDO::PARAM_STR);
    $query->bindValue(':password', $password, PDO::PARAM_INT);
    $query->bindValue(':email', $email, PDO::PARAM_STR);

        $query->execute();
    //Et on définit les variables de sessions
        $_SESSION['username'] = $username;
        $_SESSION['id'] = $bdd->lastInsertId(); ;

        $query->CloseCursor();
    }
    else
    {

        echo '<div class="form-register-error">';
        echo '<div class="panel panel-default">';
        echo'<h3>Enregistrement interrompue</h3>';
        echo '<div class="container-fluid">';
        echo'<div class="alert alert-danger">';
        echo'<p>Une ou plusieurs erreurs se sont produites pendant l\' incription</p>';
        echo'<p><b>'.$i.' erreur(s)</b></p>';
        echo'<p>'.$username_erreur1.'</p>';
        echo'<p>'.$username_erreur2.'</p>';
        echo'<p>'.$password_erreur.'</p>';
        echo'<p>'.$email_erreur1.'</p>';
        echo'<p>'.$email_erreur2.'</p>';
        echo'</div>';


        echo'<p>Cliquez <a href="./register_admin.php">ici</a> pour recommencer</p>';
        echo'</div></div></div>';
    }
}
?>
</div>
</html>
