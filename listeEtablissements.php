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

// AFFICHER L'ENSEMBLE DES ÉTABLISSEMENTS
// CETTE PAGE CONTIENT UN TABLEAU CONSTITUÉ D'1 LIGNE D'EN-TÊTE ET D'1 LIGNE PAR
// ÉTABLISSEMENT

echo "
<TITLE> Accueil > Gestion des établissements </title>
<table width='70%' cellspacing='0' cellpadding='0' align='center' 
class='tabNonQuadrille'>
   <tr class='enTeteTabNonQuad'>
      <td colspan='4'>Etablissements</td>
   </tr>";
     
   $req=obtenirReqEtablissements();
   $rsEtab=$connexion->query("SELECT * FROM Etablissement ORDER BY id ASC");
   // BOUCLE SUR LES ÉTABLISSEMENTS

   while ($lgEtab=$rsEtab->fetch())
   {
      $id=$lgEtab['id'];
      $nom=$lgEtab['nom'];

      $nbOffre=$lgEtab["nombreChambresOffertes"];
               $nbOccup=obtenirNbOccup($connexion, $id);
      $nbOccup1=$nbOccup->fetchColumn();
      // Calcul du nombre de chambres libres dans l'établissement
      $nbChLib = $nbOffre - $nbOccup1;



      echo "
		<tr class='ligneTabNonQuad'>
         <td width='30%'>$nom</td>
         
         <td width='16%' align='center'> 
         <a href='detailEtablissement.php?action=demanderVoirEtab&amp;id=$id'>
         Voir détail</a></td>
         
         <td width='16%' align='center'> 
         <a href='modificationEtablissement.php?action=demanderModifEtab&amp;id=$id'>
         Modifier</a></td>";
         if ( $nbChLib == 0 ) // SI nb chambres libres = 0, afficher complet
         {
            echo"
            <td width='16%' align='center'>  
            Complet</td>";
         }
         else if($nbOccup1 == 0)   // Si 0 attributions, afficher supprimer
         {
            echo "
            <td width='16%' align='center'> 
            <a href='suppressionEtablissement.php?action=demanderSupprEtab&amp;id=$id'>
            Supprimer</a></td>";
         } // Affichage des attributions
         else
         {
            echo"<td width='16%' align='center'> 
            $nbOccup1 attributions</a></td>";
         }
   }
         // S'il existe déjà des attributions pour l'établissement, il faudra
         // d'abord les supprimer avant de pouvoir supprimer l'établissement
       
  
   echo "
   <tr class='ligneTabNonQuad'>
      <td colspan='4'><a href='creationEtablissement.php?action=demanderCreEtab'>
      Création d'un établissement</a ></td>
  </tr>
</table>";
?>