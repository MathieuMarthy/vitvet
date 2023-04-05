
<!DOCTYPE html>
<html lang="fr" ir="ltr">
    <head>
      <meta charset="utf-8">
      <link rel="stylesheet" href="#">
      <?php include("favicon.php"); ?>
      <link rel="stylesheet" href="<?=base_url()?>assets/style/header-footer.css">
      <link rel="stylesheet" href="<?=base_url()?>assets/style/admin.css">
      <title>VitVet | Admin Utilisateurs</title>
</head>
<!-- Header -->
<?php include("headerAdmin.php"); ?>
<!-- Fin Header -->
<script>
    function deleteArticle(infos) {
        let text = "Voulez-vous vraiment supprimer" +
            "\nprenom: " + infos[0] +
            "\nnom: " + infos[1] +
            "\nlogin: " + infos[2] +
            "\nmail: " + infos[3] +
            "\nid: " + infos[4] +
            "\nstatut: " + infos[5] +
            "\nadresse: " + infos[6] +
            "\ncode postal: " + infos[7]

        if (confirm(text) == true) {
            supprimerUtilisateurAdmin(infos[4]);
        }
    }
</script>

<body>
    <a class="backToTop" onclick="window.scrollTo({top: 0, behavior: 'smooth',})">↑</a>
    <div class="main">
        <div class="side">

        </div>
        <div class="listArticles">
            <h1 class="titre"><?= count($utilisateurs) ?> UTILISATEURS</h1>

            <?php foreach($utilisateurs as $utilisateur): ?>
                <hr>
                <div class="Article">
                    <div class="ArticleInfo">
                        <p>prenom: <?= $utilisateur->getPrenom() ?></p>
                        <p>nom: <?= $utilisateur->getNom() ?></p>
                        <p>login: <?= $utilisateur->getLogin() ?></p>
                        <p>mail: <?= $utilisateur->getMail() ?></p>
                        <p>id: <?= $utilisateur->getId() ?></p>
                        <p>statut: <?= $utilisateur->getStatut() ?></p>
                        <p>adresse: <?= $utilisateur->getAdresse() ?></p>
                        <p>code postal: <?= $utilisateur->getDepartement() ?></p>
                        <?php if ($utilisateur->getVerifier() == 1) { ?>
                            <p>vérifié: oui</p>
                        <?php } else { ?>
                            <p>vérifié: non</p>
                        <?php } ?>
                    </div>

                    <div class="ArticleInfoBoutons end">
                        <?php if ((($utilisateur->getStatut() != "admin") || ($this->session->userdata("user")["id"] == 1)) && ($utilisateur->getId() != 1)) { ?>
                                <button class="bouton" onclick="deleteArticle(['<?= $utilisateur->getPrenom() ?>', '<?= $utilisateur->getNom() ?>', '<?= $utilisateur->getLogin() ?>', '<?= $utilisateur->getMail() ?>', '<?= $utilisateur->getId() ?>', '<?= $utilisateur->getStatut() ?>', '<?= $utilisateur->getAdresse() ?>', '<?= $utilisateur->getDepartement() ?>'])">Supprimer</button>
                        <?php } ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
        <div class="divAjouter side">
            <a href="<?= site_url('admin/ajoutAdmin') ?>">
                <input class="fixed bouton gris" type="button" value="Créer un nouvel admin">
            </a>
            <form method="get" action="<?= site_url("Admin/rechercheUser") ?>">
            <input class="searchBar" placeholder="Rechercher un utilisateur" name="recherche">
            </form>
        </div>
    </div>
</body>
<!-- footer -->
<?php include("footer.php"); ?>
<!-- fin footer -->
</html>
<script type="text/javascript" src="<?=base_url('assets/js/requetes.js')?>" ></script>