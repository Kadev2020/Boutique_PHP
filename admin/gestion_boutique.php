<?php

require_once('../inc/init.inc.php');

//Si l'@ n'est pas admin (statut : 0) => redirection vers connexion.php
if(!adminConnect())
{
    header('location: '. URL . 'connexion.php');
}

//SUPPRESSION DE PRODUIT
//On entre dans la condition IF seulement dans le cas où l'internaute a cliqué sur un lien suppression de produit et par conséquant à transmis dans l'URL les paramètres  'action==supression'

if(isset($_GET['action']) && $_GET['action'] == 'suppression')
{
    // echo "suppression produit";
    //EXO : réaliser le traitement SQL + PHP permettant de supprimer un produit avec une requête préparée (prepare()) en fonction de l'ID_PRODUIT transmis dans l'URL

    $d = $bdd->prepare("DELETE FROM produit WHERE id_produit = :id_produit");
    $d -> bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
    $d->execute();

    $_GET['action'] = 'affichage';// permet de redéfinir l'indice 'action' pour être redirigé vers l'affichage des produits

    // Message de validation de suppression
    $vd = "<p class='col-md-6 mx-auto bg-success text-center text-white p-3 rounded'> Le produit <strong>ID $_GET[id_produit]</strong> a bien été supprimé </p>";
}

if($_POST)// ENREGISTREMENT PRODUIT
{
    // TRAITEMENT DE LA PHOTO UPLOADE
    $photoBdd = '';
    //Si dans l'URL l'indice 'action' est bien définie et a pour valeur modif alors on entre dans la IF
    if(isset($_GET['action']) && $_GET['action'] =='modification')
    {
        //En cas  de modification l'url récupérer dans 'photo_actuelle' a été réinsérer en BDD
        $photoBdd = $_POST['photo_actuelle'];
    }
    if(!empty($_FILES['photo']['name']))
    {
        $nomPhoto = $_POST['reference'] . '-' . $_FILES['photo']['name']; // On renomme l'image en concaténant la référence que nous avons mis avec le nom de la photo
        // echo $nomPhoto;

        $photoBdd = URL . "photo/$nomPhoto"; // On définit l'URL de la photo qui sera enregistrée en BDD
        // echo $photoBdd;

        $photoDossier = RACINE_SITE . "photo/$nomPhoto"; // On définit le chemin physique du dossier cible sur le serveur pour nous permettre copier le fichier uploadé dans le bon dossier
        // echo $photoDossier;

        copy($_FILES['photo']['tmp_name'], $photoDossier); // copy() => fonction prédéfinie permettant de copier un fichier
        //arguments :
            // 1 . le nom temporaire de l'image accessible dans $_FILES
            // 2 . le chemin physique de la photo jusqu'au dossier photo sur le serveur
    }

    if(isset($_GET['action']) AND $_GET['action'] == 'ajout')//SI l'indice action est bien défini ET qu'il a pour valeur ajout  => on exec une requete d'insertion à la validation du formulaire
    {        
        // INSERTION BDD PRODUIT

    //EXO : réaliser le traitement SQL + PHP permettant d'insérer un produit dans la table 'produit' de la BDD à la validation du formulaire (PREPARE + INSERT + BINDVALUE)

        $data = $bdd->prepare("INSERT INTO produit (reference, categorie, titre, description, couleur, taille, public, photo, prix, stock) VALUES (:reference, :categorie, :titre, :description, :couleur, :taille, :public, :photo, :prix, :stock)");

        $_GET['action'] = 'affichage' ; //on redirige vers l'affichage des produits après l'inserstion

        $v = "<p class='col-md-6 mx-auto bg-success text-center text-white p-3 rounded'> Le produit <strong> $_POST[titre]</strong> référence <strong> $_POST[reference]</strong> a bien été ajouté !! </p>";

    }else{   //SINON 'action=modification' => alors requete de modification update
        // UPDATE BDD PRODUIT

        echo 'test';

        $data = $bdd->prepare("UPDATE produit SET reference = :reference, categorie = :categorie, titre = :titre, description = :description, couleur = :couleur, taille = :taille, public = :public, photo = :photo, prix = :prix, stock = :stock WHERE id_produit = :id_produit");

        $data->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);

        $_GET['action'] = 'affichage' ; //on redirige vers l'affichage des produits après l'insertion

        $v = "<p class='col-md-6 mx-auto bg-success text-center text-white p-3 rounded'> Le produit <strong> $_POST[titre]</strong> référence <strong> $_POST[reference]</strong> a bien été modifié !!</p>";

    }

    $data->bindValue(':reference', $_POST['reference'], PDO::PARAM_STR);
    $data->bindValue(':categorie', $_POST['categorie'], PDO::PARAM_STR);
    $data->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
    $data->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
    $data->bindValue(':couleur', $_POST['couleur'], PDO::PARAM_STR);
    $data->bindValue(':taille', $_POST['taille'], PDO::PARAM_STR);
    $data->bindValue(':public', $_POST['public'], PDO::PARAM_STR);
    $data->bindValue(':photo', $photoBdd, PDO::PARAM_STR);
    $data->bindValue(':prix', $_POST['prix'], PDO::PARAM_INT);
    $data->bindValue(':stock', $_POST['stock'], PDO::PARAM_INT);

    $data->execute();
}

