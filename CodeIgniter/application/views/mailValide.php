<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <link rel="stylesheet" href="<?=base_url()?>assets/style/connexion.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/style/header-footer.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/style/popup.css">
    <?php include("favicon.php"); ?>

    <meta charset="utf-8">
    <?php if ($verifie) { ?>
      <title>VitVet | Mail validé</title>
    <?php } else { ?>
      <title>VitVet | Mail en cours de validation</title>
    <?php } ?>
  </head>
  <!-- Header -->
<?php include("header.php"); ?>
<!-- Fin Header -->
<body background="<?=base_url()?>/assets/image/background-connexion.jpg">
    <a class="backToTop" onclick="window.scrollTo({top: 0, behavior: 'smooth',})">↑</a>
    <div class="boxConnexion">
        <div class="boxConnexionHaut">
            <?php if ($verifie) { ?>
                <h1>Mail validé</h1>
                <h3>Votre mail à bien été validé !</h3>
            <?php } else { ?>
                <h1>Validez votre mail</h1>
                <h3>Un mail vous à été envoyé pour valider votre compte</h3>
                <h3>Pour renvoyer un mail, entrez votre identifiant et appuyez sur le bouton</h3>
            <?php } ?>
        </div>
        <?php if ($verifie) { ?>
            <img class="image" src="<?= base_url('assets/image/valid.png') ?>">
        <?php }else{ ?>
          <form class="boxConnexionHaut" action="<?=site_url("User/resend")?>" method="post">
            <label for="log" class="">Identifiant*</label>
            <input class="champtexte resend" id="log" type="text" placeholder="Entrez votre identifiant" name="login" required>
            <input class="boutonCompte resendButton" type="submit" value="Renvoyer le mail">
          </form>
        <?php } ?>
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
