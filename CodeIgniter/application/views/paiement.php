<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=base_url()?>assets/style/header-footer.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/style/paiement.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/style/popup.css">
    <?php include("favicon.php"); ?>
    <title>VitVet | Paiement</title>
</head>
<!-- Header -->
<?php 
    include("header.php");
    $prix = $this->session->userdata('prix');
    $quantite = $this->session->userdata('quantite');
    $livraison = $this->session->userdata('livraison');
?>
<!-- Fin Header -->
<body>
    <a class="backToTop" onclick="window.scrollTo({top: 0, behavior: 'smooth',})">↑</a>
    <div class="corpsPanier">
        <div class="panier">
            <h2>PAIEMENT</h2>
            <hr>
            <form method="post" action="<?= site_url('Panier/verifPaiement')?>" class="formCreerCompte">
                <div class="grille">
                    <?php if(isset($admin)) { ?>
                        <input style="display:none" name="admin" value="true" >
                    <?php } ?>
                    <label class="nom" for="text">Nom*</label>
                    <input class="caseNom" type="text" placeholder="Entrez votre nom" id="ajoutNom" name="nom" value="<?=$user->getNom()?>" required>
                    <label class="prenom" for="text">Prénom*</label>
                    <input class="casePrenom" type="text" placeholder="Entrez votre prénom" id="ajoutPrenom" name="prenom" value="<?=$user->getPrenom()?>" required>
                    <label class="mail" for="emailCreer">E-mail*</label>
                    <input class="caseMail" type="email" placeholder="Entrez votre adresse e-mail" id="emailCreer" name="mail" value="<?=$user->getMail()?>" required>
                    <label class="adresse" for="adresseCreer">Adresse*</label>
                    <input class="caseAdresse" type="text" placeholder="Entrez votre adresse" id="adresseCreer" name="adresse" value="<?=$user->getAdresse()?>" required>
                    <label class="departement" for="departementCreer">Code Postal*</label>
                    <input class="choixDepartement" type="number" placeholder="Entrez votre code postal" id="departementCreer" min="01000" max="98000" name="code_postal" value="<?=$user->getDepartement()?>" required>
                    <label class="numCarte" for="numCreer">Numéro de carte bancaire*</label>
                    <input class="caseNumCarte" type="tel" pattern="\d*" onkeydown="return( event.ctrlKey || event.altKey 
                    || (47<event.keyCode && event.keyCode<58 && event.shiftKey==true) 
                    || (95<event.keyCode && event.keyCode<106)
                    || (event.keyCode==8) || (event.keyCode==9) 
                    || (event.keyCode>34 && event.keyCode<40) 
                    || (event.keyCode==46))" placeholder="Entrez votre numéro de carte bancaire" id="numCreer" name="card" maxlength="16" required>
                    <label class="dateDexpiration" for="dateCreer">Date d'expiration*</label>
                    <input class="caseDate" type="text" onkeydown="return( event.ctrlKey || event.altKey 
                    || (47<event.keyCode && event.keyCode<58 && event.shiftKey==true) 
                    || (95<event.keyCode && event.keyCode<106)
                    || (event.keyCode==8) || (event.keyCode==9) 
                    || (event.keyCode>34 && event.keyCode<40) 
                    || (event.keyCode==46)
                    || (event.keyCode==2f))" placeholder="MM/AA" id="dateCreer" name="date" maxlength="5" required>
                    <label class="cvv" for="cvvCreer">CVV*</label>
                    <input class="caseCVV" pattern="\d*" type="tel" onkeydown="return( event.ctrlKey || event.altKey 
                    || (47<event.keyCode && event.keyCode<58 && event.shiftKey==true) 
                    || (95<event.keyCode && event.keyCode<106)
                    || (event.keyCode==8) || (event.keyCode==9) 
                    || (event.keyCode>34 && event.keyCode<40) 
                    || (event.keyCode==46))" placeholder="CVV" id="cvvCreer" maxlength="3" name="cvv" required>
                </div>
                <div class="bouton">
                    <a href="<?=site_url('Panier/annuleAchat')?>">
                        <input class="envoi" type="button" value="Retour au panier">
                    </a>
                    <input class="envoi" type="submit" value="PAYER <?=$prix ?>€">
                </div>
            </form>
        </div>   
        <div class="total">
            <h2>TOTAL</h2>
            <div class="totalCorps">
                <div class="totalTexte">
                    <p>Article (<?=$quantite?>)</p>
                    <p>Frais de livraison</p>
                    <p class="littleText">(gratuit à partir de 75€ d'achat)</p>
                </div>
                <div class="totalNombre">
                    <p><?=$prix?>€</p> <!-- Articles -->
                    <?php if ($livraison>0) {?>
                        <p>6,66€</p> <!-- Frais de livraison -->
                    <?php } else {?>
                        <p>GRATUIT</p> <!-- Frais de livraison -->
                    <?php } ?>
                </div>
            </div>
            <hr>
            <div class="totalTotal">
                <p>Total</p>
                <p><?=$prix+$livraison?>€</p>
            </div>
        </div>
    </div>
    <script>
        const quantites = document.getElementsByName("quantite");
        console.log(quantites);
        for (quantite of quantites) {
            quantite.addEventListener("change", () => {
                <?php if ($this->session->has_userdata("user")) { ?>
                    window.location.assign(quantite.dataset.method+quantite.dataset.user+'/'+quantite.dataset.id+"/"+document.getElementById("quantite").value);
                <?php } else { ?>
                    window.location.assign(quantite.dataset.method+quantite.dataset.id+"/"+document.getElementById("quantite").value);
                <?php } ?>
            });
        }

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
