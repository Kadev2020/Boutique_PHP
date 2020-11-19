<?php

require_once('inc/init.inc.php');

if(isset($_GET['categorie']) && !empty($_GET['categorie']))// Si indice 'categorie' est bien définie dans URL et que sa valeur != de vide cela veut dire que l'internaute a cliqué sur un lien de catégorie et a donc transmis les paramètres 'categorie=jean' (par ex) 
{
    // On sélectionne tout en BDD par rapport à la catégorie transmise dans l'URL afin d'afficher tous les pdtsliés à la catégories
    $r = $bdd->prepare("SELECT * FROM produit WHERE categorie = :categorie");
    $r->bindValue(':categorie', $_GET['categorie'], PDO::PARAM_STR);
    $r->execute();

    if($r->rowCount() == false){ // Si rowCount ne renvoie rien => retourne false : catégorie dans l'url n'est pas connu en BDD ---> redirige l'@ vers la boutique

        header('location: boutique.php');

    }

}else{ // Sinon indice 'porduit' non défini dans url valeur est donc vide alors on entre dans la condition pour sélectionner l'ensemble des produits (pour les afficher)

    $r = $bdd->query("SELECT * FROM produit");
}

require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');

?>

<!-- Page Content -->
<div class="container">

<div class="row">

  <div class="col-lg-3">

  <!-- EXO : Afficher les catégories stockées en BDD dans les liens hypertexte <a></a> 1 lien <a></a> par catégorie et faire en sorte d'envoyer la catégorie dans l'URL  (?categorie=jean) - SELECT + BOUCLE + FETCH -->

  <?php
    $d = $bdd->query("SELECT DISTINCT categorie FROM produit");
  ?>

    <h1 class="my-4">Kadev's Shop</h1>
    
    <div class="list-group">
    <p class='text-center' style="font-size: 35px"><u>Catégories</u></p>
        <?php while($c = $d->fetch(PDO::FETCH_ASSOC)):

            // echo '<pre>'; print_r($c); echo '</pre>' ;?>
        
        <a href="?categorie=<?= $c['categorie'] ?>" class="list-group-item text-dark text-center"><?= strtoupper($c['categorie'])?></a>

        <?php endwhile; ?>
    </div>

  </div>
  <!-- /.col-lg-3 -->

  <div class="col-lg-9">

    <div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
      <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner" role="listbox">
        <div class="carousel-item active">
          <img class="d-block img-fluid" src="<?= URL ?>photo/boutique/image 1.jpg" alt="First slide">
        </div>
        <div class="carousel-item">
          <img class="d-block img-fluid" src="<?= URL ?>photo/boutique/image 2.jpg" alt="Second slide">
        </div>
        <div class="carousel-item">
          <img class="d-block img-fluid" src="<?= URL ?>photo/boutique/image 3.jpg" alt="Third slide">
        </div>
      </div>
      <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>

    <div class="row">

        <?php while($p = $r->fetch(PDO::FETCH_ASSOC)):
            // echo '<pre>'; print_r($p); echo'</pre>';
        ?>

      <div class="col-lg-4 col-md-6 mb-4 shadow-lg p-3 mb-5 bg-white">
        <div class="card h-100">
          <a href="fiche_produit.php?id_produit=<?= $p['id_produit']?>"><img class="card-img-top" src="<?= $p['photo']?>" alt="<?= $p['titre']?>"></a>
          <div class="card-body">
            <h4 class="card-title">
              <a href="fiche_produit.php?id_produit=<?= $p['id_produit']?>"><?= $p['titre']?></a>
            </h4>
            <h5><?= $p['prix']?> €</h5>
            <p class="card-text">
                <?php 
                    if(iconv_strlen($p['description']) > 80) // Si chaine de car > 80 =>
                        echo substr($p['description'], 0, 80) . '...'; //On coupe la chaine de car à 80 
                    else
                        echo $p['description']; // Sinon => affiche normalement
                ?>
            </p>
          </div>
          <div class="card-footer text-center">
            <a href="fiche_produit.php?id_produit=<?= $p['id_produit']?>" class="btn btn-info"> En savoir plus &raquo;</a>
          </div>
        </div>
      </div>

        <?php endwhile; ?>

    </div>
    <!-- /.row -->

  </div>
  <!-- /.col-lg-9 -->

</div>
<!-- /.row -->

</div>
<!-- /.container -->


<?php

require_once('inc/footer.inc.php');
?>