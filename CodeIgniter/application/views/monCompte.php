<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <link rel="stylesheet" href="<?=base_url()?>assets/style/monCompte.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/style/header-footer.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/style/popup.css">
    <?php include("favicon.php"); ?>
    <meta charset="utf-8">
    <title>VitVet | Mon compte</title>
  </head>
<!-- Header (+ page partagé entre user et admin donc il faut mettre le choix du header) -->
<?php 
if ($this->session->has_userdata("user")&&$this->session->userdata("user")["statut"]=="admin"){
  include("headerAdmin.php");
}else{
  include("header.php");
}
?>
<!-- Fin Header -->
<script>
    function supprCompte(infos) {
        let text = "Voulez-vous vraiment supprimer votre compte ? ";
        if (confirm(text) == true) {
            window.location.assign('<?=site_url("User/deleteAccount")?>')
        }
    }
</script>
<body background="<?=base_url()?>assets/image/backgroundInfos.jpg">
  <div class="infos">
    <div class="commande">
    <a href="<?=site_url('User/commandes')?>"><input class="boutonCommande" type="submit" value="Mes commandes"></a>
    </div>
    <div class="boxInfosCompte">
      <h1>Mon Compte</h1>
    </div>
      <div class="formCompte">
        <label class="login">Login :</label>
        <input class="case_login" type="text" name="login" value="<?= $user->getLogin()?>" disabled></label> 
        <label class="nom">Nom :</label>
        <input class="case_nom" type="text" name="nom" value="<?= $user->getNom()?>" disabled></label>
        <label class="prenom">Prénom :</label>
        <input class="case_prenom" type="text" name="prenom" value="<?= $user->getPrenom() ?> "disabled></label>
        <label class="email">Email :</label>
        <input class="case_email" type="text" name="email" value="<?= $user->getMail() ?>" disabled></label>
        <label class="adresse">Adresse :</label>
        <input class="case_adresse" type="text" name="adresse" value="<?= $user->getAdresse()?>" disabled></label>
        <label class="departement_choix">Code Postal :</label>
        <input class="nombre_dpt" type="text" name="dept" value="<?= $user->getDepartement()?>" disabled></label>
        <?php if ($this->session->userdata('user')['id']!=1) : ?>
        <div class="suppression">
          <input class="change_infos" type="button" value="Supprimer mon compte" onclick="supprCompte()">
        </div>
        <?php endif ?>
        <div class="modification">
          <a href="<?=site_url('User/modifInfos')?>"><input class="change_infos" type="submit" value="Modifier vos informations"></a>
        </div>
      </div>     
    </div>
  </div>
</body>
<!-- footer -->
<?php include("footer.php") ?>
<!-- fin footer -->
</html>
<?php if (isset($popupType, $popupMessage)) { ?>
    <script>
        const popupType = "<?= $popupType ?>"
        const popupMessage = "<?= $popupMessage ?>"
    </script>
<?php } ?>
<script type="text/javascript" src="<?=base_url('assets/js/requetes.js')?>" ></script>