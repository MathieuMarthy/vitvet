<!DOCTYPE html>
<html lang="fr" ir="ltr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=base_url()?>assets/style/article.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/style/header-footer.css">
    <?php include("favicon.php"); ?>
    <script src="<?=base_url()?>assets/js/code.js"></script>
    <title>VitVet | Article</title>
</head>
<!-- Header -->
<?php 
include("header.php");
if ($this->session->has_userdata("user")){
    $user = $this->session->userdata("user")['id'];
}
?>
<!-- Fin Header -->
<body>
    <a class="backToTop" onclick="window.scrollTo({top: 0, behavior: 'smooth',})">↑</a>
    <div class="boxArticle">
        <div class="BoxCorpsGauche">
            <img class="imgArticle" src="<?=base_url()?>assets/image/articles/<?=$article->getImage()?>">
        </div>
        <div class="boxCorpsDroite">
            <h2><?=$article->getNom()?></h2>
            <div>
                <h3><?=$article->getPrix()?>€</h3>
                <h3>Taille</h3>
                <select class="inputQuantiteArticle", onchange="changePageArticle(this.value);">
                    <option value="<?=$article->getId()?>"> <?=$article->getTaille()?></option>
                    <?php foreach ($autres as $autre):?>
                        <?php if ($autre->getQuantite()>0 && $autre->getTaille()!=$article->getTaille()){?>
                            <option value="<?=$autre->getId()?>"><?=$autre->getTaille()?></option>
                        <?php }?>
                    <?php endforeach?>
                </select>
            </div>
            <label for="quantiteArticle">Quantité</label>
            <input class="inputQuantiteArticle" type="number" value="1" min="1" max="<?=$article->getQuantite()?>" id="quantiteArticle" name="dep">

            <?php if (!$this->session->has_userdata("user")) { ?>
                <input class="ajouterPanier" type="button" value="Ajouter au Panier" onclick="ajoutPanierSession(<?= $article->getId() ?>, document.getElementById('quantiteArticle').value)">
            <?php } else { ?>
                <input class="ajouterPanier" type="button" value="Ajouter au Panier" onclick="ajoutePanierBD(<?= $user ?>, <?= $article->getId() ?>, document.getElementById('quantiteArticle').value)">
            <?php } ?>

            <h3>Description</h3>
            <p class="descriptionArticle"><?=$article->getDescription()?></p>
        </div>
    </div>
</body>
<!-- footer -->
<?php include("footer.php"); ?>
<!-- fin footer -->
</html>
<script type="text/javascript" src="<?=base_url('assets/js/requetes.js')?>" ></script>