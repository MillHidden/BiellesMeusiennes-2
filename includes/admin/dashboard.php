<?php 
	if(!isset($_SESSION)) { 
		session_start();
	}
	if (isset($_SESSION['id']) AND isset($_SESSION['username'])) {
		include_once($_SERVER['DOCUMENT_ROOT'].'includes/common/verif_security.php');
		$token = generer_token();	 
?>

connecté !
<form action="logout.php" method="POST">
	<input type="submit" type="button" class="btn btn-info btn-lg" value="Se déconnecter" />
</form>

<form action="methode_protegee.php" method="GET">
	<input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
	<input type="submit" type="button" class="btn btn-info btn-lg" value="attention" />	
</form>
<?php 
} else {	
	header('Location: http://biellesMeusiennes.fr/admin/index.php?message=errorlogin');
}
?>