
<!DOCTYPE html>
<html lang="fr" ir="ltr">
    <head>
      <meta charset="utf-8">
      <link rel="stylesheet" href="#">
      <?php include("favicon.php"); ?>
      <link rel="stylesheet" href="<?=base_url('assets/style/header-footer.css')?>">
      <link rel="stylesheet" href="<?=base_url('assets/style/admin.css')?>">
      <title>VitVet | <?php if (isset($article))echo "Modifier un produit"; else echo "Ajouter un produit";?></title>
</head>
<!-- Header -->
<?php include("headerAdmin.php"); ?>
<!-- Fin Header -->
<body>
    <a class="backToTop" onclick="window.scrollTo({top: 0, behavior: 'smooth',})">↑</a>
    <div class="main">
        <div class="side">

        </div>
        <div class="listArticles">
            <h1 class="titre">ARTICLES</h1>

            <hr>
            <div class="Article">
                <form class="form" method="post" action="<?php if (isset($article)) echo site_url('admin/update'); else echo site_url('admin/add');?>" enctype="multipart/form-data">
                    <div>
                        <?php if (isset($article)) {?>
                            <input style="display:none" name="id" value="<?= $article->getId() ?>">
                        <?php } ?>
                        <div>
                            <p>Nom: </p>
                            <input class="inputText" name="nom" value="<?php if (isset($article))echo $article->getNom();?>">
                        </div>
                        <div>
                            <p>Prix:</p>
                            <input class="inputText" name="prix" value="<?php if (isset($article))echo $article->getPrix();?>">
                        </div>
                        <div>
                            <p>Quantite:</p>
                            <input class="inputText" name="quantite" value="<?php if (isset($article))echo $article->getQuantite();?>">
                        </div>
                        <div>
                            <p>Description:</p>
                            <input class="inputText" name="description" value="<?php if (isset($article))echo $article->getDescription();?>">
                        </div>
                    </div>
                    <div class="droite">
                        <div>
                            <p>Taille:</p>
                            <input class="inputText" name="taille" value="<?php if (isset($article))echo $article->getTaille();?>">
                        </div>
                        <div class="divCategory">
                            <p>Categorie:</p>
                            <select class="inputSelect" name="categorie" value="<?php if (isset($article))echo $article->getCategorie();?>">
                                <option <?php if (isset($article) && $article->getCategorie() == "tshirt") echo "selected"; ?>>tshirt</option>
                                <option <?php if (isset($article) && $article->getCategorie() == "pantalon") echo "selected"; ?>>pantalon</option>
                                <option <?php if (isset($article) && $article->getCategorie() == "chaussure") echo "selected"; ?>>chaussure</option>
                            </select>
                        </div>
                        <div>
                            <p>Image:</p>
                            <input class="inputFile" type="file" name="image" id="image" accept=".png,.webp,.jpg,.jpeg">
                        </div>
                        <div>
                            <p>Enregister:</p>
                            <input class="submit" type="submit" value="Enregistrer">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="divAjouter side">
            <a href="<?= site_url('admin') ?>">
                <input class="bouton gris" type="button" value="Voir tous les articles">
            </a>
            
        </div>
    </div>
</body>
<!-- footer -->
<?php include("footer.php"); ?>
<!-- fin footer -->
</html>
