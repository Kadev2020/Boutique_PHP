<?php

require_once('inc/init.inc.php');
require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');

?>

<h1 class="display-1 text-center my-5"> Félicitations !! </h1>

<h3 class="text-center">Votre commande a bien été prise en compte !!</h3><br>

<h4 class="text-center"> Ceci est votre numéro de commande <span class="text-success"><br><br><?= $_SESSION['num_cmd'] ?></h4>

<p class="text-center">
    <a href="profil.php" class="btn btn-success mt-5"> VOIR MES COMMANDES </a>
</p>


<?php

require_once('inc/footer.inc.php');