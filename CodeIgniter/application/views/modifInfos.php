<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <link rel="stylesheet" href="<?=base_url()?>assets/style/modifInfos.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/style/header-footer.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/style/popup.css">
    <?php include("favicon.php"); ?>
    <meta charset="utf-8">
    <title>VitVet | Modifier mes informations</title>
  </head>
    <!-- Header (+ page partagé entre user et admin donc il faut mettre le choix du header) -->
    <?php 
    if ($this->session->has_userdata("user")&&$this->session->userdata("user")["statut"]=="admin"){
    include("headerAdmin.php");
    }else{
    include("header.php");
    }
    ?>
    <!-- Fin Header -->
    <body background="<?=base_url()?>assets/image/backgroundInfos.jpg">
        <div class="boxCreerCompteTout">
            <div class="boxCreerCompte">
                <h1>Mon Compte</h1>
            </div>
            <div class="formulaire">
                <form class="formCompte" method="post" action="<?=site_url('User/updateAccount')?>">
                    <label class="nom" for="idNom">Nom* </label>
                    <input class="case_nom" type="text" name="nom" value="<?=$user->getNom()?>" required>
                    <label class="prenom" for="prenomCreer">Prénom* </label>
                    <input class="case_prenom" type="text" name="prenom" value="<?=$user->getPrenom()?>" required>
                    <label class="mot_de_passe" for="mdpCreer">Mot de passe</label>
                    <input class="case_mdp" type="password" placeholder="Entrez votre nouveau mot de passe" id="mdpCreer" name="password">
                    <label class="conf_mdp" for="mdpConfirmer">Confirmer le mot de passe</label>
                    <input class="case_conf" type="password" placeholder="Confirmez votre nouveau mot de passe" id="mdpCreer" name="password2">
                    <label class="creer_adresse" for="adresseCreer">Adresse*</label>
                    <input class="case_adresse" type="text" id="adresseCreer" name="adresse" value="<?=$user->getAdresse()?>" required>
                    <label class="departement_choix" for="departementCreer">Code Postal* </label>
                    <input class="nombre_dpt" type="number" id="departementCreer" min="01000" max="98000" name="dep" value="<?=$user->getDepartement()?>" required>
                    <a class="retour" href="<?=site_url('User/monCompte')?>">
                      <input class=" retour bouton" type="button" value="Annuler les modifications">
                    </a>
                    <input class=" envoi_creation bouton" type="submit" value="Confirmer les modifications">
                </form>
            </div>
        </div>
    </body>
  <!-- footer -->
<?php include("footer.php"); ?>
<!-- fin footer -->
</html>
<?php if (isset($popupType, $popupMessage)) { ?>
    <script>
        const popupType = "<?= $popupType ?>"
        const popupMessage = "<?= $popupMessage ?>"
    </script>
<?php } ?>
<script type="text/javascript" src="<?=base_url('assets/js/requetes.js')?>" ></script>