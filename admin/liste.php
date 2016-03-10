<?php
include($_SERVER['DOCUMENT_ROOT'].'/includes/common/verif_security.php');
try {
    verif_origin_user();
} catch (Exception $e) {
    header('Location: http://biellesMeusiennes.fr/admin/index.php?message=errortoken' );
    die();
}

require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
use Core\Configure\Config;

$inscriptions = Config::QueryBuilder()->findAll("exposants")->execute();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Informations des utilisateurs</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.0.2/css/responsive.dataTables.min.css">

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

    h1 {
        text-align: center;
    }

    th, td {
        text-align: center;
    }

    button {
        margin-left: 5px;
    }

    body {
        font-size: 1.7em;
    }

    .container-fluid {
        padding-top: 60px;
    }
    table.dataTable{
        margin:0;
    }
    #moTable {
        width:100%;
    }
</style>

<!--FIN du style CSS perso-->



    <?php include('../includes/common/topNavBar.php');?>

<div class="container-fluid">
    <h1>Informations générales des utilisateurs</h1>
    <div id="alert" style="display:none;">
        <div class="alert"></div>
    </div>
    <div class="row" style="padding-bottom:50px;">
        <div class="col-xs-12">
            <div class="pull-right">
                <form action="actions/actions.php" method="POST">
                    <input name="action" value="exportCsv" type="hidden">
                    <input name="token" value="<?= $_GET['token'];?>" type="hidden">
                    <input name="model" value="Exposants" type="hidden">
                    <button id="btn-csv" type="submit" class="btn btn-primary">Export csv</button>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="table-responsive">
                <table id="moTable" summary="Exemple d'affichage du tableau de visualisation des utilisateurs"
                       class="table table-hover table-striped table-responsive table-condensed">
                    <thead>
                    <tr>
                        <th data-priority="0">Nom</th>           <!-- Les priorités permettent de définir dans quel ordre se cachent les colonnes pour que l'affichage reste responsive. -->
                        <th data-priority="1">Prénom</th>          <!--  Les colonnes ici se cachent dans l'ordre suivant 9/8/7/6/5/4/3/2/1, priorité d'affichage aux valeurs basses -->
                        <th data-priority="3">Email</th>
                        <th data-priority="8">Newsletter</th>
                        <th data-priority="5">Marque</th>
                        <th data-priority="6">Modèle</th>
                        <th data-priority="7">Immat.</th>
                        <th data-priority="4">Date de Mise en Circulation</th>
                        <th data-priority="2">Validé</th>
                        <th style="display:none;" class="Hidden"></th>
                        <th data-priority="9">Actions Administrateur</th>
                    </tr>
                    </thead>
                    <tbody class="tbody">
                    <?php foreach ($inscriptions as $inscription): ?>
                        <tr>
                            <?php if($inscription->valid == 0){
                                $color = "orange";
                                $text = "en attente";
                            } else if ($inscription->valid == 1){
                                $color = "green";
                                $text = "validé";
                            } else if($inscription->valid == 2){
                                $color ="red";
                                $text = "refusé";
                            }?>
                            <td><?= $inscription->lastname; ?></td>
                            <td><?= $inscription->firstname; ?></td>
                            <td><?= $inscription->email; ?></td>
                            <td><?= ($inscription->newsletter) ? "<span style='color:green;'>Oui</span>" : "<span style='color:red;'>Non</span>"; ?></td>
                            <td><?= $inscription->marque; ?></td>
                            <td><?= $inscription->model; ?></td>
                            <td><?= $inscription->immat; ?></td>
                            <td><?= $inscription->date_circu; ?></td>
                            <td><span style="color:<?=$color ?>; width:0.3%;"><?= $text ?></span></td>
                            <td style="display:none;" class="Hidden"><span style="visibility: hidden;"><?= $inscription->valid;?></span></td>
                            <td>
                                <span class="pull-left">
                                    <a href="view.php?user=<?= $inscription->id; ?>&token=<?= $_GET['token'] ?>"
                                       class="btn btn-default btn-md">Voir</a>
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
                                </span>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!--DataTable-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="http://<?=$_SERVER['SERVER_NAME'].'/assets/js/bootstrap.min.js';?>"></script>
<script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<script src="http://<?=$_SERVER['SERVER_NAME']."/assets/js/topNavBarScript.js";?>"></script>

<script src="https://cdn.datatables.net/responsive/2.0.2/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.colVis.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#moTable').dataTable({
            "responsive": true,
            "sScrollX": "100%", // retrait de la barre de scroll horizontale en responsive
            "language": { // mise en Français du pluggin.
                "sProcessing": "Traitement en cours...",
                "sSearch": "Rechercher&nbsp;:",
                "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
                "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
                "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                "sInfoPostFix": "",
                "sLoadingRecords": "Chargement en cours...",
                "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
                "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
                "oPaginate": {
                    "sFirst": "Premier",
                    "sPrevious": "Pr&eacute;c&eacute;dent",
                    "sNext": "Suivant",
                    "sLast": "Dernier"
                },

                "oAria": {
                    "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                    "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
                }
            },
            "pageLength": 100, // Affichage de 100 éléments de base au chargement de la page
            "lengthMenu": [ [100, 250, 500, 1000, -1], [100, 250, 500, 1000, "tous les"] ], // Changement du menu de base pour la sélection de l'affichage du nombre d'éléments : [nombres d'éléments à afficher] [libellés]
            "order": [[1, 'asc']]
        });
        $('#moTable').css('width', '100%');
        $('.dataTables_scrollHeadInner').css('width', '100%');
        $('.table').css('width', '100%');
        $('.Hidden').css('display', 'none');
        var token = location.search.split('token=');

        $('tbody').on('click', '.btn-validate', function (e) {
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
                    console.log("ok result");
                    console.log(result);
                    alertContent.addClass('alert-success').removeClass('alert-danger').html(res.message);
                    alertWrapper.fadeIn().delay(500).fadeOut(2400);
                    setTimeout(location.reload(), 2000);
                })
                .fail(function () {                    
                    alertContent.addClass('alert-danger').removeClass('alert-success').html('Une erreur est survenue lors de l\'action demandée');
                    alertWrapper.fadeIn().delay(500).fadeOut(2400);
                    setTimeout(location.reload(), 2000);
                })
        });
        $('tbody').on('click', '.btn-refused', function (e) {
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
                    alertWrapper.fadeIn().delay(500).fadeOut(2400);
                    setTimeout(location.reload(), 2000);
                })
                .fail(function () {
                    alertContent.addClass('alert-danger').removeClass('alert-success').html('Une erreur est survenue lors de l\'action demandée');
                    alertWrapper.fadeIn().delay(500).fadeOut(2400);
                    setTimeout(location.reload(), 2000);
                })
        });
    });
</script>


</body>
</html>
