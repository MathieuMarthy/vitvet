<!DOCTYPE html>
<html lang="fr" ir="ltr">
<head>
    <meta charset="utf-8">
    <?php include("favicon.php"); ?>
    <link rel="stylesheet" href="<?=base_url()?>assets/style/boutique.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/style/header-footer.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/style/cardArticle.css">
    <title>VitVet | Boutique</title>
</head>
<!-- Header -->
<?php include 'header.php'; ?>
<!-- Fin Header -->
<?php
if ($this->session->has_userdata("user")){
    $user = $this->session->userdata("user")['id'];
}
?>
<body>
    <a class="backToTop" onclick="window.scrollTo({top: 0, behavior: 'smooth',})">↑</a>

    <div class="corpsProduits">
        <h2>BOUTIQUE</h2>
        <form methode="get" action="<?= site_url("Boutique/recherche") ?>">
        <input class="barreDeRecherche" placeholder="Entrez votre recherche" type="search" name="recherche">
        </form>
        <?php include("afficheArticles.php"); ?> <!-- affiche tous les articles du tableau $articles -->
        </div>
    </div>
</body>
<!-- footer -->
<?php include("footer.php"); ?>
<!-- fin footer -->
</html>
