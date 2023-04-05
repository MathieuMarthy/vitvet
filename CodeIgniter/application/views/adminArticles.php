
<!DOCTYPE html>
<html lang="fr" ir="ltr">
    <head>
      <meta charset="utf-8">
      <link rel="stylesheet" href="#">
      <?php include("favicon.php"); ?>
      <link rel="stylesheet" href="<?=base_url()?>assets/style/header-footer.css">
      <link rel="stylesheet" href="<?=base_url()?>assets/style/admin.css">
      <title>VitVet | Admin Produits</title>
</head>
<!-- Header -->
<?php include("headerAdmin.php"); ?>
<!-- Fin Header -->
<script>
    function deleteArticle(infos) {
        let text = "Voulez-vous vraiment supprimer" +
            "\nid: " + infos[0] +
            "\nnom: " + infos[1] +
            "\nprix: " + infos[2] +
            "\nquantite: " + infos[3] +
            "\ncategorie: " + infos[4] +
            "\ntaille: " + infos[5];

        if (confirm(text) == true) {
            supprimerArticleAdmin(infos[0]);
        }
    }
</script>

<body>
    <a class="backToTop" onclick="window.scrollTo({top: 0, behavior: 'smooth',})">↑</a>
    <div class="main">
        <div class="side">

        </div>
        <div class="listArticles">
            <h1 class="titre"><?= count($articles) ?> ARTICLES</h1>

            <?php foreach($articles as $article): ?>
                <hr>
                <div class="Article">
                    <img loading="lazy" src="<?= base_url('assets/image/articles/'.$article->getImage()) ?>">
                    <div class="ArticleInfo">
                        <div class="ArticleInfoHaut">
                            <div class="ArticleInfoTitrePrix">
                                <h3><?= $article->getNom() ?></h3>
                                <p><?= $article->getPrix() ?>€</p>
                            </div>
                        </div>
                        <p class="ArticleInfoDescription"><?= $article->getDescription() ?></p>
                        <div class="ArticleInfoBas">
                            <div class="ArticleInfoInfo">
                                <p>id: <?= $article->getId() ?></p>
                                <p>quantite: <?= $article->getQuantite() ?></p>
                                <p>taille: <?= $article->getTaille() ?></p>
                                <p>categorie: <?= $article->getCategorie() ?></p>
                            </div>
                            <div class="ArticleInfoBoutons">
                                <a href="<?= site_url('admin/updateArticle/'.$article->getId()) ?>">
                                    <input class="bouton" type="button" value="Modifier">
                                </a>
                                <button class="bouton" onclick="deleteArticle(['<?= $article->getId() ?>', '<?= $article->getNom() ?>', '<?= $article->getPrix() ?>', '<?= $article->getQuantite() ?>', '<?= $article->getCategorie() ?>', '<?= $article->getTaille() ?>', '<?= $article->getImage() ?>'])">Supprimer</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
        <div class="divAjouter side">
            <a href="<?= site_url('admin/ajoutArticle') ?>">
                <input class="fixed bouton gris" type="button" value="Ajouter un article">
            </a>
            <form method="get" action="<?= site_url("Admin/rechercheArticle")?>">
            <input class="searchBar" name="recherche" placeholder="Rechercher un article">
            </form>
        </div>
    </div>
</body>
<!-- footer -->
<?php include("footer.php"); ?>
<!-- fin footer -->
</html>
<script type="text/javascript" src="<?=base_url('assets/js/requetes.js')?>" ></script>
