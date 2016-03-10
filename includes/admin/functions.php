<?php
function erreur($err='')
{
   $mess=($err!='')? $err:'Une erreur inconnue s\'est produite';
   exit('<p>'.$mess.'</p>
   <p>Cliquez <a href="../admin/login.php">ici</a> pour revenir à la page de connexion</p></div></body></html>');
}
?>
