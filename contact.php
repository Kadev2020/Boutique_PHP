<?php

require_once('inc/init.inc.php');


if($_POST){

    foreach($_POST as $key => $value)
    {
        $_POST[$key] = htmlspecialchars($value);
    }

    $insert = $bdd -> prepare("INSERT INTO formulaire_contact (nom, prenom, tel, email, message) VALUES (:nom, :prenom, :tel, :email, :message)");
    $insert->bindValue(':nom', $_POST['nom'],PDO::PARAM_STR);
    $insert->bindValue(':prenom', $_POST['prenom'],PDO::PARAM_STR);
    $insert->bindValue(':tel', $_POST['tel'],PDO::PARAM_INT);
    $insert->bindValue(':email', $_POST['email'],PDO::PARAM_STR);
    $insert->bindValue(':message', $_POST['message'],PDO::PARAM_STR);
    $insert->execute();
    
    header("location: validation_formulaire.php");

}

require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');

?>
<h3 class="m-4">Formulaire de Contact</h3>

<form method="post" class="col-md-6  ml-8">

        <div class="form-group col-md-8">
            <label for="nom"><strong>Votre nom</strong> <small class="text-muted">(Champs obligatoire)</small> </label>
            <input type="text" class="form-control" id="nom" name="nom" value="" required>
        </div>
        
        <div class="form-group col-md-8">
            <label for="prenom"><strong>Votre prénom</strong> <small class="text-muted">(Champs obligatoire)</small></label>
            <input type="text" class="form-control" id="prenom" name="prenom" value="" required>
        </div>
        
        <div class="form-group col-md-8">
            <label for="tel"> <strong>Votre numéro de téléphone</strong> <small class="text-muted">(Champs obligatoire)</small></label>
            <input type="tel" class="form-control" id="tel" name="tel" value="" placeholder="Ex : 0612345678" pattern="[0-9]{10}" title="Exemple : 0612345678" required>
        </div>
        
        <div class="form-group col-md-8">
            <label for="mail"> <strong>Votre adresse email</strong> <small class="text-muted">(Champs obligatoire)</small></label>
            <input type="email" class="form-control" id="email" name="email" value="" placeholder="Ex : mail@mail.fr" required>
        </div>

        <div class="form-group col-md-8">
            <label for="message"> <strong class="">Votre Message</strong> <small class="text-muted">(Champs obligatoire)</small></label>
            <textarea class="form-control" id="message" name="message" rows="6" placeholder="400 caractères maximum" minlength="50" maxlength="400" required></textarea>
        </div>
        
        <button type="submit" class="btn btn-dark col-md-4 m-2">Envoyer</button>
</form>


        

<?php

require_once('inc/footer.inc.php');