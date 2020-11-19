<?php

require_once('inc/init.inc.php');
if(!connect())
{
    header("location: connexion.php"); //si l'@ n'est pasconnecté il n'a rien à faire sur la page profil il est donc renvoyé vers la page connexion
}

require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');

// echo '<pre>'; print_r($_SESSION); echo '</pre>';

/*

Array
(
    [user] => Array
        (
            [id_membre] => 6
            [pseudo] => DDD
            [nom] => azdzad
            [prenom] => zadaz
            [email] => Azerty2020@gmail.com
            [civilite] => homme
            [ville] => zadazd
            [code_postal] => 00000
            [adresse] => azdza
            [statut] => 1
        )
)

*/
?>
<!-- 
    Exo : Faire en sorte d'afficher "Bonjour 'pseudo'" en passant par le fichier de l'utilisateur en HTML
-->
<h1 class="display-4 text-center my-4"> Bonjour <span class="text-info"> <?= $_SESSION['user']['pseudo']?></span></h1>

<!-- 
    Exo : Faire une mise en forme avec les données de l'utilisateur
-->



<!-- GREG -->

<div class="col-md-5 mx-auto card mb-3 shadow-lg">
  
  <div class="card-body">
    <h5 class="card-title text-center">Vos infos personnelles</h5><hr>
    
    <?php foreach($_SESSION['user'] as $key => $value): ?>

    <?php if($key != 'id_membre' && $key != 'statut'): ?>

        <p class="card-text"><strong><?= $key ?></strong> : <?=$value?></p>
    
    <?php endif; ?>

    <?php endforeach; ?>
    
    <a href="#" class="btn btn-primary">Modifier</a>
  </div>
</div>

<?php

require_once('inc/footer.inc.php');