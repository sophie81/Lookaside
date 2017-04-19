<?php

require_once('inc/init.inc.php');

if($_POST){

	if(empty($_POST['commentaire'])){
		echo '<div class="erreur">Veuillez renseigner un commentaire !</div>';
		$msg = 1;
	} elseif (empty($_POST['radio'])) {
		echo '<div class="erreur">Veuillez renseigner une note !</div>';
		$msg = 1;
	}
	
	if(empty($msg)){ // Tout est OK ! Si $msg est vide cela signifie que nous sommes passés dans aucun message d'erreur. 

		$checked_arr = $_POST['radio'];
		foreach ($checked_arr as $check){
			$test = $check;
		}


		$resultat = $pdo -> prepare("INSERT INTO avis (id_membre, id_salle, commentaire, note, date_enregistrement) VALUES (:id_membre, :id_salle, :commentaire, $test, NOW())");

		//STR
		$resultat -> bindParam(':id_membre', $_POST['id_membre'], PDO::PARAM_INT);
		$resultat -> bindParam(':id_salle', $_POST['id_salle'], PDO::PARAM_INT);
		$resultat -> bindParam(':commentaire', $_POST['commentaire'], PDO::PARAM_STR);
		
		if($resultat -> execute()){
			header('location:index.php');
		}
	}
}



?>