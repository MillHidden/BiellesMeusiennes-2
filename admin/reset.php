<?php
include('../includes/common/verif_security.php');
try {
    verif_origin_user();
} catch (Exception $e) {
    header('Location: http://biellesMeusiennes.fr/admin/index.php?message=errortoken' );
    die();
}

require "../vendor/autoload.php";
use Core\Configure\Config;


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Reset de la base de données</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css">
    <link type="text/css" rel="stylesheet" href="../assets/css/TopNavBarStyles.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
    <!--Style CSS perso-->
<style type="text/css">

    body {
        font-size: 1.7em;
    }

    .container-fluid {
        padding-top: 60px;
    }

</style>

<!--FIN du style CSS perso-->
<?php if ($message) :?>
    <div id="alert">
        <div class="alert <?= $class; ?>">
            <?= $message ;?>
        </div>
    </div>

<?php endif ;?>

    <?php include('../includes/common/topNavBar.php');?>

<div class="container-fluid">
    <div class="row text-center">
        <div class="col-xs-12">
            Attention, vous allez définitivement effacer la base de données, êtes-vous sûrs ?
        </div>
        <div class="col-xs-6">
            <a href="#" class="btn btn-danger btn-lg btn-deleteAll">OUI</a>
        </div>
        <div class="col-xs-6">
            <a href='/admin/liste.php?token=<?= $_GET['token']; ?>' class="btn btn-primary btn-lg">NON</a>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        if ( $('#alert') ) {
            var alertWrapper = $('#alert');
            var alertContent = $('.alert');
            alertWrapper.fadeIn().delay(2000).fadeOut(400);
        }

        $('.btn-deleteAll').on('click', function (e) {
            e.preventDefault();
            if ( confirm('Voulez vous vraiment vider la base de donnée ? Cette action est irrévocable') ) {
                var data = {
                    action: "validate",
                    type: "toutsupprimer"
                };
                $.post('actions/actions.php', data)
                    .done(function (result) {
                        var res = JSON.parse(result);
                        window.location.href = "liste.php";
                    })
                    .fail(function () {
                        alertContent.addClass('alert-danger').removeClass('alert-success').html('Une erreur est survenue lors de l\'action demandée');
                        alertWrapper.fadeIn().delay(2000).fadeOut(400);
                        setTimeout(location.reload(), 2000);
                    });
            }


        });

    });
</script>

</body>
</html>
