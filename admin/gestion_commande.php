<?php

require_once('../inc/init.inc.php');

if(!adminConnect())
{
    header('location: '. URL . 'connexion.php');
}

if(isset($_GET['action']) && $_GET['action'] == 'details'){
    if(isset($_GET['id_commande']) && !empty($_GET['id_commande']))
    {
        $dCmd = $bdd->prepare("SELECT dc.produit_id AS ID, p.photo, p.reference, p.titre, p.categorie, dc.quantite, dc.prix FROM details_commande dc INNER JOIN produit p ON dc.produit_id = p.id_produit AND dc.commande_id = :id_commande");
        $dCmd->bindValue(':id_commande', $_GET['id_commande'], PDO::PARAM_INT);
        $dCmd->execute();

        if(!$dCmd->rowCount())
        {
            header('location: ' . URL . 'admin/gestion_commande.php');
        }
    }
    else{
        header('location: ' . URL . 'admin/gestion_commande.php');
    }
}

require_once('../inc/header.inc.php');
require_once('../inc/nav.inc.php');

/*Afficher la liste des commandes sous forme de tableau HTML contenant les colonnes suivantes :
    - id_commande
    - nom
    - prénom
    - mail
    - montant total
    - date enregistrement
    - etat
    - edit
    - détail
    - supp

    Jointure SQL entre la table commande et la table membre
    BOUCLE + FETCH
*/

$data = $bdd->query('SELECT id_commande AS "numero de commande", id_membre AS "numero de client", nom, prenom, email, montant AS "montant total", DATE_FORMAT(date_enregistrement, "%d/%m/%Y à %H:%i:%s") AS "date de commande", etat FROM membre INNER JOIN commande ON membre.id_membre = commande.membre_id');


?>
<h3 class="text-center m-4">Détails des commandes par client</h3>

<table class=" mx-auto table table-bordered text-center "><tr>

<?php for($i=0 ; $i < $data->columnCount(); $i++) :
        $c = $data->getColumnMeta($i);    
    ?>

        <th class="bg-success text-white"><?= strtoupper($c['name'])?></th>
        
    <?php endfor; ?>
        
        <th class="bg-info text-white">Détails</th>
        <th class="bg-warning text-white">Modifier</th>
        <th class="bg-danger text-white">Supprimer</th>

    </tr>

    <?php while($m = $data->fetch(PDO::FETCH_ASSOC)): 
        
        // echo '<pre>'; print_r($m); echo'</pre>'
        
        ?>
        <tr>

            <?php foreach($m as $k => $v):?>

                <?php if($k == 'montant total'): ?>

                    <td><?= $v ?> €</td>

                <?php else :?>

                    <td><?= $v ?></td>

                <?php endif ;?>

            <?php endforeach ;?>

            <td><a href="?action=details&id_commande=<?=$m['numero de commande']?>" class="btn btn-primary"><i class="fas fa-search"></i></a></td>
            
            <td><a href="?action=modification&id_commande=<?=$m['numero de commande']?>" class="btn btn-dark"><i class="far fa-edit"></i></a></td>
            
            <td><a href="?action=suppression&commande=<?=$m['numero de commande']?>" class="btn btn-danger" onclick="return(confirm('Êtes vous certain de vouloir supprimer cette commande ?'));"><i class="far fa-trash-alt"></i></a></td>

        </tr>

    <?php endwhile ; ?>

</table>

<?php if(isset($_GET['action']) && $_GET['action'] == 'details'): ?>

        <h4 class="text-center my-4"> Détails de la commande N° <span class="text-info"> <?=$_GET['id_commande']?></span></h4>
    
    <table class=" mx-auto table table-bordered text-center "><tr>

<?php for($i=0 ; $i < $dCmd->columnCount(); $i++) :
        $c = $dCmd->getColumnMeta($i);    
    ?>

        <th class="bg-success text-white"><?= strtoupper($c['name'])?></th>
        
    <?php endfor; ?>
    </tr>

    <?php while($p = $dCmd->fetch(PDO::FETCH_ASSOC)): 
        
        // echo '<pre>'; print_r($m); echo'</pre>'?>
        <tr>

        <?php foreach($p as $k => $v):?>

            <?php if($k == 'photo'): ?>

                <td><img src="<?=$v?>" alt="<?=$p['titre']?>" style="width: 50px"></td>

            <?php else :?>

                <td><?= $v ?></td>

            <?php endif ;?>

        <?php endforeach ;?>

            

        </tr>

    <?php endwhile ; ?>

</table>

<?php endif; ?>

<?php
require_once('../inc/footer.inc.php');
