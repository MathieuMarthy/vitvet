<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <?php include("favicon.php"); ?>
        <link rel="stylesheet" href="<?=base_url()?>assets/style/header-footer.css">
        <link rel="stylesheet" href="<?=base_url('assets/style/commandes.css')?>">
        <link rel="stylesheet" href="<?=base_url('assets/style/popup.css')?>">
        <meta charset="utf-8">
        <title>VitVet | Mes Commandes</title>
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
        <a class="backToTop" onclick="window.scrollTo({top: 0, behavior: 'smooth',})">↑</a>
        <div class="main">
            <div class="side">

            </div>
            <div class="listArticles">
                <div class="headCommande">
                    <h1 class="titre"><?= count($commandes) ?> COMMANDE<?php if (count($commandes) >1 ) {echo "S";} ?></h1>
                    <a href="<?= site_url('user/monCompte') ?>">
                        <input class="bouton monCompte" type="button" value="Mon Compte">
                    </a>
                </div>
                <?php if (count($commandes) == 0) { ?>
                <hr>
                    <h4 class="pasCommande">Vous n'avez pas encore passé de commande</h4>
                <?php } ?>
                <?php foreach($commandes as $commande): ?>

                    <hr>
                    <div class="Article">
                        <div class="ArticleInfo">
                            <div class="ArticleInfoHaut">
                                <div class="ArticleInfoTitrePrix">
                                    <h2>Commande du <?= $commande["date"] ?></h2>
                                    <p>Montant Total : <?= $commande["prix"] ?> €</p>
                                    <p>Nombre d'articles : <?= $commande["quantite"] ?></p>
                                    <p>Adresse de Livraison : <?= $commande["adresse"] ?></p>
                                    <?php if ($commande["statut"] == 1) {?>
                                        <p>Statut : En cours de livraison</p>
                                    <?php }else{ ?>
                                        <p>Statut : Livraison effectuée avec succès</p>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="ArticleInfoBas">
                                <div class="ArticleInfoBoutons">
                                    <a href="<?= site_url('user/commande/'.$commande['dateSql']) ?>">
                                        <input class="bouton" type="button" value="Détail de la Commande">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </body>
    <?php include("footer.php") ?>
</html>
<?php if (isset($popupType, $popupMessage)) { ?>
    <script>
        const popupType = "<?= $popupType ?>"
        const popupMessage = "<?= $popupMessage ?>"
    </script>
<?php } ?>
<script type="text/javascript" src="<?=base_url('assets/js/requetes.js')?>" ></script>
