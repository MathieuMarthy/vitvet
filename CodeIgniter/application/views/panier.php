<!DOCTYPE html>
<html lang="fr" ir="ltr">
    <head>
      <meta charset="utf-8">
      <?php include("favicon.php"); ?>
      <link rel="stylesheet" href="<?=base_url()?>assets/style/header-footer.css">
      <link rel="stylesheet" href="<?=base_url()?>assets/style/panier.css">
      <link rel="stylesheet" href="<?=base_url()?>assets/style/popup.css">
      <title>VitVet | Panier</title>
</head>
<!-- Header -->
<?php 
include("header.php");
// ######### $user est l'id du user #############
$connecte = $this->session->has_userdata("user");
if ($connecte){
    $user = $this->session->userdata("user")['id'];
}
?>
<!-- Fin Header -->
<body>
    <a class="backToTop" onclick="window.scrollTo({top: 0, behavior: 'smooth',})">↑</a>

    <div class="corpsPanier">

        <div class="panier">
            <div class="ligne1">
                <h2>MON PANIER - <?= $quantite ?> ARTICLE<?php if ($quantite > 1) {
                       echo "S";} if (count($articles) > 1){ ?></h2>
                <?php if ($connecte) { ?>
                    <button class="boutonTousSuppr" onclick="supprimerPanierDuUser(<?= $user ?>)"> <p>Supprimer le panier</p> <span class="material-symbols-outlined"> close </span></button>
                <?php } else { ?>
                    <button class="boutonTousSuppr" onclick="supprimerPanierDeSession()"> <p>Supprimer le panier</p>  <span class="material-symbols-outlined"> close </span></button>
                <?php }
                   } ?>
            </div>
            <?php if ($quantite == 0) { ?>
                <hr>
                <h4 class="emptyCart">Votre panier est vide</h4>
            <?php } ?>
            <!-- boucle -->
            <!-- pas de foreach car on veut récupéré les valeurs de articles et de panier (pour la quantite) -->
            <?php for ($i = 0; $i < count($articles); ++$i): ?> 
                <hr>
                <div class="Article">
                <img loading="lazy" class="ImageProduit" src="<?=base_url()?>assets/image/articles/<?=$articles[$i]->getImage()?>" alt="image vêtement">
                    <div class="ArticleInfo">
                        <div class="ArticleInfoHaut">
                            <div class="ArticleInfoTitrePrix">
                                <p><?=$articles[$i]->getNom()?> - <?= $articles[$i]->getTaille() ?></p>
                                <p><?=$articles[$i]->getPrix()*$panier[$i]->getQuantiteCommande()?>€</p>
                            </div>                            
                            <?php if ($connecte) { ?>
                                <button onclick="supprimerArticlePanierBD(<?= $user ?>, <?= $articles[$i]->getId() ?>)">
                            <?php } else { ?>
                                <button onclick="supprimerArticlePanierSession(<?= $articles[$i]->getId() ?>)">
                            <?php } ?>
                                <span class="material-symbols-outlined">
                                    close
                                </span>
                            </button>
                        </div>
                        <p class="ArticleInfoDescription"><?=$articles[$i]->getDescription()?></p>
                        <div class="ArticleInfoBas">
                            <div class="quantity">
                                <p>Quantite</p>
                                <?php if ($connecte) { ?>
                                    <input
                                        class="inputQuantiteArticle" 
                                        type="number" min="0" max="<?=$articles[$i]->getQuantite()?>" 
                                        value="<?=$panier[$i]->getQuantiteCommande()?>" 
                                        id="quantite<?=$articles[$i]->getId()?>"
                                        name="quantite"
                                        data-id=<?=$articles[$i]->getId()?>
                                        data-method = "<?= site_url('Panier/update/')?>"
                                        data-user="<?= $user ?>"
                                    >
                                <?php } else { ?>
                                    <input
                                        class="inputQuantiteArticle" 
                                        type="number" min="0" max="<?=$articles[$i]->getQuantite()?>" 
                                        value="<?=$panier[$i]->getQuantiteCommande()?>" 
                                        id="quantite<?=$articles[$i]->getId()?>" 
                                        name="quantite"
                                        data-id="<?=$articles[$i]->getId()?>"
                                        data-method = "<?= site_url('Panier/updateSession/')?>"
                                    >
                                    <?php } ?>
                            </div>
                            <a class="ArticleInfoTextePageProduit" href="<?= site_url("Boutique/article/").$articles[$i]->getId()?>" >
                                <p>Page produit</p>
                                <span class="material-symbols-outlined">
                                    search
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endfor;?>
            <!-- fin boucle -->
        </div>

        <!-- Panel informatif de droite -->
        <div class="total">
            <h2>TOTAL</h2>
            <div class="totalCorps">
                <div class="totalTexte">
                    <p>Article (<?=$quantite?>)</p>
                    <p>Frais de livraison</p>
                    <p class="littleText">(gratuit à partir de 75€ d'achat)</p>
                </div>
                <div class="totalNombre">
                    <p><?php
                        if ($prix != 0) {
                            $decimal = explode(".", $prix)[1];
                            if (strlen($decimal) == 0) {
                                echo $prix.",00";
                            } else if (strlen($decimal) == 1) {
                                echo $prix."0";
                            } else {
                                echo $prix;
                            }
                        } else {
                            echo $prix;
                        }
                    ?>€</p> <!-- rajoute des zéros à la fin pour toujours avoir 2 décimals -->

                    <?php if ($prix < 75 && $prix > 0) {
                        $livraison = 6.66;?>
                        <p>6,66€</p> <!-- Frais de livraison -->
                    <?php } else {
                        $livraison = 0; ?>
                        <p>GRATUIT</p> <!-- Frais de livraison -->
                    <?php } ?>
                </div>
            </div>
            <hr>
            <div class="totalTotal">
                <p>Total</p>
                <?php if ($quantite == 0) {
                    $livraison = 0;
                }
                $this->session->set_userdata(array("quantite" => $quantite));
                $this->session->set_userdata(array("prix" => $prix));
                $this->session->set_userdata(array("livraison" => $livraison));?>

                <p><?php
                    $total = $prix + $livraison;
                    if ($total != 0) {
                        $decimal = explode(".", $total)[1];
                        if (strlen($decimal) == 0) {
                            echo $total.",00";
                        } else if (strlen($decimal) == 1) {
                            echo $total."0";
                        } else {
                            echo $total;
                        }
                    } else {
                        echo $total;
                    }
                ?>€</p>
            </div>
            <form action="<?=site_url('Panier/achat')?>" method="post">
                <div class="totalButton">
                    <?php if ($quantite == 0){?>
                        <input class="totalInputDisabled" type="submit" value="COMMANDER" disabled>
                    <?php } else {?>
                        <input class="totalInput" type="submit" value="COMMANDER">
                    <?php }?>
                </div>
            </form>
        </div>
        <!-- Fin panel informatif -->
    </div>
    <script>
        const quantites = document.getElementsByName("quantite");

        quantites.forEach(function(elem) {
            elem.addEventListener("change", () => {
                <?php if ($connecte) { ?>
                    modifierQuantitePanierBD(elem.dataset.user, elem.dataset.id, document.getElementById("quantite" + elem.dataset.id).value);
                <?php } else { ?>
                    modifierQuantitePanierSession(elem.dataset.id, document.getElementById("quantite" + elem.dataset.id).value);
                <?php } ?>
            });
        });

    </script>
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
