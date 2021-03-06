<?php
require_once('../inc/init.inc.php');

if (!userAdmin()) {
    header("location:../connexion.php");
}

if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
    $resultat = $pdo -> prepare("SELECT * FROM produit WHERE id_produit = :id");
    $resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    $resultat -> execute();

    if ($resultat -> rowCount() > 0) {
        $produit = $resultat -> fetch(PDO::FETCH_ASSOC);

        if($produit['etat'] == 'libre') {

            $resultat = $pdo->exec("DELETE FROM produit WHERE id_produit = $produit[id_produit]");

            if ($resultat != FALSE) {
                header('location:gestion_produit.php');
            }
        } else {
            header("location:gestion_produit.php");
        }

    } else {
        header("location:gestion_produit.php");
    }

} else {
    header("location:gestion_produit.php");
}