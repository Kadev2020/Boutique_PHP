<?php

require_once('inc/init.inc.php');

if(isset($_GET['action']) && $_GET['action'] == 'deconnexion')
// si action = déconnexion redirection vers location -> connexion.php
{
    // session_destroy(); => si on veut totalement supprimer la session (panier etc.)
    unset($_SESSION['user']); // supprime l'indice user dans la session (laisse tout le reste : panier etc.)
    header("location: connexion.php");//permet de supprimer action=deconnexion dans l'url
}

if(connect())
{
    header("location: profil.php"); //si l'@ est déjà connecté il n'a rien à faire sur la page connexion il est donc renvoyé vers la page profil
}

// echo '<pre>' ; print_r($_POST); echo '</pre>'; 

if($_POST){
    $data = $bdd -> prepare("SELECT * FROM membre WHERE pseudo = :pseudo OR email = :email");
    $data -> bindValue(':pseudo', $_POST['pseudo_email'], PDO::PARAM_STR);
    $data -> bindValue(':email', $_POST['pseudo_email'], PDO::PARAM_STR);
    $data -> execute() ;

    if($data->rowCount()){ // veif email ou pseudo existant en bdd
        // echo 'pseudo ou email existant en BDD';

        $user = $data -> fetch(PDO::FETCH_ASSOC);
        // echo '<pre>'; print_r($user); echo '</pre>';

        //CONTROLE MDP NON CHIFRRé
        // if($_POST['password'] == $user['mdp'])
        // {
        //     echo 'MDP Ok';

        // }else{

        //     // echo 'Erreur MDP';
        //     $error = "<p class='col-md-4 mx-auto bg-danger text-white text-center p-3 rounded'> Identifiants ou mot de passe erronés </p>";
        // }

        //CONTROLE MDP CRYPTé
        if(password_verify($_POST['password'], $user['mdp'])) // permet de comparer une clé de hashage à une chaine de caractere
        {
            // echo 'MDP Ok';
            // si nous entrons dans cette condition, l'intenaute a correctement rempli le formulaire de connexion
            foreach($user as $key => $value){//ici on passe en revue les infos user (sauf mdp)
        
                if($key != 'mdp'){
                    $_SESSION['user'][$key] = $value;
                }
            }
            // On créé dans la session un indice 'user' contenant un tab ARRAY avec toutes les données de l'utilisateur . Cela permet d'identifier l'utilisateur et de l'autoriser à continuer à naviguer tout en restant connecté
            // echo '<pre>'; print_r($_SESSION); echo '</pre>';

            //une fois l'internaute connecté on redirige vers page profil

            header('location: profil.php');


        }else{

            // echo 'Erreur MDP';
            $error = "<p class='col-md-4 mx-auto bg-danger text-white text-center p-3 rounded'> Identifiants ou mot de passe erronés </p>";
        }

    }else{
        // echo 'pseudo ou email inexistant';
        $error = "<p class='col-md-4 mx-auto bg-danger text-white text-center p-3 rounded'> Identifiants ou mot de passe erronés </p>";
    }
}

require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');

?>

<h1 class="display-4 text-center my-4"> Identifiez-vous</h1>

<?php if(isset($error)) echo $error;?> 

<form method="post" class="col-md-3 mx-auto">
        <div class="form-group">
            <label for="pseudo_email">Entrez votre pseudo ou email</label>
            <input type="text" class="form-control" id="pseudo_email" name="pseudo_email" value="<?php if(isset($_POST['pseudo_email'])) echo $_POST['pseudo_email']; ?>">
        </div>
        <div class="form-group">
            <label for="password">Mot de Passe</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="text-center">
        <button type="submit" class="btn btn-dark col-md-4">Envoyer</button>
        </div>

<?php

require_once('inc/footer.inc.php');