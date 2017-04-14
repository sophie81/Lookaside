<?php
require_once('../inc/init.inc.php');

if (!userAdmin()) {
	header("location:../connexion.php");
}

$recup_commande = $pdo -> query('SELECT c.id_commande, c.id_membre, c.id_produit, p.prix, DATE_FORMAT(c.date_enregistrement, "%d/%m/%Y %H:%m") as date_enregistrement  FROM produit p, commande c WHERE c.id_produit = p.id_produit');
$commande = $recup_commande -> fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
	$resultat = $pdo -> prepare('SELECT c.id_commande, c.id_membre, c.id_produit, p.prix, DATE_FORMAT(c.date_enregistrement, "%d/%m/%Y %H:%m") as date_enregistrement  FROM produit p, commande c WHERE c.id_produit = p.id_produit');
	$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
	$resultat -> execute();

	if ($resultat -> rowCount() > 0) {
		$commande_actuel = $resultat -> fetch(PDO::FETCH_ASSOC);
	} 
}

$id_commande = (isset($commande_actuel)) ? $commande_actuel['id_commande'] : '';
$prix = (isset($commande_actuel)) ? $commande_actuel['prix'] : '';
$date_enregistrement = (isset($commande_actuel)) ? $commande_actuel['date_enregistrement'] : '';

require_once('../inc/header.inc.php');

?>

<h1>Gestion des commandes</h1>
<?php if(!empty($commande)): ?>
	<table border="1" class="table table-striped table-bordered table-hover">
		<tr>
			<?php for($i = 0; $i < $recup_commande -> columnCount(); $i++): ?>
				<?php $colonne = $recup_commande -> getColumnMeta($i); ?>
				<th><?= $colonne['name']; ?></th>
			<?php endfor; ?>
			<th colspan="2">Actions</th>
		</tr>
		
		
		<?php foreach ($commande as $indice => $valeur): ?>
			<tr>
				<?php foreach($valeur as $indice2 => $valeur2): ?>
					<?php if ($indice2 == 'id_membre'): ?>
						<?php $membre_val = getMembre($valeur2); ?>
						<td><?= $membre_val['id_membre']; ?> - <?= $membre_val['email']; ?></td>
					<?php elseif ($indice2 == 'id_produit'): ?>
						<?php $produit_val = getProduit($valeur2); $salle_val = getSalle($produit_val['id_salle']) ?>
						<td><?= $produit_val['id_salle']; ?> - <?= $salle_val['titre']; ?><br><?= $produit_val['date_arrivee']; ?> au <?= $produit_val['date_depart']; ?></td>
					<?php elseif ($indice2 == 'prix'): ?>
						<td><?= $valeur2 ?> €</td>
					<?php else: ?>
						<td><?= $valeur2 ?></td>
					<?php endif; ?>
				<?php endforeach; ?>
				<td><a href="gestion_commande.php?id=<?= $valeur['id_commande']; ?>"><i class="fa fa-search" aria-hidden="true"></i></a></td>
				<td><a href="supprimer_commande.php?id=<?= $valeur['id_commande']; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
			</tr>
		<?php endforeach; ?>
		
	</table>
<?php else: ?>
	<p>Aucune commande</p>
<?php endif; ?>

<?php if(isset($_GET['id'])): ?>

<h1>Détails commande</h1>
<div class="row">
	<div class="col-md-12">
		<ul class="list-group">
			<li class="list-group-item"><b>ID commande :</b> <span><?= $id_commande; ?></span></li>
			<li class="list-group-item">
				<?php foreach($commande_actuel as $indice_commande => $valeur_commande): ?>
					<?php if($indice_commande == 'id_membre'): ?>
						<?php $membre_val = getMembre($valeur_commande); ?>
						<span><b>ID membre&nbsp;:&nbsp;</b> <?= $membre_val['id_membre']; ?> <br>
						<b>Email membre&nbsp;:&nbsp;</b> <?= $membre_val['email']; ?></span>
					<?php endif; ?>
				<?php endforeach; ?>
			</li>
			<li class="list-group-item">
				<?php foreach($commande_actuel as $indice_salle => $valeur_salle): ?>
					<?php if($indice_salle == 'id_produit'): ?>
						<?php $salle_val = getSalle($valeur_salle); ?>
						<span><b>ID produit&nbsp;:&nbsp;</b> <?= $salle_val['id_salle']; ?> <br>
						<b>Titre de la salle&nbsp;:&nbsp;</b> <?= $salle_val['titre']; ?></span> <br>
					<?php endif; ?>
				<?php endforeach; ?>
				<?php foreach($commande_actuel as $indice_produit => $valeur_produit): ?>
					<?php if($indice_produit == 'id_produit'): ?>
						<?php $produit_val = getProduit($valeur_produit); ?>
						<span><b>Date d'arrivée&nbsp;:&nbsp;</b><?= $produit_val['date_arrivee']; ?></span> <br>
						<span><b>Date de départ&nbsp;:&nbsp;</b><?= $produit_val['date_depart']; ?></span>
					<?php endif; ?>
				<?php endforeach; ?>
			</li>
			<li class="list-group-item"><b>Prix :</b> <span><?= $prix; ?> €</span></li>
			<li class="list-group-item"><b>Date d'enregistrement :</b> <span><?= $date_enregistrement; ?></span></li>
		</ul>
	</div>
</div>
<?php endif; ?>

<?php require_once('../inc/footer.inc.php'); ?>