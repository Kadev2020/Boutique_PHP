<?php

require_once('inc/init.inc.php');
    /*
    1. réalsier le traitement SQL + PHP permettant de selectionnés les données du produit par rapport à l'id_produit transmis dans l'URL
    2. Faites en sorte que si l'id_produit dans l'URL n'est pas définit ou sa valeur est vide, de re-diriger vers la page boutique
    3. Si la requete de selection ne retourne aucun produit de la BDD, faites en sorte de re-diriger vers la page boutique
    4. Afficher les détails du produit dans l'affichage HTML, dans les div ci-dessous 
    */
    if(isset($_GET['id_produit']) && !empty($_GET['id_produit']))
    {
        
        $r = $bdd->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
        $r->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
        $r->execute();

        // Si la req de selection retourne 1 résultat de la BDD => id_produit connu en BDD donc on entre dans la condition IF
        if($r->rowCount()){ 

            $p = $r->fetch(PDO::FETCH_ASSOC);
            // echo '<pre>'; print_r($p); echo'</pre>';

        }else{// Sinon on redirige vers la page boutique

            header('location: boutique.php');
    
        }
    
    }else{ // Sinon indice 'porduit' non défini dans url valeur est donc vide alors on entre dans la condition pour sélectionner l'ensemble des produits (pour les afficher)
    
        header('location: boutique.php');
    }

require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');

?>

<div class="container">

<div class="row">

    <?php
        $d = $bdd->query("SELECT DISTINCT categorie FROM produit");
    ?>
    <!-- Exo : afficher la liste des catégories sotckées en BDD, chaque lien de catégorie renvoie vers la page boutique à la bonne catégorie -->
    <div class="col-lg-3">
        <h1 class="my-4">Kadev's Shop</h1>
        <div class="list-group">
        <p class='text-center' style="font-size: 35px"><u>Catégories</u></p>
            <?php while($c = $d->fetch(PDO::FETCH_ASSOC)):

            // echo '<pre>'; print_r($c); echo '</pre>' ;?>

                <a href="boutique.php?categorie=<?= $c['categorie'] ?>" class="list-group-item text-dark text-center"><?= strtoupper($c['categorie'])?></a>

            <?php endwhile; ?>
            
        </div>
    </div>
  <!-- /.col-lg-3 -->

    <div class="col-lg-9">

        <div class="card mt-4">
            <img class="card-img-top img-fluid" src="<?= $p['photo'] ?>" alt="<?= $p['photo'] ?>">
            <div class="card-body">
                <h3 class="card-title"><?= $p['titre'] ?></h3>
                <h4><?= $p['prix'] ?> €</h4>

                <p class="card-text"><?= $p['description'] ?></p>

                <p class="card-text">Catégorie : <a href="boutique.php?categorie=<?= $p['categorie'] ?>"><?= $p['categorie'] ?></a></p>

                <p class="card-text">Référence : <?= $p['reference'] ?></p>

                <p class="card-text">Couleur : <?= $p['couleur'] ?></p>

                <p class="card-text">Taille : <?= $p['taille'] ?></p>
                
                <p class="card-text">Public : <?= $p['public'] ?></p>

                <?php if($p['stock'] <=10 && $p['stock'] !=0): // SI produit <10 && !=0?>
                
                    <p class="card-text font-italic text-danger"> Attention il ne reste que <?= $p['stock'] ?> exemplaire(s) en stock</p>

                <?php elseif($p['stock'] > 10): //SINON SI stock > 10?> 

                    <p class="card-text font-italic text-success"> En stock</p>

                <?php endif; ?>

                <hr>

                <?php if($p['stock'] > 0): //SINON SI stock > 0 ==> l'@ pourra ajouter au panier avec formulaire?> 

                    <form method="post" action="panier.php" class="form-inline">
                        <input type="hidden" id="id_produit" name="id_produit" value="<?= $p['id_produit'] ?>">
                        <div class="form-group">
                            <select class="form-control" name="quantite" id="quantite">

                            <?php for($i=1 ; $i <= $p['stock'] && $i <= 30; $i++): ?>

                            <option value="<?= $i ?>"><?= $i ?></option>

                            <?php endfor ;?>
                            </select>

                        </div>
                        <input type="submit" name="ajout_panier" value="AJOUTER AU PANIER" class=" bg-success text-white">
                    </form>

                <?php else: //Sinon message si stock = 0 on entre dans la condition ELSE?> 

                    <p class="card-text font-italic text-danger"> Rupture de stock</p>

                <?php endif; ?>
                
            </div>
        </div>
    <!-- /.card -->

    <div class="card card-outline-secondary my-4">
        <div class="card-header">
            Dernier avis postés...
        </div>
        <div class="card-body">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
            <small class="text-muted">Posted by Anonymous on 3/1/17</small>
            <hr>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
            <small class="text-muted">Posted by Anonymous on 3/1/17</small>
            <hr>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
            <small class="text-muted">Posted by Anonymous on 3/1/17</small>
            <hr>
            <a href="#" class="btn btn-success">Laissez un commentaire ...</a>
        </div>
    </div>

    
    <!-- /.card -->

  </div>
  <!-- /.col-lg-9 -->

</div>

</div>

<?php

require_once('inc/footer.inc.php');