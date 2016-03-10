<div id='cssmenu' class="sticky">
    <ul>
       <li><a href="http://<?= $_SERVER['SERVER_NAME']."/admin/liste.php?token=".$_GET['token'];?>">Liste</a></li>
       <li><a href="http://<?= $_SERVER['SERVER_NAME']."/admin/reset.php?token=".$_GET['token'];?>">Reset BDD</a></li>
       <li><a href="http://<?= $_SERVER['SERVER_NAME']."/admin/register_admin.php?token=".$_GET['token'];?>">Creer un compte</a></li>
       <li><a href="http://<?= $_SERVER['SERVER_NAME']."/includes/admin/logout.php?token=".$_GET['token'];?>">Deconnexion</a></li>
    </ul>
</div>
