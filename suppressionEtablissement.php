<?php 

include("_debut.inc.php");
include("_gestionBase.inc.php"); 
include("_controlesEtGestionErreurs.inc.php");

// CONNEXION AU SERVEUR MYSQL PUIS SÉLECTION DE LA BASE DE DONNÉES festival

$connexion=connect();
if (!$connexion)
{
   ajouterErreur("Echec de la connexion au serveur MySql");
   afficherErreurs();
   exit();
}
if (!selectBase($connexion))
{
   ajouterErreur("La base de données festival est inexistante ou non accessible");
   afficherErreurs();
   exit();
}

// SUPPRIMER UN ÉTABLISSEMENT 

$id=$_REQUEST['id'];  

$lgEtab=obtenirDetailEtablissement($connexion, $id);
$nom=$lgEtab['nom'];

// Cas 1ère étape (on vient de listeEtablissements.php)

if ($_REQUEST['action']=='demanderSupprEtab')    
{
   $rsEtab=$connexion->query("SELECT * from Etablissement where id='$id'");
   while($lgEtab=$rsEtab->fetch())
   {
      $nom=$lgEtab['nom'];
   echo "
   <TITLE> Accueil > Gestion des établissements > Supprimer </TITLE>
   <br><center><h5>Souhaitez-vous vraiment supprimer l'établissement $nom ? 
   <br><br>
   <a href='suppressionEtablissement.php?action=validerSupprEtab&amp;id=$id'>
   Oui</a>&nbsp; &nbsp; &nbsp; &nbsp;
   <a href='listeEtablissements.php?'>Non</a></h5></center>";
}
}

// Cas 2ème étape (on vient de suppressionEtablissement.php)

else
{
   $rsEtab=$connexion->query("SELECT * from Etablissement where id='$id'");
   while($lgEtab=$rsEtab->fetch())
   {
      $nom=$lgEtab['nom'];
      $req=$connexion->query("DELETE from Etablissement where id='$id'");
   echo "
   <br><br><center><h5>L'établissement $nom a été supprimé</h5>
   <a href='listeEtablissements.php?'>Retour</a></center>";
}

}

?>
