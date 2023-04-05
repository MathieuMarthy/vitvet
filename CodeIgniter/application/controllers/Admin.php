<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    private string $imagesPath = "assets/image/articles/";
    private bool $owner = false;

    public function __construct() {
		parent::__construct();
		$this->load->helper('form');
        
		$this->load->model('PanierModel');
        $this->load->model('ArticleModel');
        $this->load->model("UserModel");
        $this->load->model("FunctionsModel");

        // vérifie si l'utilisateur est un admin
        if (!$this->session->has_userdata("user") || !$this->UserModel->isAdmin($this->session->userdata("user")["id"])) {
            redirect("home"); 
            die();
        }

        // si l'utilisateur est l'admin suprême
        if ($this->session->userdata("user")["id"] == 1) {
            $this->owner = true;
        }
	}

    /**
     * affiche tous les articles de la boutique en colonne triées par ajout en ordre décoissant (le plus récent en premier)
     * pour chaque article, les infos de l'article et 2 boutons (modifier et supprimer)
     * mettre un bouton ajouter toujours disponible (dans le header par exemple) 
     * @return void
     */
    public function index() {
        $products = $this->ArticleModel->findAll();
        $data = array(
            "articles" => array_reverse($products)
        );
		$this->load->view('adminArticles', $data);
    }

    /**
     * Affiche un formulaire pour ajouter un nouvel admin
     * @return void
     */
    public function ajoutAdmin() {
        $data = array(
            "admin" => true
        );
        $this->load->view("creerUnCompte", $data);
    }

    /**
     * Renvoie sur un form pour ajouter un article
     * @return void
     */
    public function ajoutArticle() {
        $this->load->view("adminProduct");
    }

    /**
     * charge la page depuis laquelle on peut voir tous les utilisateurs
     * @return void
     */
    public function listeUtilisateurs() {
        $utilisateurs = $this->UserModel->findAll();
        $data = array(
            "utilisateurs" => $utilisateurs
        );
		$this->load->view("adminUsers", $data);
    }

    /**
     * Supprime un utilisateur si il n'est pas un admin
     * Seul l'admin suprême peut supprimer d'autres admins
     * 
     * @param int $id
     * @return void
     */
    public function deleteUser($id) {
        $user = $this->UserModel->findById($id);

        if ((($user->getStatut() != "admin") || ($this->owner)) && ($user->getId() != 1)) {
            $this->UserModel->deleteById(array($id));
        }
    }

    /**
     * ajoute le produit à la bd et renvoie sur la page principale de l'admin
     * @return never
     */
    public function add() {
        if (isset($_POST["nom"], $_POST["description"], $_POST["prix"], $_POST["quantite"], $_POST["taille"], $_POST["categorie"])) {
            $data = array(
                "nom" => $_POST["nom"],
                "prix" => $_POST["prix"],
                "quantite" => $_POST["quantite"],
                "description" => $_POST["description"],
                "categorie" => $_POST["categorie"],
                "taille" => strtoupper($_POST["taille"]),
            );

            // vérification des types des entrées
            if (!is_numeric($data["prix"]) ||
                (!is_numeric($data["taille"]) && !in_array($data["taille"], array("XXS", "XS", "S", "M", "L", "XL", "XXL"))) ||
                (!in_array($data["categorie"], array("tshirt", "pantalon", "chaussure")))) {
                    redirect("admin");
                }

            // récupère l'image
            $config["upload_path"] = $this->imagesPath;
            $config["allowed_types"] = "png|webp|jpg|jpeg";
            $config["max_size"] = 10_000;

            $this->load->library("upload", $config);


            if (!$this->upload->do_upload("image")) {
                $error = array("error" => $this->upload->display_errors());
                var_dump($error);
                die();
            } else {
                $data["image"] = $this->upload->data("file_name");
                $this->ArticleModel->add($data);
            }
        }
        redirect("admin");
    }

    /**
     * Renvoie sur un form de modification d'un article
     * @param mixed $id
     * @return void
     */
    public function updateArticle($id) {
        $article = $this->ArticleModel->findById($id);

        if (!is_null($article)) {
            $data = array(
                "article" => $article
            );
            $this->load->view("adminProduct", $data);
        } else {
            redirect("admin");
        }
    }

    /**
     * modifie l'article dans la bd et renvoie sur la page principale de l'admin
     * @return never
     */
    public function update() {
        if (isset($_POST["id"], $_POST["nom"], $_POST["description"], $_POST["prix"], $_POST["quantite"], $_POST["taille"], $_POST["categorie"])) {
            $article = $this->ArticleModel->findById($_POST["id"]);

            $data = array(
                "id" => $this->input->post("id"),
                "nom" => $this->input->post("nom"),
                "prix" => $this->input->post("prix"),
                "quantite" => $this->input->post("quantite"),
                "description" => $this->input->post("description"),
                "categorie" => $this->input->post("categorie"),
                "taille" => strtoupper($this->input->post("taille")),
                "image" => $article->getImage()
            );

            // vérification des types des entrées
            if (!is_numeric($data["prix"]) ||
                (!is_numeric($data["taille"]) && !in_array($data["taille"], array("XXS", "XS", "S", "M", "L", "XL", "XXL"))) ||
                (!in_array($data["categorie"], array("tshirt", "pantalon", "chaussure"))) ||
                !is_numeric($this->input->post("id"))) {
                    redirect("admin");
                }

            // si une image est donnée
            if (isset($_FILES["image"]) && $_FILES["image"]["name"] != "") {
                $config["upload_path"] = $this->imagesPath;
                $config["allowed_types"] = "webp|png|jpg|jpeg";
                $config["max_size"] = 10_000;

                $this->load->library("upload", $config);

                if (!$this->upload->do_upload("image")) {
                    $error = array("error" => $this->upload->display_errors());
                    var_dump($error);
                    die();
                } else {
                    $data["image"] = $this->upload->data("file_name");
                    unlink($this->imagesPath.$article->getImage());
                }
            }
            $this->ArticleModel->update($data);
        }
        redirect("admin");
    }

    /**
     * supprime l'article de la bd
     * @param mixed $id
     * @return void
     */
    public function delete($id) {
        $article = $this->ArticleModel->findById($id);
        $this->ArticleModel->delete(array($id));
        // redirect("admin");
    }


    /**
     * affiche tous les tshirts
     * @return void
     */
    public function tshirt() {
        $products = $this->ArticleModel->findByCategorie("Tshirt");
        $data = array(
            "articles" => $products
        );
		$this->load->view('adminArticles', $data);
    }

    /**
     * affiche tous les pantalons
     * @return void
     */
    public function pantalon() {
        $products = $this->ArticleModel->findByCategorie("pantalon");
        $data = array("articles" => $products);
        $this->load->view('adminArticles', $data);
    }

    /**
     * affiche toutes les chaussures
     * @return void
     */
    public function chaussure() {
        $products = $this->ArticleModel->findByCategorie("chaussure");
        $data = array("articles" => $products);
        $this->load->view('adminArticles', $data);
    }

    /**
     * Trouve les tous les articles correspondant à la recherche
     * @return void
     */
    public function rechercheArticle(){
        $data = array("recherche" => $this->input->get("recherche"));
        $products = $this->ArticleModel->recherche($data);
        $data = array("articles" => $products);
        $this->load->view('adminArticles', $data);
      }


    /**
     * Trouve les tous les utilisateurs correspondant à la recherche
     * @return void
     */
    public function rechercheUser(){
        $data = array("recherche" => $this->input->get("recherche"));
        $products = $this->UserModel->recherche($data);
        $data = array("utilisateurs" => $products);
        $this->load->view('adminUsers', $data);
    }
}
?>
