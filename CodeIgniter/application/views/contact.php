<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <link rel="stylesheet" href="<?=base_url()?>assets/style/contact.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/style/header-footer.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/style/popup.css">
    <?php include("favicon.php"); ?>
    <meta charset="utf-8">
    <title>VitVet | Contact</title>
  </head>
<!-- Header -->
<?php include("header.php"); ?>
<!-- Fin Header -->
<body background="<?=base_url()?>/assets/image/backContact.jpg">
  <a class="backToTop" onclick="window.scrollTo({top: 0, behavior: 'smooth',})">↑</a>
  <div class="boxContactTout">
    <div class="boxContact">
      <h1>Contact</h1>
    </div>
      <div class="boxForm">
          <h2>Faites nous un retour</h2>
          <form method="post" action="<?= site_url('Contact/sendMessage') ?>" class="formContact">
            <label class="prenom" for="prenomContact">Prenom*</label>
            <input class="casePrenom" type="text" id="prenomContact" name="prenom" value="<?=$prenom?>" required>
            <label class="nom" for="nomContact">Nom*</label>
            <input class="caseNom" type="text" id="nomContact" name="nom" value="<?=$nom?>" required>
            <label class="email" for="emailContact">E-mail*</label>
            <input class="caseEmail" type="email" id="emailContact" name="mail"  value="<?=$mail?>"required>
            <label class="message" for="messageContact">Message*</label>
            <textarea class="caseMessage" type="textarea" name="message" id="messageContact" required></textarea>
            <input class="envoi" type="submit" value="Envoyer">
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
