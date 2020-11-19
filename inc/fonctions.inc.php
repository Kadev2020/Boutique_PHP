<?php



// FONCTION INTERNAUTE CONNECTé

function connect()//cette fonction permet de savoir l'user est connecté ou non au site
{
    if(!isset($_SESSION['user']))// si indice user dans la session est non définie l'internaute n'est pas passé par la page connexion (c'est dans cette page que l'on créé l'indice 'user dans la session), cela veut donc dire que l'internaute n'est pas connecté et ne peut pas être inscrit sur le site.
    {
        return false;
    }else{ //SINON, l'indice 'user' est bien défini dans la session, donc l'internaute est bien connecté
        return true;
    }
}

// FONCTION INTERNAUTE ADMIN

function adminConnect()
{
    if(connect() && $_SESSION['user']['statut'] == 1) // Si l'@ est bien connecté est que l'indice statut = 1 => alors @ == ADMIN du site (return true)
    {
        return true;
        
    }else{

        return false;

    }
}

// FONCTION CREATION DU PANIER DANS LA SESSION
    //Les données du panier ne sont jamais conservés en BDD, beaucoup de panier n'aboutissent jamais => stockage des informations des informations du panier dans le fichier de session de l'@ 
    // Dans la session, nous définissons différents tableau ARRAY qui permettront de stocker par exemple toute les références des produits ajoutés au panier dans un ARRAY
function creationPanier(){
    if(!isset($_SESSION['panier'])) //SI l'indice 'panier' dans la session n'est pas définie alors on l'a créée
    {
        $_SESSION['panier'] = array(); //création d'un tableau ARRAY dans la session à l'indice 'panier'
        $_SESSION['panier']['id_produit'] = array();
        $_SESSION['panier']['photo'] = array();
        $_SESSION['panier']['reference'] = array();
        $_SESSION['panier']['titre'] = array();
        $_SESSION['panier']['quantite'] = array();
        $_SESSION['panier']['prix'] = array();
    }
}

//FONCTION AJOUTER PRODUIT DANS LA SESSION
    //Les param définis dans la F$ (fonction) permettront de receptionner les infos du produit ajouté dans le panier afin de stocker chaque donnée  dans les différents ARRAY
function ajoutPanier($id_produit, $photo, $reference, $titre, $quantite, $prix)
{
    creationPanier();

    $positionProduit = array_search($id_produit, $_SESSION['panier']['id_produit']); // Retourne l'indice numérique qui a été créé
    if ($positionProduit !== false) // SI $id_poduit est trouvé dans $_SESSION['panier']['id_produit'] on rentre ICI
    {
        $_SESSION['panier']['quantite'][$positionProduit] += $quantite ;

    }else{//SINON création des éléments pour l'id_produit qui n'avait pas été au préalable

    // Les crochets vide [] permettent de générer des indices numériques dans le ARRAY 
    // => $_SESSION['panier']['id_produit'][0]
    // => $_SESSION['panier']['id_produit'][1]
    // => $_SESSION['panier']['id_produit'][3] ETC.
    $_SESSION['panier']['id_produit'][] = $id_produit;
    $_SESSION['panier']['photo'][] = $photo;
    $_SESSION['panier']['reference'][] = $reference;
    $_SESSION['panier']['titre'][] = $titre;
    $_SESSION['panier']['quantite'][] = $quantite;
    $_SESSION['panier']['prix'][] = $prix; 
    }
}

// FONCTION MONTANT TOTAL PANIER
function montantTotal(){
    $total=0;
    for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
    {
        $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
    }
    return round($total, 2);
}

//FONCITON SUPPRESSION PRODUIT DU PANIER
function suppProduit($id_produit)
{
    $positionProduit = array_search($id_produit, $_SESSION['panier']['id_produit']);

    if($positionProduit !== false)

    {
        //On transmet à la fonction prédéfinie array_splice() l'id_produit du produit en rupture de stock 
        //array_search() retourne l'indice du tableau ARRAY auquel se trouve l'id_produit à supprimer

        array_splice($_SESSION['panier']['id_produit'], $positionProduit, 1); // 1 supprimer 1 élément (une ligne)du tableau
        array_splice($_SESSION['panier']['photo'], $positionProduit, 1); // 1 supprimer 1 élément (une ligne)du tableau
        array_splice($_SESSION['panier']['reference'], $positionProduit, 1); // 1 supprimer 1 élément (une ligne)du tableau
        array_splice($_SESSION['panier']['titre'], $positionProduit, 1); // 1 supprimer 1 élément (une ligne)du tableau
        array_splice($_SESSION['panier']['quantite'], $positionProduit, 1); // 1 supprimer 1 élément (une ligne)du tableau
        array_splice($_SESSION['panier']['prix'], $positionProduit, 1); // 1 supprimer 1 élément (une ligne)du tableau
    }
}


