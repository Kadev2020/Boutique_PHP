<nav class="navbar navbar-expand-md navbar-light bg-light sticky-top">
      <a class="navbar-brand" href="boutique.php">Kadev's Boutique ONLINE</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbar">
        <ul class="navbar-nav mr-auto">

        <?php // Si l'indice panier dans la session est bien définie => calcul de la somme des toutes les quantités demandées grâce à la fonction prédéfinie array_sum()
          if(isset($_SESSION['panier'])){
            $badge = "<span class='badge badge-info'>" . array_sum($_SESSION['panier']['quantite']) . "</span>";
          }
          else// SINON l'indice 'panier' dans la session n'est pas définie donc l'internaute n'a pas ajouté de produit dans le panier
          {
            $badge = "<span class='badge badge-info'>0</span>";
          }
        ?>

        <?php if(connect()): //accès @ mais non ADMIN?> 

          <li class="nav-item active">
            <a class="nav-link" href="<?= URL ?>profil.php">Mon Compte</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="<?= URL ?>boutique.php">Accédez à la boutique</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="<?= URL ?>panier.php">Votre Panier <?= $badge ?> </a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="<?= URL ?>connexion.php?action=deconnexion">Déconnexion</a>
          </li>

        <?php else: //Accès @ non connecté?>

          <li class="nav-item active">
            <a class="nav-link" href="<?= URL ?>inscription.php">Créez votre compte</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="<?= URL ?>connexion.php">Connectez-vous ICI !</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="<?= URL ?>boutique.php">Accédez à la boutique</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="<?= URL ?>panier.php">Votre Panier <?= $badge ?> </a>
          </li>
          

        <?php endif; ?>

        <?php if(adminConnect()): //Fait réf à la fonction dans fonctions.inc.php si 1=> Back Officez Sinon @ lambda?>

          <li class="nav-item dropdown active">
            <a class="nav-link dropdown-toggle" href="" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">BACK OFFICE</a>
            <div class="dropdown-menu" aria-labelledby="dropdown04">
              <a class="dropdown-item" href="<?= URL ?>admin/gestion_boutique.php">Gestion Boutique</a>
              <a class="dropdown-item" href="<?= URL ?>admin/gestion_commande.php">Gestion Commande</a>
              <a class="dropdown-item" href="<?= URL ?>admin/gestion_membre.php">Gestion Membre</a>
            </div>
          </li>

        <?php endif; ?>

        </ul>
    
            <a class="nav-link" href="<?= URL ?>contact.php">Contactez-nous</a>
            
            <a class="nav-link" href="http://facebook.fr" target="blank"><i class="fab fa-facebook-square"></i></a>
            <a class="nav-link" href="http://instagram.fr" target="blank"><i class="fab fa-instagram"></i></a>
            <a class="nav-link" href="http://twitter.fr" target="blank"><i class="fab fa-twitter-square"></i></a>
        
      </div>
      
    </nav>
    
    <main class="container-fluid" style="min-height:90vh;">