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

$inscription = Config::QueryBuilder()->findOne("exposants")->where(['id' => intval($_GET['user'])])->execute();
if ( $inscription == false) {
    throw new Exception ("Erreur");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Visualisation d'un utilisateur</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link type="text/css" rel="stylesheet" href="../assets/css/TopNavBarStyles.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<body>



<div id="alert" style="display: none">
    <div class="alert"></div>
</div>
<style type="text/css">

    h2 {
        text-align: center;
    }

    td {
        width: 60%;
    }

    button {
        margin-left: 5%;
    }

    body {
        font-size: 1.8em;
    }

    .container {
        padding-top: 60px;
    }
</style>
<?php include('../includes/common/topNavBar.php');?>


<div class="container">

    <h2>Visualisation complète des informations d'une inscription</h2>
        <div class="row">
        <?php if ($inscription->valid ==0) {
            $statut = "<span style='color:orange;'>En attente</span>";
        }else if( $inscription->valid ==1 ){
            $statut = "<span style='color:green;'>Validé</span>";
        } else {
            $statut = "<span style='color:red;'>Refusé</span>";
        }?>
        <h3>Statut de l'inscription: <?= $statut ;?></h3>
        <div class="col-xs-12" style="text-align: center;">
            <div class="col-xs-6">
                <?php if ($inscription->valid == 0): ?>
                    <button type="button" class="btn btn-success btn-validate"
                            data-id="<?= $inscription->id; ?> ">Valider
                    </button>
                    <button type="button" class="btn btn-danger btn-refused"
                            data-id="<?= $inscription->id; ?>">Refuser
                    </button>
                <?php elseif($inscription->valid == 1): ?>
                    <button type="button" class="btn btn-danger btn-refused"
                            data-id="<?= $inscription->id; ?>">Refuser
                    </button>
                <?php else: ?>
                    <button type="button" class="btn btn-success btn-validate"
                            data-id="<?= $inscription->id; ?>">Valider
                    </button>
                <?php endif; ?>
            </div>
            <div class="col-xs-6">
                <a href="edit.php?user=<?= $inscription->id;?>&token=<?= $_GET['token'] ?>" class="btn btn-primary">Editer</a>
                <button type="button" class="btn btn-danger btn-delete"
                        data-id="<?= $inscription->id; ?>">Supprimer
                </button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="table">
                <form action="" method="post">
                    <table summary="Visualisation des informations d'un propriétaire"
                           class="table table-hover table-responsive table-condensed table-striped">
                        <thead>
                        <tr>
                            <th colspan="2" class="text-center">Propriétaire</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row">Nom</th>
                            <td><?= $inscription->lastname; ?></td>

                        </tr>
                        <tr>
                            <th scope="row">Prénom</th>
                            <td><?= $inscription->firstname; ?></td>

                        </tr>
                        <tr>
                            <th scope="row">Email</th>
                            <td><?= $inscription->email; ?></td>

                        </tr>
                        <tr>
                            <th scope="row">Ville</th>
                            <td><?= $inscription->city; ?></td>

                        </tr>
                        <tr>
                            <th scope="row">Code Postal</th>
                            <td><?= $inscription->cp; ?></td>

                        </tr>
                        <tr>
                            <th scope="row">Pays</th>
                            <td><?= $inscription->country; ?></td>

                        </tr>
                        <tr>
                            <th scope="row">Club</th>
                            <td><?= $inscription->club; ?></td>

                        </tr>
                        <tr>
                            <th scope="row">Newsletter</th>
                            <td>
                                <select name="newsletter"  class="form-control" disabled>
                                    <option value="0" <?= ($inscription->newsletter == 0) ? "selected" :'';?> >Non</option>
                                    <option value="1" <?= ($inscription->newsletter == 1) ? "selected" :'';?> >Oui</option>
                                </select>
                            </td>
                        </tr>
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="table">
                <form action="" method="post">
                    <table summary="Visualisation des informations d'un véhicule"
                           class="table table-hover table-responsive table-condensed table-striped">
                        <thead>
                        <tr>
                            <th colspan="2" class="text-center">Véhicule</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row">Marque</th>
                            <td><?= $inscription->marque; ?></td>

                        </tr>
                        <tr>
                            <th scope="row">Modèle</th>
                            <td><?= $inscription->model; ?></td>

                        </tr>
                        <tr>
                            <th scope="row">Type</th>
                            <td><?= $inscription->type; ?></td>

                        </tr>
                        <tr>
                            <th scope="row">Motorisation</th>
                            <td><?= $inscription->motorisation; ?></td>

                        </tr>
                        <tr>
                            <th scope="row">Date de mise en circulation</th>
                            <td><?= $inscription->date_circu; ?></td>

                        </tr>
                        <tr>
                            <th scope="row">Immatriculation</th>
                            <td><?= $inscription->immat; ?></td>

                        </tr>
                        <tr>
                            <th scope="row">Information complémentaire sur le véhicule</th>
                            <td><p><?= ucfirst($inscription->infos); ?></p></td>

                        </tr>
                        <tr>
                            <th scope="row">Claude Lorrenzini</th>
                            <td><p><?= ($inscription->concours1 == 1) ? "Oui" : "Non" ?></p></td>

                        </tr>
                        <tr>
                            <th scope="row">Coupé-cabriolet</th>
                            <td><p><?= ($inscription->concours2 == 1) ? "Oui" : "Non" ?></p></td>

                        </tr>
                        <tr>
                            <th scope="row">Jeune de -25 ans</th>
                            <td><p><?= ($inscription->concours3 == 1) ? "Oui" : "Non" ?></p></td>

                        </tr>

                        </tbody>
                    </table>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="http://<?=$_SERVER['SERVER_NAME'].'/assets/js/bootstrap.min.js';?>"></script>
<script src="http://<?=$_SERVER['SERVER_NAME']."/assets/js/topNavBarScript.js";?>"></script>

<script>
    $(document).ready(function(){
        if ( $('#alert') ) {
            var alertWrapper = $('#alert');
            var alertContent = $('.alert');
            alertWrapper.fadeIn().delay(2000).fadeOut(400);
        }

        var token = location.search.split('token=');
        $('.btn-validate').on('click', function (e) {
            e.preventDefault();
            var data = {
                action: "validate",
                type: "valider",
                token: token[1],
                id: $(this).data('id')
            };
            var alertWrapper = $('#alert');
            var alertContent = $('.alert');

            $.post('../admin/actions/actions.php', data)
                .done(function (result) {
                    var res = JSON.parse(result);
                    alertContent.addClass('alert-success').removeClass('alert-danger').html(res.message);
                    alertWrapper.fadeIn().delay(1000).fadeOut(400);
                    setTimeout(location.reload(), 6000);
                })
                .fail(function () {
                    alertContent.addClass('alert-danger').removeClass('alert-success').html('Une erreur est survenue lors de l\'action demandée');
                    alertWrapper.fadeIn().delay(1000).fadeOut(400);
                    setTimeout(location.reload(), 6000);
                })
        });
        $('.btn-refused').on('click', function (e) {
            e.preventDefault();

            var data = {
                action: "validate",
                type: "refuser",
                token: token[1],
                id: $(this).data('id')
            };
            var alertWrapper = $('#alert');
            var alertContent = $('.alert');

            $.post('../admin/actions/actions.php', data)
                .done(function (result) {

                    var res = JSON.parse(result);
                    alertContent.addClass('alert-success').removeClass('alert-danger').html(res.message);
                    alertWrapper.fadeIn().delay(2000).fadeOut(400);
                    setTimeout(location.reload(), 2000);
                })
                .fail(function () {
                    alertContent.addClass('alert-danger').removeClass('alert-success').html('Une erreur est survenue lors de l\'action demandée');
                    alertWrapper.fadeIn().delay(2000).fadeOut(400);
                    setTimeout(location.reload(), 2000);
                })
        });

        $('.btn-delete').on('click', function (e) {
            e.preventDefault();
            if ( confirm('Voulez vous vraiment supprimer cet enregistrement') ) {
                var data = {
                    action: "validate",
                    type: "supprimer",
                    token: token[1],
                    id: $(this).data('id')
                };
                $.post('../admin/actions/actions.php', data)
                    .done(function (result) {
                        console.log(result);
                        var res = JSON.parse(result);
                        window.location.href = "http://biellesMeusiennes.fr/admin/liste.php?token="+token[1];
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
