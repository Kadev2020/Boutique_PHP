<?php

require_once('../inc/init.inc.php');

if(!adminConnect())
{
    header('location: '. URL . 'connexion.php');
}

// SUPPRESSION MEMBRE
// Exo : réaliser le traitement SQL + PHP permettant de supprimer un membre de la BDD en fonction de l'id_membre trasmit dans l'url.


if(isset($_GET['action']) && $_GET['action'] == 'suppression')
{
    
    $d = $bdd->prepare("DELETE FROM membre WHERE id_membre = :id_membre");
    $d -> bindValue(':id_membre', $_GET['id_membre'], PDO::PARAM_INT);
    $d->execute();

    
    // Message de validation de suppression
    $vd = "<p class='col-md-6 mx-auto bg-success text-center text-white p-3 rounded'> Le membre <strong>ID $_GET[id_membre]</strong> a bien été supprimé !!</p>";
}

// MODIFICATION MEMBRE
if(isset($_GET['action']) && $_GET['action'] == 'modification')
{
    if(isset($_GET['id_membre']) && !empty($_GET['id_membre']))
    {
        $r = $bdd->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
        $r->bindValue(':id_membre', $_GET['id_membre'], PDO::PARAM_INT);
        $r->execute();

        if($r->rowCount())
        {
            $m = $r->fetch(PDO::FETCH_ASSOC);

            // echo '<pre>'; print_r($m); echo'</pre>';
        }
        else
        {
            header('location: ' . URL . 'admin/gestion_membre.php');
        }
    }
    else
    {
        header('location: ' . URL . 'admin/gestion_membre.php');
    }
    foreach($m as $k => $v) //La boucle ForEach génère une variablepar tout de boucle // On se sert de la variable $k qui réceptionne un indice du tableau ARRAY par tour de boucle pour créer une variable
    {
        $$k = (isset($m[$k])) ? $m[$k] :'';
    }

    //REQ UPDATE MODIF MEMBRE

    if($_POST)
    {
        // echo '<pre>'; print_r($_POST); echo'</pre>';
        $up = $bdd->prepare("UPDATE membre SET civilite = :civilite, nom = :nom, prenom = :prenom, adresse = :adresse, ville = :ville, code_postal = :code_postal, statut = :statut WHERE id_membre = :id_membre" );
        $up->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
        $up->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
        $up->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
        $up->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
        $up->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
        $up->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_INT);
        $up->bindValue(':statut', $_POST['statut'], PDO::PARAM_INT);
        $up->bindValue(':id_membre', $_GET['id_membre'], PDO::PARAM_INT);
        $up->execute();

    $vUpdt = "<p class='col-md-6 mx-auto bg-success text-center text-white p-3 rounded'> Le membre <strong>ID $_GET[id_membre]</strong> a bien été modifié !!</p>";

        $_GET = '';
    }

}

require_once('../inc/header.inc.php');
require_once('../inc/nav.inc.php');

$r = $bdd->query('SELECT id_membre, pseudo, nom, prenom, email, civilite, ville, code_postal, adresse, statut  FROM membre');

?>
<!--
    Exo : Afficher l'ensemble de la table 'membre' sauf le MDP  dans un tab HTML
    SELECT + TABLE + FECTH
    Prévoir 2 colonnes supplémentaires pour modification et suppression de chaque membre
-->

<h1 class="display-4 text-center my-4">Liste des membres</h1>

<?php 
if(isset($vd)) echo $vd;

if(isset($vUpdt)) echo $vUpdt; 
?>

<?php

//TRAITEMENT AFFICHAGE NOMBRE ADMIN

    $n = $bdd->query('SELECT * FROM membre WHERE statut = 1');

//CONDITION AFFICHAGE MEMBRES
    if($r->rowCount() == 1)
        $txtM = 'membre enregistré.';
    else
        $txtM = 'membres enregistrés.';

    if($n->rowCount() == 1)
        $txtA = 'administrateur.';
    else
        $txtA = 'administrateurs.';
        
?>

<h5 class="badge badge-success"><?= $r -> rowCount() ?> <?=$txtM?></h5>
<h5 class="badge badge-info"><?= $n -> rowCount() ?> <?=$txtA?></h5>



<table class=" mx-auto table table-bordered text-center "><tr>

    <?php for($i=0 ; $i < $r->columnCount(); $i++) :
        $c = $r->getColumnMeta($i);    
    ?>

        <th><?= strtoupper($c['name'])?></th>
        
    <?php endfor; ?>

        <th>EDIT</th>
        <th>SUPP</th>
    </tr>
    <?php while($m = $r->fetch(PDO::FETCH_ASSOC)): ?>
        
        <tr>
            <?php foreach($m as $k => $v):?>

                <?php if($k == 'statut'): ?>

                    <?php if($v == 0): ?>

                        <td>Membre</td>

                    <?php else: ?>

                        <td class="bg-info text-white">Admin</td>

                    <?php endif;  ?>

                <?php else: ?>

                    <td><?= $v ?></td>

                <?php endif; ?>
                
            <?php endforeach ;?>
                
                <td><a href="?action=modification&id_membre=<?=$m['id_membre']?>" class="btn btn-dark"><i class="far fa-edit"></i></a></td>
                <td><a href="?action=suppression&id_membre=<?=$m['id_membre']?>" class="btn btn-danger" onclick="return(confirm('Êtes vous certain de vouloir supprimer ce membre ?'));"><i class="far fa-trash-alt"></i></a></td>
        </tr>
    <?php endwhile ; ?>

</table>

<?php if(isset($_GET['action']) && ($_GET['action'] == 'modification')): ?>

    <form method="post" class="col-md-6 mx-auto">
        <div class="form-group">
            <label for="civilite">Civilité</label>
            <select name="civilite" id="civilite" class="form-control">
                <option value="homme" <?php if($civilite=='homme') echo 'selected'; ?>> Monsieur</option>
                <option value="femme" <?php if($civilite=='femme') echo 'selected'; ?>>Madame</option>
            </select>
        </div>

        <div class="form-group">
            <label for="pseudo">Pseudo</label>
            <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?=$pseudo?>" disabled>
        </div>

        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" value="<?=$nom?>">
        </div>

        <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text" class="form-control" id="prenom" name="prenom" value="<?=$prenom?>">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" class="form-control" id="email" name="email" value="<?=$email?>" disabled>
        </div>

        <div class="form-group">
            <label for="adresse">Ville</label>
            <input type="text" class="form-control" id="adresse" name="adresse" value="<?=$adresse?>">
        </div>

        <div class="form-group">
            <label for="ville">Ville</label>
            <input type="text" class="form-control" id="ville" name="ville" value="<?=$ville?>">
        </div>

        <div class="form-group">
            <label for="code_postal">Code Postal</label>
            <input type="text" class="form-control" id="code_postal" name="code_postal" value="<?=$code_postal?>">
        </div>

        <div class="form-group">
            <label for="statut">Rôle</label>
            <select name="statut" id="statut" class="form-control">
                <option value="0" <?php if($statut== 0) echo 'selected'; ?>>Utilisateur</option>
                <option value="1" <?php if($statut== 1) echo 'selected'; ?>>Administrateur</option>
            </select>
        
        <button type="submit" class="btn btn-dark col-md-4 m-2">Modifier membre</button>
    </form>
<?php endif; ?>
<?php

require_once('../inc/footer.inc.php');


