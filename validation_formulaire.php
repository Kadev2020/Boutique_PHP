<?php

require_once('inc/init.inc.php');
require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');

?>
<h1 class="display-4 text-center my-5"> Votre message a bien été envoyé !! </h1>

<h3 class="text-center">Il sera traité dans les meilleurs délais !!</h3><br>



<?php if(!connect()):?>

<h4 class="text-center"> Cliquez ci-dessous pour vous connecter ou bien vous inscrire </h4>

    <div class="text-center">
        <a href="connexion.php" class="btn btn-info mt-5"> Identifiez-vous ICI</a>
    </div><br>

        <p class="text-center mt-5">OU BIEN</p><br>
        
    <div class="text-center"><a href="inscription.php" class="btn btn-success mt-5"> Inscrivez-vous ICI</a></div>
    
<?php else: ?>
<h4 class="text-center"> Cliquez ci-dessous pour revenir vers votre profil </h4>

<div class="text-center">
    <a href="profil.php" class="btn btn-success mt-5 "> Revenir vers mon profil </a>
</div>
<?php endif; ?>
<?php

require_once('inc/footer.inc.php');

