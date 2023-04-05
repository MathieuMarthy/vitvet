
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
<header class="header">
    <a href="<?=site_url('')?>" class="headerLogoTexte">
        <img src="<?=base_url()?>assets/image/logo_with_text.png" class="headerLogo" alt="logo">
    </a>
    
    <div class="headerLiens">
        <a href="<?=site_url('Admin/listeUtilisateurs')?>">Utilisateurs</a>
        <div class="categories">
            <a href="<?=site_url('Admin/')?>">Articles</a>
            <ul class="menuDeroulant">
                <li class="liHeader"><a href="<?=site_url('Admin/tshirt')?>">T-Shirts</a></li>
                <li class="liHeader"><a href="<?=site_url('Admin/pantalon')?>">Pantalons</a></li>
                <li class="liHeader"><a href="<?=site_url('Admin/chaussure')?>">Chaussures</a></li>
            </ul>
        </div>
        <a href="<?= site_url('User/monCompte') ?>"><?= $this->session->userdata("user")["login"]; ?></a>
        <a href="<?=site_url('User/logout')?>">Deconnexion</a>
    </div>
</header>
<?php
$page = $this->router->class.'/'.$this->router->method;
?>