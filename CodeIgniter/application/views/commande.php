<!DOCTYPE html>
<html lang="fr" ir="ltr">
    <head>
      <meta charset="utf-8">
      <?php include("favicon.php"); ?>
      <link rel="stylesheet" href="<?=base_url()?>assets/style/header-footer.css">
      <link rel="stylesheet" href="<?=base_url()?>assets/style/commande.css">
      <title>VitVet | Commande</title>
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

    <div class="corpsCommande">

        <div class="commande">
            <div class="ligne1">
                <h2>MA COMMANDE - <?= $quantite ?> ARTICLE<?php if ($quantite > 1) {
                       echo "S";}?></h2>
                <a href="<?= site_url('User/commandes') ?>">
                    <button type="button" class="bouton">Mes commandes</button>
                </a>
            </div>
            <?php for ($i = 0; $i < count($commandes); ++$i): ?> 
                <hr>
                <div class="Article">
                <img loading="lazy" class="ImageProduit" src="<?= base_url('assets/image/articles/'.$articles[$i]->getImage()) ?>" alt="image vêtement">
                    <div class="ArticleInfo">
                        <div class="ArticleInfoHaut">
                            <div class="ArticleInfoTitrePrix">
                                <p><?=$articles[$i]->getNom()?> - <?= $articles[$i]->getTaille() ?></p>
                                <p><?=$articles[$i]->getPrix() * $commandes[$i]->getQuantiteCommande()?>€</p>
                                <p class="quantity">Quantite <?= $commandes[$i]->getQuantiteCommande() ?></p>
                            </div>
                        </div>
                        <p class="ArticleInfoDescription"><?=$articles[$i]->getDescription()?></p>
                        <div class="ArticleInfoBas">
                            
                            <a class="ArticleInfoTextePageProduit" href="<?= site_url("Boutique/article/".$articles[$i]->getId())?>" >
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
                    <p>Article (<?= $quantite ?>)</p>
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
        </div>
    </div>
</body>
<!-- footer -->
<?php include("footer.php"); ?>
<!-- fin footer -->
</html>