require_once('../inc/header.inc.php');
require_once('../inc/nav.inc.php');
?>

<!--HTML-->

<!-- LIEN PRODUITS -->

<ul class="col-md-3 mx-auto list-group text-center mt-3">
    <li class="list-group-item bg-dark text-white">BACK OFFICE</li>
    <li class="list-group-item"><a href="?action=affichage" class="col-md-12 p-2 btn btn-info">AFFICHAGE PRODUITS</a></li>
    <li class="list-group-item"><a href="?action=ajout" class="col-md-12 p-2 btn btn-info">AJOUT PRODUIT</a></li>
</ul>

<?php

if(isset($_GET['action']) && $_GET['action'] == 'affichage')//Début de confition IF pour affichage
{

// echo '<pre>'; print_r($_POST); echo '</pre>';
// echo '<pre>'; print_r($_FILES); echo '</pre>';

// EXO : Afficher l'ensemble de la table 'produit' sous forme de tableau HTML avec le nom des champs comme entêtes du tableau. Prévoir 2 colonnes supplémentaires pour la modificatin et la suppression de chaque produit (SELECT + QUERY + FECTH)

echo '<h1 class="display-4 text-center my-4"> Affichage des produits </h1>';

//Affichage des messages utilisateurs
if(isset($vd)) echo $vd;
if(isset($v)) echo $v;

$r = $bdd->query("SELECT * FROM produit");

echo '<table class="table table-bordered text-center"><tr>';

for($i = 0; $i < $r->columnCount(); $i++)
{
    $c = $r -> getColumnMeta($i);
    // echo '<pre>'; print_r($c); echo '</pre>';
    echo "<th>" . strtoupper($c['name']) . "</th>";

}
    echo "<th>MODIFIER</th>";
    echo "<th>SUPPRIMER</th>";
    
echo '</tr>'; 
while($p = $r -> fetch(PDO::FETCH_ASSOC))
{
    // echo '<pre>'; print_r($p); echo '</pre>';
    echo'<tr>';
    
    foreach($p as $k => $v)
    {
        if($k == 'photo'){
            echo "<td><img src='$v' alt='' style='width: 100px'</td>";
        }else{
            echo "<td class='align-middle'>$v</td>";
        }
    }
        echo "<td class='align-middle'><a href='?action=modification&id_produit=$p[id_produit]' class ='btn btn-dark'><i class='far fa-edit'></i></a></td>";
        echo "<td class='align-middle'><a href='?action=suppression&id_produit=$p[id_produit]' class ='btn btn-danger' onclick='return(confirm(\"Êtes-vous sûre de vouloir supprimer ?\"));'><i class='fas fa-trash'></i>
        </a></td>";
    echo '</tr>';
}

echo '</table>';  

}//fin de condition IF pour affichage
?>

<?php if(isset($_GET['action']) && ($_GET['action'] == 'ajout' OR $_GET['action'] == 'modification')): 
    
    if(isset($_GET['id_produit']) && !empty($_GET['id_produit']))
    {
        //Si l'indice 'id_produit' est bien définie dans l'URL et que sa valeur est != de VIDE alors on entre dans la condition IF
        $r = $bdd -> prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
        $r->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
        $r->execute();

        if($r->rowCount())
        {
            $pa = $r->fetch(PDO::FETCH_ASSOC);
            // echo '<pre>'; print_r($pa); echo '</pre>';

        }else{ // Sinon l'id_produit de l'URL n'est pas connu en BDD, on redirige vers l'affichage des produits 

            // header('location: ' . URL . 'admin/gestion_boutique.php?action=affichage');
        }
    }
    elseif($_GET['action'] == 'modification' && (!isset($_GET['id_produit']) OR empty($_GET['id_produit']))){// Sinon l'id_produit de l'URL n'est pas définie ou sa valeur est vide on entre dans la condition else => renvoi vers la page affichage

        // header('location: ' . URL . 'admin/gestion_boutique.php?action=affichage');
    }
    $reference = (isset($pa['reference'])) ? $pa['reference'] : ''; //Condition ternaire
    $categorie = (isset($pa['categorie'])) ? $pa['categorie'] : '';
    $titre = (isset($pa['titre'])) ? $pa['titre'] : '';
    $description = (isset($pa['description'])) ? $pa['description'] : '';
    $couleur = (isset($pa['couleur'])) ? $pa['couleur'] : '';
    $taille = (isset($pa['taille'])) ? $pa['taille'] : '';
    $public = (isset($pa['public'])) ? $pa['public'] : '';
    $photo = (isset($pa['photo'])) ? $pa['photo'] : '';
    $prix = (isset($pa['prix'])) ? $pa['prix'] : '';
    $stock = (isset($pa['stock'])) ? $pa['stock'] : '';
    
