<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <link rel="stylesheet" href="<?=base_url()?>assets/style/connexion.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/style/header-footer.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/style/popup.css">
    <?php include("favicon.php"); ?>

    <meta charset="utf-8">
    <title>VitVet | Mot de passe oublié</title>
  </head>
  <!-- Header -->
<?php include("header.php"); ?>
<!-- Fin Header -->
<body background="<?=base_url()?>/assets/image/background-connexion.jpg">
    <a class="backToTop" onclick="window.scrollTo({top: 0, behavior: 'smooth',})">↑</a>
    <div class="boxConnexion">
        <div class="boxConnexionHaut">
            <h1>Mot de passe oublié</h1>
            <h3>Veuillez entrer votre identifiant et votre mail pour récuperer votre compte</h3>
        </div>
        <form method="post" action="<?= site_url('User/resetmotdepasse')?>" class="boxConnexionBas">
            <div class="boxConnexionBas">
                <div class="boxConnexionBasGaucheDroite boxConnexionBasGauche">
                    <label for="idConnexion">Identifiant*</label>
                    <input class="champtexte" placeholder="Entrez votre Identifiant" name="login" required>
                    <a href="<?= site_url('User/login')?>">
                        <input class="boutonCompte" type="button" value="Retour">
                    </a>
                </div>
                <div class="boxConnexionBasGaucheDroite boxConnexionBasDroite">
                    <label>Mail*</label>
                    <input class="champtexte" type="email" placeholder="Entrez votre mail" name="mail" required>
                    <input class="boutonCompte" type="submit" value="Réinitialiser le mot de passe">
                </div>
            </div>
        </form>
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
