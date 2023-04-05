<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <link rel="stylesheet" href="<?=base_url()?>assets/style/creerUnCompte.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/style/header-footer.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/style/popup.css">
    <?php include("favicon.php"); ?>
    <meta charset="utf-8">
    <title>VitVet | Créer Compte</title>
  </head>
<!-- Header -->
<?php include("header.php"); ?>
<!-- Fin Header -->
  <body background="<?=base_url()?>assets/image/background-connexion.jpg">
    <a class="backToTop" onclick="window.scrollTo({top: 0, behavior: 'smooth',})">↑</a>
    <div class="boxCreerCompte">
        <div class="boxCreerCompteHaut">
            <h1>Créer un Compte <?php if (isset($admin)) echo 'Administrateur' ?></h1>
        </div>
        <div class="boxFormCompte">
            <form method="post" action="<?= site_url('User/createAccount')?>" class="formCreerCompte">
                <div class="grille">
                    <?php if(isset($admin)) { ?>
                        <input style="display:none" name="admin" value="true" >
                    <?php } ?>
                    <label class="id" for="idCreer">Identifiant*</label>
                    <input class="caseId" placeholder="Entrez votre Identifiant" id="idCreer" name="id" required>
                    <label class="mdp" for="mdpCreer">Mot de Passe*</label>
                    <input class="caseMdp" type="password" placeholder="Entrez votre mot de passe" id="mdpCreer" name="password" required>
                    <label class="nom" for="text">Nom*</label>
                    <input class="caseNom" type="text" placeholder="Entrez votre nom" id="ajoutNom" name="nom" required>
                    <label class="prenom" for="text">Prénom*</label>
                    <input class="casePrenom" type="text" placeholder="Entrez votre prénom" id="ajoutPrenom" name="prenom" required>
                    <label class="mail" for="emailCreer">E-mail*</label>
                    <input class="caseMail" type="email" placeholder="Entrez votre adresse e-mail" id="emailCreer" name="mail" required>
                    <label class="adresse" for="adresseCreer">Adresse*</label>
                    <input class="caseAdresse" type="text" placeholder="Entrez votre adresse" id="adresseCreer" name="adresse" required>
                    <label class="departement" for="departementCreer">Code Postal*</label>
                    <input class="choixDepartement" type="number" placeholder="Entrez votre code postal" id="departementCreer" min="01000" max="98000" name="dep" required>
                </div>
                <div class="bouton">
                    <a href="<?=site_url('User/login')?>">
                        <input class="envoi" type="button" value="Identifiez vous">
                    </a>
                    <input class="envoi" type="submit" value="Créer son compte">
                </div>
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