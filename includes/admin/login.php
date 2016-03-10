<?php

require $_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php";
use Core\Configure\Config;

session_start();

//Attribution des variables de session

$id=(isset($_SESSION['id']))?(int) $_SESSION['id']:0;
$username=(isset($_SESSION['username']))?$_SESSION['username']:'';


if (!isset($_POST['username'])) //On est dans la page de formulaire
{
	include($_SERVER['DOCUMENT_ROOT'].'/includes/common/connexion.php');
	//On inclue les 2 pages restantes
	include($_SERVER['DOCUMENT_ROOT'].'/includes/admin/functions.php');
	include($_SERVER['DOCUMENT_ROOT'].'/includes/admin/constants.php');
	if (isset($_GET['message'])) {
		if ($_GET['message'] == "errorlogin") {
			echo '<div class="alert alert-danger"><p>Une erreur s\'est produite pendant votre identification.<br /> Le mot de passe ou l\'identifiant entr� n\'est pas correct.</p></div>';
		}
		else if ($_GET['message'] =="errorchampmanquant") {
			echo '<div class="alert alert-danger"><p>une erreur s\'est produite pendant votre identification. Vous devez remplir tous les champs</p></div>';
		}
		else if ($_GET['message'] =="errortoken") {
			echo '<div class="alert alert-danger"><p>Faille csrf</p></div>';
			echo  (isset($_SESSION['_token'])) ? 'on veut ce token : '.$_SESSION['_token'] : 'Votre premier passage';
			echo 'on a envoy� ce token : ' . $_GET['token'];
		}
	}

	echo '<form method="post" action="../includes/admin/login.php">
	<fieldset>
	<legend>Connexion</legend>
	<p>
	<label for="username">Identifiant :</label><input name="username" type="text" id="username" /><br />
	<label for="password">Mot de Passe :</label><input type="password" name="password" id="password" />
	</p>
	</fieldset>
	<p><input type="submit" value="Connexion" /></p></form>
	<a href="../admin/register_admin.php">Creer un compte administrateur</a>
	 
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
        header('Location: http://biellesmeusiennes.fr/admin/index.php?message=errorchampmanquant');
    }
    else //On check le mot de passe
    {


		$bdd = connexionbdd();

        $query = $bdd->prepare('SELECT password, id, email, username
        FROM users WHERE username = :username');
        $query->bindValue(':username',$_POST['username'], PDO::PARAM_STR);
        $query->execute();
        $data=$query->fetch();
		/*$bdd = Config::QueryBuilder()->findAll("exposants")->execute();
		var_dump($bdd);
		die();*/



		if ($data['password'] == md5($_POST['password'])) // Acces OK !
	{
	    $_SESSION['username'] = $data['username'];
	    $_SESSION['id'] = $data['id'];
	    $token = generer_token();
	    header('Location: http://biellesmeusiennes.fr/admin/liste.php?token='.$token);
	}
	else // Acces pas OK !
	{
	    header('Location: http://biellesmeusiennes.fr/admin/index.php?message=errorlogin');
	}
    $query->CloseCursor();
    }
    echo $message.'</div></body></html>';

}
?>

