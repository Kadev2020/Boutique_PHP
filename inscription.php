<?php

require_once('inc/init.inc.php');

if(connect())
{
    header("location: profil.php"); //si l'@ est déjà connecté il n'a rien à faire sur la page inscription il est donc renvoyé vers la page profil
}

// 2 - Contrôler en PHP que l'on réceptionne bien toutes les données saisies dans le formulaire
// echo '<pre>' ; print_r($_POST); echo '</pre>'; 

// extract($_POST); //extraction pour une gestion plus facile
if($_POST){

    // Bordure Rouge en cas d'erreur dans le formulaire
    $border = "border border-danger";

// 3 - Contrôler la validité du pseudo, si le pseudo est existant en BDD, alors on affiche un message d'erreur. Faire pareil pour le champs 'email'

    //PSEUDO
//=> $verifPseudo ici est un PDOStatement 
    $verifPseudo = $bdd->prepare("SELECT * FROM membre WHERE pseudo = :pseudo"); // ":pseudo" Secure contre tout XSS et injction SQL

    $verifPseudo -> bindValue('pseudo', $_POST['pseudo'], PDO::PARAM_STR); // remplissage de ':pseudo'
    $verifPseudo -> execute(); //=> permet d'executer la req


    //Si la req de selection a retourné au moins 1 résultat, cela veut dire que le pseudo est connu en BDD, alors on entre dans le IF et on affiche un message d'erreur à l'internaute
    
    if(empty($_POST['pseudo'])){

        $errorPseudo = "<p class='text-danger font-italic'> Merci de renseigner un pseudo </p>";

        $error = true;
    }
    elseif($verifPseudo->rowCount()){

        $errorPseudo = "<p class='text-danger font-italic'> Pseudo déjà utilisé</p>";

        $error = true;
    }

    //MAIL

    $verifMail = $bdd->prepare("SELECT * FROM membre WHERE email = :email");
    $verifMail -> bindValue('email', $_POST['email'], PDO::PARAM_STR);
    $verifMail -> execute();

    if(empty($_POST['email'])){

        $errorMail = "<p class='text-danger font-italic'> Merci de renseigner un email </p>";

        $error = true;
    }
    elseif($verifMail->rowCount()){

        $errorMail = "<p class='text-danger font-italic'> Email déjà existant</p>";

        $error = true;
    }

// 4 - Informer l'internaute si les mdp ne correspondent pas

    if($_POST['mdp'] != $_POST['confirm_mdp'])
    {
        $errorMdp = "<p class='text-danger font-italic'> => Les mots de passe renseignés ne sont pas identiques <= </p>";
        $error = true;
    }

    if(!isset($error))
    {
        // 5 - Gérer les failles XSS
        foreach($_POST as $key => $value)
        {
            $_POST[$key] = htmlspecialchars($value);
        }

        //Cryptage  du mot de passe
        // Les mdp ne sont jamais gardés en clair dans la BDD
        // password_hash() : fonction prédéfinie qui créée une cléz de hashage pour le mdp dans la BDD
        $_POST['mdp'] = password_hash($_POST['mdp'], PASSWORD_BCRYPT);

        // 6 - Si l'internaute a correctement remplie le formulaire, réaliser le traitement PHP + SQL permettant d'insérer le membre en BDD (req préparée | prepare() + bindValue())

        $insert = $bdd -> prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, ville, code_postal, adresse) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :ville, :code_postal, :adresse)");
        $insert->bindValue(':pseudo', $_POST['pseudo'],PDO::PARAM_STR);
        $insert->bindValue(':mdp', $_POST['mdp'],PDO::PARAM_STR);
        $insert->bindValue(':nom', $_POST['nom'],PDO::PARAM_STR);
        $insert->bindValue(':prenom', $_POST['prenom'],PDO::PARAM_STR);
        $insert->bindValue(':ville', $_POST['ville'],PDO::PARAM_STR);
        $insert->bindValue(':civilite', $_POST['civilite'],PDO::PARAM_INT);
        $insert->bindValue(':code_postal', $_POST['cp'],PDO::PARAM_INT);
        $insert->bindValue(':email', $_POST['email'],PDO::PARAM_STR);
        $insert->bindValue(':adresse', $_POST['adresse'],PDO::PARAM_STR);
        $insert->execute();

        // Après l'insertion du membre en BDD, on le redirige vers la page validation_inscription.php grâce à la fonction prédéfinie deahder()
        header("location: validation_inscription.php");
    }
}

require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');

?>

<!--  Nous sommes dans la balise <main></main> ICI

Exo : 
    1 - Réaliser un formulaire d'inscription correspondant à la table 'membre de la BDD 'boutique (sauf id_membre) et ajouter le champs 'confirmer mot de passe' (name="confirm_mdp")

    2 - Contrôler en PHP que l'on réceptionne bien toutes les données saisies dans le formulaire

    3 - Contrôler la validité du pseudo, si le pseudo est existant en BDD, alors on affiche un message d'erreur. Faire pareil pour le champs 'email'

    4 - Informe>r l'internaute si les mdp ne correspondent pas

    5 - Gérer les failles XSS

    6 - Si l'internaute a correctement remplie le formulaire, réaliser le traitement PHP + SQL permettant d'insérer le membre en BDD (req préparée | prepare() + bindValue())

-->

<h3 class="text-center m-4">Formulaire d'enregistrement</h3>

<form method="post" class="col-md-6 mx-auto">
        <div class="form-group">
            <label for="pseudo">Pseudo</label>

            <input type="text" class="form-control <?php if(isset($errorPseudo)) echo $border;?>" id="pseudo" name="pseudo" value="<?php if(isset($_POST['pseudo'])) echo $_POST['pseudo']; ?>">

            <?php if(isset($errorPseudo)) echo $errorPseudo;?>
        </div>
        
        <div class="form-group">
            <label for="email">Entrez une adresse mail</label>
            <input type="email" class="form-control <?php if(isset($errorMail)) echo $border;?>" id="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>">

            <?php if(isset($errorMail)) echo $errorMail;?>
        </div>

        <div class="form-group">
            <label for="mdp">Entrez un mot de passe</label>
            <input type="text" class="form-control" id="mdp" name="mdp">
        </div>
        <div class="form-group">
            <label for="confirm_mdp">Confirmez votre mot de passe</label>
            <input type="text" class="form-control <?php if(isset($errorMdp)) echo $border;?>" id="confirm_mdp" name="confirm_mdp">

            <?php if(isset($errorMdp)) echo $errorMdp;?>
        </div>
        <div class="form-group">
            <label for="civilite">Civilite</label>
            <select name="civilite" id="civilite">
                <option value="homme">Monsieur</option>
                <option value="femme">Madame</option>
            </select>
        </div>
        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom">
        </div>
        <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text" class="form-control" id="prenom" name="prenom">
        </div>
        <div class="form-group">
            <label for="adresse">Adresse</label>
            <input type="text" class="form-control" id="adresse" name="adresse">
        </div>
        <div class="form-group">
            <label for="cp">Code Postal</label>
            <input type="text" class="form-control" id="cp" name="cp">
        </div>
        <div class="form-group">
            <label for="ville">Ville</label>
            <input type="text" class="form-control" id="ville" name="ville">
        </div>   

        <button type="submit" class="btn btn-dark col-md-4 m-2">Envoyer</button>
</form>

<?php

require_once('inc/footer.inc.php');

