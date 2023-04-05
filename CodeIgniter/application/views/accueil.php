<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <?php include("favicon.php"); ?>
    <link rel="stylesheet" href="<?=base_url()?>assets/style/acceuil.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/style/header-footer.css">
    <link rel="stylesheet" href="<?=base_url('assets/style/cardArticle.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/style/popup.css')?>">
    <meta charset="utf-8">
    <title>VitVet | Accueil</title>
  </head>

<!-- Header -->
<?php 
include("header.php");
?>
<!-- Fin Header -->
<?php 
if ($this->session->has_userdata("user")){
    $user = $this->session->userdata("user")['id'];
}
?>
<body>
    <a class="backToTop" id="top-button">↑</a>

    <div class="corpsProduits">
        <h2>LES INCONTOURNABLES</h2>
        <?php include("afficheArticles.php");?>

    </div>
    <!-- footer -->
    <?php include("footer.php") ?>
    <!-- fin footer -->
  </body>
</html>
<script>
document.getElementById("top-button").addEventListener("click", function() {
  window.scrollTo({
    top: 0,
    behavior: "smooth"
  });
});
</script>
<?php if (isset($popupType, $popupMessage)) { ?>
    <script>
        const popupType = "<?= $popupType ?>"
        const popupMessage = "<?= $popupMessage ?>"
    </script>
<?php } ?>