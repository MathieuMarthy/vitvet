<div class="ListeProduits">
    <?php foreach ($articles as $article): ?>
        <?php if ($article->getQuantite()>0) { ?> <!-- n'affiche que les produits encore disponible -->
            <div class="cardArticle">
                <div class="ImageProduitLien">
                    <a href="<?= site_url('Boutique/article/'.$article->getId()) ?>">
                        <img loading="lazy" class="ImageProduit" src="<?=base_url()?>assets/image/articles/<?= $article->getImage() ?>" alt="image vêtement">
                    </a>
                </div>
                <div class="cardArticleInformations">
                    <h3><?= $article->getNom() ?></h3>
                    <h3><?= $article->getPrix() ?>€</h3>
                    <?php if ($this->session->has_userdata("user")) { ?>
                        <input class="ajouterPanier" type="button" value="Ajouter au Panier" onclick="ajoutePanierBD(<?= $user ?>, <?= $article->getId() ?>)" >
                    <?php } else { ?>
                            <input class="ajouterPanier" type="button" value="Ajouter au Panier" onclick="ajoutPanierSession(<?= $article->getId() ?>)" >
                    <?php } ?>
                    </a>
                </div>
            </div>
        <?php }?>
    <?php endforeach ?>
</div>
<script type="text/javascript" src="<?=base_url('assets/js/requetes.js')?>" ></script>
