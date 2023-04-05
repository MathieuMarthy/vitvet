<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
<header class="header">
    <a href="<?=site_url('Home/')?>" class="headerLogoTexte">
        <img src="<?=base_url()?>assets/image/logo_with_text.png" class="headerLogo" alt="logo" height="76">
    </a>
    <div class="headerLiens">
        <a href="<?=site_url('Home/')?>">Accueil</a>
        <div class="categories">
            <a href="<?=site_url('Boutique/')?>">Boutique</a>
            <ul class="menuDeroulant">
                <li class="liHeader" ><a href="<?=site_url('Boutique/tshirt')?>">T-Shirts</a></li>
                <li class="liHeader" ><a href="<?=site_url('Boutique/pantalon')?>">Pantalons</a></li>
                <li class="liHeader" ><a href="<?=site_url('Boutique/chaussure')?>">Chaussures</a></li>

            </ul>
        </div>
        <a href="<?=site_url('Contact/')?>">Contact</a>
        <?php if ($this->session->has_userdata("user")){?>
            <a href="<?= site_url('User/monCompte') ?>"><?= $this->session->userdata("user")["login"]; ?></a>
            <a href="<?=site_url('User/logout')?>">Deconnexion</a>
        <?php } else { ?>
            <a href="<?=site_url('User/login')?>">Connexion</a>
        <?php } ?>
        <a class="headerPanier" href="<?=site_url('Panier/')?>">
            <p id="nbArticles"><?= $this->session->userdata("nbArticles") ?></p>
            <span class="material-symbols-outlined">shopping_cart</span>
        </a>
    </div>
</header>
<?php
$page = $this->router->class;
?>