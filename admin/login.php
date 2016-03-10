
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="language" content="fr" />
	<link rel="stylesheet" href="../../assets/css/style.css" type="text/css" media="screen">
	<link rel="stylesheet" href="../../assets/css/bootstrap.min.css" type="text/css">
</head>
<?php

session_start();

//Attribution des variables de session

$id=(isset($_SESSION['id']))?(int) $_SESSION['id']:0;
$username=(isset($_SESSION['username']))?$_SESSION['username']:'';


if (!isset($_POST['username'])) //On est dans la page de formulaire
{
	include('../includes/common/connexion.php');
	//On inclue les 2 pages restantes
	include('../includes/common/functions.php');
	include('../includes/admin/constants.php');

		echo '<div class="form-login">';
		echo '<div class="container-fluid">';


	if (isset($_GET['message'])) {
		if ($_GET['message'] == "errorlogin") {
			echo '<div class="alert alert-danger"><p>Une erreur s\'est produite pendant votre identification.<br /> Le mot de passe ou l\'identifiant entré n\'est pas correct.</p></div>';
		}
		else if ($_GET['message'] =="errorchampmanquant") {
			echo '<div class="alert alert-danger"><p>une erreur s\'est produite pendant votre identification. Vous devez remplir tous les champs</p></div>';
		}
		else if ($_GET['message'] =="errortoken") {
			echo '<div class="alert alert-danger"><p>Faille csrf</p></div>';
		}
	}

	echo '<form method="post" action="login.php">

	<fieldset>
	<legend>Connexion</legend>
	<br>
	<p>
	<label for="username">Identifiant :</label><input class="form-control input-sm" id="inputsm" name="username" type="text" id="username" /><br />
	<label for="password">Mot de Passe :</label><input class="form-control input-sm" id="inputsm" type="password" name="password" id="password" />
	</p>
	</fieldset>
	<p><input  class="btn btn-primary" type="submit" value="Connexion" /></p></form>

	</div>

	</body>
	</html>';
}

else
{
	include($_SERVER['DOCUMENT_ROOT'].'/includes/common/connexion.php');
	//On inclue les 2 pages restantes
	include($_SERVER['DOCUMENT_ROOT'].'/includes/admin/functions.php');
	include($_SERVER['DOCUMENT_ROOT'].'/includes/admin/constants.php');
	include($_SERVER['DOCUMENT_ROOT'].'/includes/common/verif_security.php');

    $message='';
    if (empty($_POST['username']) || empty($_POST['password']) ) //Oublie d'un champ
    {
        header('Location: http://biellesMeusiennes.fr/admin/index.php?message=errorchampmanquant');
    }
    else //On check le mot de passe
    {
		$bdd = connexionbdd();
        $query = $bdd->prepare('SELECT password, id, email, username
        FROM users WHERE username = :username');
        $query->bindValue(':username',$_POST['username'], PDO::PARAM_STR);
        $query->execute();
        $data=$query->fetch();
	if ($data['password'] == md5($_POST['password'])) // Acces OK !
	{
	    $_SESSION['username'] = $data['username'];
	    $_SESSION['id'] = $data['id'];
	    $token = generer_token();
	    header('Location: http://biellesMeusiennes.fr/admin/liste.php?token='.$token);
	}
	else // Acces pas OK !
	{
	    header('Location: http://biellesMeusiennes.fr/admin/index.php?message=errorlogin');
	}
    $query->CloseCursor();
    }
    echo $message.'</div></div>
	</div></body></html>';

}
?>
