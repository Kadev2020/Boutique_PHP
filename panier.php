<?php

require_once('inc/init.inc.php');

if(isset($_POST['ajout_panier']))
{
    // echo '<pre>'; print_r($_POST) ;echo'</pre>';

    $r = $bdd->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
    $r->bindValue(':id_produit', $_POST['id_produit'], PDO::PARAM_INT);
    $r->execute();

    $p = $r->fetch(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($p) ;echo'</pre>';

    //Ajout dans la session un nouveau panier à la validation du formulaire dans le fichier fiche_produit.php
    ajoutPanier($p['id_produit'], $p['photo'], $p['reference'], $p['titre'], $_POST['quantite'],$p['prix']);    
}

// SUPPRESSION PRODUIT DANS LE PANIER
if(isset($_GET['action']) && $_GET['action'] == 'suppression')
{

    // On récupère l'indice auquel se trouve le produit que l'on souhaite supprimer du panier afin de personnaliser le message de validation de suppression 
    $positionProduit = array_search($_GET['id_produit'], $_SESSION['panier']['id_produit']);

    $vd = "<div class='bg-success col-md-6 mx-auto text-center rouded p-2 mb-2 text-white'> Le produit : <strong>" .$_SESSION['panier']['titre'][$positionProduit]. "</strong> référence <strong>" . $_SESSION['panier']['reference'][$positionProduit] . "</strong> a bien été retiré du panier </div>";

    suppProduit($_GET['id_produit']); //On transmet l'id_produit du produit à supprimer du panier à la fonction suppProduit() // la méthode array_splice() supprime chaque ligne dans les tab ARRAY de la session
}


//CONTROLE STOCK PRODUIT AU MOMENT DU PAIEMENT
if(isset($_POST['payer'])) // SI l'indice (name) 'payer' est bien défini => l'@ a donc cliqué sur le bouton 'VALIDER LE PAIEMENT' et donc par conséquent que l'attribut name 'payer' a été détécté
{
    $error = '';

    for($i = 0 ; $i < count($_SESSION['panier']['id_produit']); $i++) // tant qu'il y a de produit dans le panier la boucle FOR tourne
    {
        $r = $bdd->query('SELECT stock FROM produit WHERE id_produit =' . $_SESSION['panier']['id_produit'][$i]) ;
        $s = $r->fetch(PDO::FETCH_ASSOC);
        // echo '<pre>'; print_r($s);  echo'</pre>';

        
        
        if($s['stock'] < $_SESSION['panier']['quantite'][$i])// SI la quantité du stock du produit en BDD est inférieur à la quantité dans la session => on entre dans le IF
        {
            $error .= "<div class='bg-danger col-md-3 mx-auto text-center rouded p-2 mb-2 text-white'> Stock restant du produit : <strong>$s[stock]</strong></div>";

            $error .= "<div class='bg-success col-md-3 mx-auto text-center rouded p-2 mb-2 text-white'> Quantité demandée du produit : <strong>" .$_SESSION['panier']['quantite'][$i]. "</strong></div>";

            //SI stock BDD > 0 mais < à la quantité demandée par l'@ on entre dans la condition IF
            if($s['stock'] > 0)
            {
                $error .= "<div class='bg-danger col-md-3 mx-auto text-center rouded p-2 mb-2 text-white'> La quantité du produit : <strong>" .$_SESSION['panier']['titre'][$i]. "</strong> référence <strong>" .$_SESSION['panier']['reference'][$i]. "</strong> a été modifiée car notre stock est insuffisant, vérifiez vos achats. </div>";

                //Affectation de la totalité de la quantité restante
                $_SESSION['panier']['quantite'][$i] = $s['stock'];
            }
            else // SINON si stock = 0 on entre dans le ELSE
            {
                $error .= "<div class='bg-danger col-md-3 mx-auto text-center rouded p-2 mb-2 text-white'> Le produit : <strong>" .$_SESSION['panier']['titre'][$i]. "</strong> référence <strong>" .$_SESSION['panier']['reference'][$i]. "</strong> a été supprimé car nous sommes en rupture de stock, vérifiez vos achats. </div>";

                suppProduit($_SESSION['panier']['id_produit'][$i]);// 
                $i--; // on fait un retour de boucle en arrière on "décrémente" car array_splice() remonte les indices inférieurs vers les indices supérieurs, cela permet de ne pas oublier un produit qui aurait remonté d'un indice dans le tableau ARRAY de la session
            }
            $err = true;
        }
    }
    //SI la variable $err n'est pas définie => les stocks sont supérieurs à la quantité commandée par l'@ on entre dans la condition IF
    if(!isset($err))
    {
        //ENREGISTREMENT DE LA COMMANDE
        $r = $bdd->exec("INSERT INTO commande (membre_id, montant, date_enregistrement) VALUES (" . $_SESSION['user']['id_membre'] . "," . montantTotal() .", NOW()) ");

        $idCommande = $bdd -> lastInsertId(); // permet de récupérer le dernier id_commande créé dans la BDD afin de l'enregistrer dans la table details_commande pour chaque produit à la bonne commande

        for($i = 0 ; $i < count($_SESSION['panier']['id_produit']); $i++)// pour chaque tour de boucle FOR on éxecute une insertion de la table details_commande pour chaque produit ajouté au panier
        // On récupère le dernier id_commande généré en BDD afin de relier chaque produit à la bonne commande dans la table details_commande
        {
            $r = $bdd->exec("INSERT INTO details_commande (commande_id, produit_id, quantite, prix) VALUES ($idCommande, " . $_SESSION['panier']['id_produit'][$i] ."," . $_SESSION['panier']['quantite'][$i] . "," . $_SESSION['panier']['prix'][$i] . ")");

            //Dépréciation des stocks 
            $r = $bdd->exec("UPDATE produit SET stock = stock - ". $_SESSION['panier']['quantite'][$i] . " WHERE id_produit = " . $_SESSION['panier']['id_produit'][$i]);
        }

        unset($_SESSION['panier']); // On supprime les éléments du panier de la session après validation du paiement et donc l'insert dans les tables commande et details_commande

        $_SESSION['num_cmd'] = $idCommande; // on sotcke l'id_commande dans la session après validation panier
        header('location: validation_cmd.php'); // redirection de l'@ après validation panier
    }
}


// echo '<pre>'; print_r($_SESSION) ;echo'</pre>';
require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');

?>
<h1 class="display-4 text-center my-4"> Mon Panier</h1>

<?php if(isset($error)) echo $error; ?>
<?php if(isset($vd)) echo $vd; ?>

<table class="col-md-8 mx-auto table table-bordered text-center ">

    <tr>
        <th>PHOTO</th>
        <th>REFERENCE</th>
        <th>TITRE</th>
        <th>QUANTITE</th>
        <th>PRIX unitaire</th>
        <th>PRIX total/produit</th>
        <th>SUPP</th>
    </tr>

    <?php if(empty($_SESSION['panier']['id_produit'])) : // Si l'indice 'id_produit' dans le panier de la session est vide ou non définie on entre dans IF?> 

        <tr><td colspan="7" class="text-danger"> Aucun produit dans le panier </td></tr>

        </table>

    <?php else: //Sinon des 'id_produit' définie dans le panier de la session on entre dans la condition ELSE et on affiche le contenu du panier ?>

        <?php for($i=0 ; $i < count($_SESSION['panier']['id_produit']); $i++) : ?>
        
            <tr> 
                <!-- Pour chaque boucle FOR nous allons crocheter aux indices numériques des  différents ARRAY dans la session afin d'afficher la photo le titre etc. des  produits ajoutés dans le panier-->
                <td ><a href="fiche_produit.php?id_produit=<?=$_SESSION['panier']['id_produit'][$i];?>"> <img src="<?=$_SESSION['panier']['photo'][$i];?>" alt="<?=$_SESSION['panier']['titre'][$i];?>" style="width: 100px"> </a></td>
                <td> <?=$_SESSION['panier']['reference'][$i];?> </td>
                <td> <?=$_SESSION['panier']['titre'][$i];?> </td>
                <td> <?=$_SESSION['panier']['quantite'][$i];?> </td>
                <td> <?=$_SESSION['panier']['prix'][$i];?> € </td>
                <td> <?=$_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i]; ?> € </td>
                <td> <a href="?action=suppression&id_produit=<?= $_SESSION['panier']['id_produit'][$i] ?>" class="btn btn-danger"><i class='far fa-trash-alt' ></i></a></td>
            </tr>
        
        <?php endfor; ?>
            <tr>
                <th> MONTANT TOTAL </th>
                <td colspan="4"> </td>
                <th><?= montantTotal(); ?> € </th>
                <td></td>
            </tr>
        
    </table>

        <?php if(connect()): //Si l'internaute est connecté il peut valider le paiement?>

            <form action="" method="post" class="col-md-8 mx-auto pl-0">
                <input type="submit" name="payer" value="VALIDER LE PAIEMENT" class="btn btn-success">
            </form>

        <?php else: // SINON renvoi vers la page de connexion?>

            <a href="<?= URL ?>connexion.php" class="offset-md-2 btn btn-success mb-3"> IDENTIFIEZ-VOUS POUR VALIDER LA COMMANDE </a> 

        <?php endif; ?>

    <?php endif; ?>

<?php

require_once('inc/footer.inc.php');