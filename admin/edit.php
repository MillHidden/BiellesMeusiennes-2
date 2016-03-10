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
$message = false;
$class = false;

if (!empty($_POST)) {

    $id =   intval($_POST['id']);
    $_POST['newsletter'] = intval($_POST['newsletter']);
    $_POST['concours1'] = intval($_POST['concours1']);
    $_POST['concours2'] = intval($_POST['concours2']);
    $_POST['concours3'] = intval($_POST['concours3']);
    $_POST['cp'] = intval($_POST['cp']);
    $_POST['valid'] = intval($_POST['valid']);

    $edit = Config::QueryBuilder()->update('exposants', ['id' => $id], $_POST)->execute();
    if ($edit) {
        $message = "Participant mis à jour";
        $class = "alert-success";
    } else {
        $message = "Une erreur est survenue merci de contacter l'administrateur";
        $class = "alert-danger";
    }
}
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
<?php if ($message) :?>
    <div id="alert">
        <div class="alert <?= $class; ?>">
            <?= $message ;?>
        </div>
    </div>

<?php endif ;?>
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
</style>

<?php include('../includes/common/topNavBar.php');?>

<h2>Visualisation complète des informations d'une inscription</h2>

<div class="container">
    <div class="row">
        <?php if ($inscription->valid ==0) {
            $statut = "<span style='color:orange;'>En attente</span>";
        }else if( $inscription->valid ==1 ){
            $statut = "<span style='color:green;'>Validé</span>";
        } else {
            $statut = "<span style='color:red;'>Refusé</span>";
        }?>
        <h3>Statut de l'inscription: <?= $statut ;?></h3>
        
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="table">
                <form action="" method="post">
                    <input type="hidden" value="<?= $inscription->id ;?>" name="id">
                    <input type="hidden" value="<?= $inscription->valid ;?>" name="valid">
                    <div class="col-xs-12" style="text-align: center;">
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                    <table summary="Visualisation des informations d'un propriétaire"
                       class="table table-hover table-responsive table-condensed table-striped">
                    <thead>
                    <tr>
                        <th colspan="2" class="text-center">Propriétaire</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">NOM</th>
                        <td><input type="text" value="<?= $inscription->lastname; ?>" name="lastname" class="form-control"></td>

                    </tr>
                    <tr>
                        <th scope="row">Prénom</th>
                        <td><input type="text" value="<?= $inscription->firstname; ?>" name="firstname" class="form-control"></td>

                    </tr>
                    <tr>
                        <th scope="row">Email</th>
                        <td><input type="text" value="<?= $inscription->email; ?>"  name="email" class="form-control"></td>

                    </tr>
                    <tr>
                        <th scope="row">Ville</th>
                        <td><input type="text" value="<?= $inscription->city; ?>" name="city" class="form-control"></td>

                    </tr>
                    <tr>
                        <th scope="row">Code Postal</th>
                        <td><input type="text" value="<?= $inscription->cp; ?>" name="cp" class="form-control"></td>

                    </tr>
                    <tr>
                        <th scope="row">Pays</th>
                        <td><input type="text" value="<?= $inscription->country; ?>" name="country" class="form-control"></td>

                    </tr>
                    <tr>
                        <th scope="row">Club</th>
                        <td><input type="text" value="<?= $inscription->club; ?>"  name="club" class="form-control"></td>

                    </tr>
                    <tr>
                        <th scope="row">Newsletter</th>
                        <td>
                            <select name="newsletter"  class="form-control">
                                <option value="0" <?= ($inscription->newsletter == 0) ? "selected" :'';?> >Non</option>
                                <option value="1" <?= ($inscription->newsletter == 1) ? "selected" :'';?> >Oui</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Claude Lorrenzini</th>
                        <td>
                            <select name="concours1"  class="form-control">
                                <option value="0" <?= ($inscription->concours1 == 0) ? "selected" :'';?> >Non</option>
                                <option value="1" <?= ($inscription->concours1 == 1) ? "selected" :'';?> >Oui</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Coupé-Cabriolet</th>
                        <td>
                            <select name="concours2"  class="form-control">
                                <option value="0" <?= ($inscription->concours2 == 0) ? "selected" :'';?> >Non</option>
                                <option value="1" <?= ($inscription->concours2 == 1) ? "selected" :'';?> >Oui</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Jeune de -25 ans</th>
                        <td>
                            <select name="concours3"  class="form-control">
                                <option value="0" <?= ($inscription->concours3 == 0) ? "selected" :'';?> >Non</option>
                                <option value="1" <?= ($inscription->concours3 == 1) ? "selected" :'';?> >Oui</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Marque</th>
                        <td><input type="text" value="<?= $inscription->marque; ?>" class="form-control" name="marque"></td>

                    </tr>
                    <tr>
                        <th scope="row">Modèle</th>
                        <td><input type="text" value="<?= $inscription->model; ?>" class="form-control" name="model"></td>

                    </tr>
                    <tr>
                        <th scope="row">Type</th>
                        <td><input type="text" value="<?= $inscription->type; ?>" class="form-control" name="type"></td>

                    </tr>
                    <tr>
                        <th scope="row">Motorisation</th>
                        <td><input type="text" value="<?= $inscription->motorisation; ?>" class="form-control" name="motorisation"></td>

                    </tr>
                    <tr>
                        <th scope="row">Date de mise en circulation</th>
                        <td><input type="text" value="<?= $inscription->date_circu; ?>" class="form-control" name="date_circu"></td>

                    </tr>
                    <tr>
                        <th scope="row">Immatriculation</th>
                        <td><input type="text" value="<?= $inscription->immat; ?>" class="form-control" name="immat"></td>

                    </tr>
                    <tr>
                        <th scope="row">Information complémentaire sur le véhicule</th>
                        <td><textarea  class="form-control" name="infos"> <?= $inscription->infos; ?></textarea></td>

                    </tr>
                    </tbody>
                </table>
                <div class="col-xs-12" style="text-align: center;">
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
                </form>
            </div>
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


        $('.btn-validate').on('click', function (e) {

            e.preventDefault();
            e.stopPropagation();

            var data = {
                action: "validate",
                type: "valider",
                id: $(this).data('id')
            };
            var alertWrapper = $('#alert');
            var alertContent = $('.alert');

            $.post('actions/actions.php', data)
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
            e.stopPropagation();
            var data = {
                action: "validate",
                type: "refuser",
                id: $(this).data('id')
            };
            var alertWrapper = $('#alert');
            var alertContent = $('.alert');

            $.post('actions/actions.php', data)
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

            var data = {
                action: "validate",
                type: "supprimer",
                id: $(this).data('id')
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
                })
        });

    });
</script>
</body>
</html>
