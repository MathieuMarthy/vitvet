<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <link rel="stylesheet" href="<?=base_url()?>assets/style/connexion.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/style/header-footer.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/style/popup.css">
    <?php include("favicon.php"); ?>

    <meta charset="utf-8">
    <title>VitVet | Connexion</title>
  </head>
  <!-- Header -->
<?php include("header.php"); ?>
<!-- Fin Header -->
<body background="<?=base_url()?>/assets/image/background-connexion.jpg">
    <a class="backToTop" onclick="window.scrollTo({top: 0, behavior: 'smooth',})">↑</a>
    <div class="boxConnexion">
        <div class="boxConnexionHaut">
            <h1>Connexion</h1>
            <h3>Veuillez entrer votre identifiant et votre mot de passe pour vous connecter</h3>
        </div>
        <form method="post" action="<?= site_url('User/loginCheck')?>" class="boxConnexionBas">
            <div class="boxConnexionBas">
                <div class="boxConnexionBasGaucheDroite boxConnexionBasGauche">
                    <label for="idConnexion">Identifiant*</label>
                    <input class="champtexte" placeholder="Entrez votre Identifiant" id="idConnexion" name="id" required>
                    <div class="empty"> </div>
                    <a href="<?= site_url('User/register')?>">
                        <input class="boutonCompte" type="button" value="Créer un Compte">
                    </a>
                </div>
                <div class="boxConnexionBasGaucheDroite boxConnexionBasDroite">
                    <label for="mdpConnexion">Mot de Passe*</label>
                    <input class="champtexte" type="password" placeholder="Entrez votre mot de passe" id="mdpConnexion" name="password" required>
                    <a class="oubliemdp" href="<?=site_url("User/motdepasseoublie")?>">mot de passe oublié ?</a>
                    <input class="boutonCompte" type="submit" value="Se Connecter">
                </div>
            </div>
        </form>
    </div>
  </body>
<?php if (isset($popupType, $popupMessage)) { ?>
    <script>
        const popupType = "<?= $popupType ?>"
        const popupMessage = "<?= $popupMessage ?>"
    </script>
<?php } ?>
  <!-- footer -->
<?php include("footer.php"); ?>
<!-- fin footer -->
</html>
<script type="text/javascript" src="<?=base_url('assets/js/requetes.js')?>" ></script>