?>

<h3 class="text-center m-4"><?= strtoupper($_GET['action'])  //($_GET['action']) => Permet d'aumatiquement injecter soit'Ajout' ou 'Modification selon le bouton cliqué?> PRODUIT</h3>

<form method="post" class="col-md-6 mx-auto" enctype="multipart/form-data"> 
<!-- enctype="multipart/form-data" => Permet de récupérer les informations des photos ou fichier uploadés-->
        <div class="form-group">
            <label for="reference">Référence</label>
            <input type="text" class="form-control" id="reference" name="reference" value="<?=$reference?>">
        </div>

        <div class="form-group">
            <label for="categorie">Catégorie</label>
            <select name="categorie" id="categorie" value="<?=$categorie?>">
                <option value="jean" <?php if($categorie == 'jean') echo 'selected'?>>Jeans</option>
                <option value="t-shirt" <?php if($categorie == 't-shirt') echo 'selected'?>>T-shirts</option>
                <option value="chemise" <?php if($categorie == 'chemise') echo 'selected'?>>Chemises</option>
                <option value="robe" <?php if($categorie == 'robe') echo 'selected'?>>Robes</option>
            </select>
        </div>

        <div class="form-group">
            <label for="titre">Titre</label>
            <input type="text" class="form-control" id="titre" name="titre" value="<?=$titre?>">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="5"><?=$description?></textarea> <!-- Si 'text-area' => mettre la variable entre les balises mais pas dans une 'value' -->
        </div>

        <div class="form-group">
            <label for="couleur">Couleur</label>
            <input type="text" class="form-control " id="couleur" name="couleur" value="<?=$couleur?>">
        </div>

        <div class="form-group">
            <label for="taille">Taille</label>
            <input type="text" class="form-control " id="taille" name="taille" value="<?=$taille?>">
        </div>

        <div class="form-group">
            <label for="public">Public cible</label>
            <select name="public" id="public" value="<?=$public?>">
                <option value="homme" <?php if($public == 'homme') echo 'selected'?>>Homme</option>
                <option value="femme" <?php if($public == 'femme') echo 'selected'?>>Femme</option>
                <option value="mixte" <?php if($public == 'mixte') echo 'selected'?>>Mixte</option>
            </select>
        </div>

        <!-- Un champ de type 'file' ne pas avoir d'attribue 'value', c'est pourquoi nous définissons un champ de type 'hidden' ci-dessous afin de récupérer l'URL de la photo en cas de modification -->

        <div class="form-group">
            <label for="photo">Photo</label>
            <input type="file" class="form-control-file" id="photo" name="photo" value="<?=$photo?>">
        </div>

                    <!-- Hidden => permet de récup l'URL de l'image pour la renvoyer dans la BDD si modification sans modif de l'image-->
        <input type="hidden" id="photo_actuelle" name="photo_actuelle" value="<?= $photo ?>">
        <!-- Affichage de la photo actuelle du produit en cas de modification -->
        <?php if(!empty($photo)): ?>
            
            <div class="text-center">
                <em> Pour changer d'image </em>
                <img src="<?= $photo ?>" alt="<?= $titre ?>" style="width: 200px;">
            </div>

        <?php endif; ?>

        <div class="form-group">
            <label for="prix">Prix</label>
            <input type="text" class="form-control" id="prix" name="prix" value="<?=$prix?>">
        </div>

        <div class="form-group">
            <label for="stock">Stock</label>
            <input type="text" class="form-control" id="stock" name="stock" value="<?=$stock?>">
        </div><br>
        
        <div class="text-center">
            <button type="submit" class="btn btn-dark"> <?= strtoupper($_GET['action'])?> PRODUIT</button>
        </div>
</form>
<br><br><br>
<?php
endif;
require_once('../inc/footer.inc.php');

