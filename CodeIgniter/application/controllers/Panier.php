<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR."PanierEntity.php";

class Panier extends CI_Controller
{

    public function __construct()
	{
		parent::__construct();
		$this->load->model('PanierModel');
        $this->load->model('UserModel');
        $this->load->model('ArticleModel');
        $this->load->model("FunctionsModel");
        
        //vérifie que le user existe toujours dans la base de données (si un Admin supprime un User, il sera déconnecté)
        if ($this->session->has_userdata("user") && is_null($this->UserModel->findById($this->session->userdata("user")["id"]))) {
            $this->session->unset_userdata("user");
            redirect(""); 
            die();
        }
	}

    /**
     * affiche tous les produits du panier
     * @return void
     */
    public function index() {
        $prix = 0;
        //affiche si le User n'est pas connecté
        if (!$this->session->has_userdata('user')){
            $productsDuPanier = array();
            $products = array();
            $quantite = 0;
            foreach (array_keys($this->session->userdata('panier')) as $idProd){
                $article = $this->ArticleModel->findById($idProd); //la référence de l'article commandé
                $q = $this->session->userdata('panier')[$idProd]; //la quantite commandé
                array_push($products, $article); //met l'article dans le tableau de tout les articles commandé
                $temp = new PanierEntity;
                $temp->setIdArticle($idProd);
                $temp->setQuantiteCommande($q);
                array_push($productsDuPanier, $temp); //met le produit dans le tableau de tout les produit du panier (pour avoir la quantite commande)
                $quantite += $this->session->userdata('panier')[$idProd];
                $prix += $article->getPrix() * $q;
            }
        } 
        //affiche si le User est connecté
        else {
            $id = $this->session->userdata("user")['id'];
            $productsDuPanier = $this->PanierModel->findByUserId($id);
            $products = array();
            foreach ($productsDuPanier as $product) {
                $article = $this->ArticleModel->findById($product->getIdArticle());
                array_push($products, $article);
                $prix += $article->getPrix()*$product->getQuantiteCommande();
            }
            $quantite = $this->PanierModel->count($id);
        }
        $data = array("panier" => $productsDuPanier, "articles" => $products, "quantite" => $quantite, "prix" => $prix);

        //affiche la popup si besoin
        // Si dans l'url il y a "popupType" & "popupMessage" alors il faut afficher une popup
        // il faut donc mettre dans le type et le message demandé
        if (isset($_REQUEST["popupType"], $_REQUEST["popupMessage"])) {
            $data["popupType"] = $_REQUEST["popupType"];
            $data["popupMessage"] = $_REQUEST["popupMessage"];
        }
		$this->load->view('panier', $data);
    }

    /**
     * ajoute un produit au panier dans la bd
     * @param int $id_user
     * @param int $id_article
     * @param mixed $quantite
     * @return void
     */
    public function add(int $id_user, int $id_article, $quantite = 1)
    {
        //sort de la fontion si la personne n'est pas connecté
        if (!$this->session->has_userdata("user")) {
            redirect("");
        }

        //sort de la fonction si la quantite est inf à 1
        if ($quantite < 1) {
            return;
        }

        $existe = $this->PanierModel->findOneLine(array('id_user' => $id_user, "id_article" => $id_article));
        $stock = $this->ArticleModel->findById($id_article)->getQuantite();

        //si le produit n'existe pas encore alors on l'ajoute dans la base de données avec la quantite en entrée
        if (count($existe) == 0) {
            if ($quantite > $stock) {
                $quantite = $stock;
            }
            $_SESSION["nbArticles"] += $quantite;
            $this->PanierModel->add(array("id_user" => $id_user, "id_article" => $id_article, "quantiteCommande" => $quantite));
        }

        //si le produit existe dans la base de données alors on change sa quantite commandée
        else {
            $existe = $existe[0];
            $quantiteCommande = $existe->getQuantiteCommande();
            if ($quantiteCommande + $quantite > $stock) { //n'ajoute pas au panier si la quantite en stock ne le permet pas
                $quantite = $stock;
            } else {
                $quantite += $quantiteCommande;
            }
            $_SESSION["nbArticles"] += $quantite - $quantiteCommande;
            $this->PanierModel->update(array("id_user" => $id_user, "id_article" => $id_article, "quantiteCommande" => $quantite));
        }
    }


    /**
     * ajoute un produit au panier de la session
     * @param int $id_article
     * @param mixed $quantite
     * @return void
     */
    public function addSession(int $id_article, $quantite=1){
        //on sort de la fonction si la quantite est inf à 1
        if ($quantite <1){
            return;
        }
        $formerNbArticle = $_SESSION["nbArticles"]; // on enregistre le nombre d'article dans la panier
        $formerNbArticleCommande = 0; // on enregistre le nombre d'article déjà présent dans la panier
        if (isset($this->session->userdata("panier")[$id_article])) { // si l'article est déjà présent dans la panier alors on met à jour formerNbArticleCommande
            $formerNbArticleCommande = $this->session->userdata("panier")[$id_article];
        }

        // ajoute dans la panier la quantite commande et met à jour le nombre d'article commande
        $_SESSION['panier'][$id_article] += $quantite;
        $_SESSION["nbArticles"]+=$quantite;

        //vérification que la quantite commande n'est pas supérieur à la quantite présente dans la base de données
        $article = $this->ArticleModel->findById($id_article);
        $q = $article->getQuantite();
        if ($_SESSION['panier'][$id_article] > $q){
            $_SESSION['panier'][$id_article] = $q;
            $_SESSION['nbArticles'] = $formerNbArticle + $q - $formerNbArticleCommande;
        }
    }

    
    /**
     * change un article du panier (la quantite commande seulement), l'article existe. On set sa quantite à la valeur en entrée
     * @param int $id_user
     * @param int $id_article
     * @param int $quantite
     * @return never
     */
    public function update(int $id_user, int $id_article, int $quantite){
        //redirige si le User n'est pas connecté
        if (!$this->session->has_userdata("user")){
            redirect("");
        }
        $article = $this->ArticleModel->findById($id_article);
        $q = $article->getQuantite();
        //si la quantité est inf ou égal à 0 alors on supprime l'article du Panier
        if ($quantite<=0){
            redirect("Panier/delete/".$id_user."/".$id_article);
            die();
        }
        $lignePanier = $this->PanierModel->findOneLine(array('id_user' => $id_user, "id_article" => $id_article))[0];
        //si la quantité est trop grande par rapport à ce qui est dispo alors on met la quantité maximal commandable
        if ($quantite > $q) {
            $quantite = $q;
        }
        $this->PanierModel->update(array("id_user" => $id_user, "id_article" => $id_article, "quantite" => $quantite));
        $_SESSION["nbArticles"]+=($quantite - $lignePanier->getQuantiteCommande());
        redirect("Panier");
    }

    /**
     * change un article du panier (la quantite commande seulement), l'article existe. On set sa quantite à la valeur en entrée
     * @param int $id_article
     * @param int $quantite
     * @return never
     */
    public function updateSession(int $id_article, int $quantite){
        if ($quantite<=0){
            redirect("Panier/deleteSession/".$id_article);
            die();
        }
        $article = $this->ArticleModel->findById($id_article);
        $q = $article->getQuantite();
        if ($quantite > $q) {
            $quantite = $q;
        }
        $_SESSION["nbArticles"]+=($quantite - $_SESSION['panier'][$id_article]);
        $_SESSION['panier'][$id_article] = $quantite;
        redirect('Panier');
    }

    /**
     * supprime un article du panier dans la bd
     * @param int $id_user
     * @param int $id_article
     * @return never
     */
    public function delete(int $id_user, int $id_article){
        if (!$this->session->has_userdata("user")){
            redirect("");
        }
        $lignePanier = $this->PanierModel->findOneLine(array('id_user' => $id_user, "id_article" => $id_article))[0];
        $_SESSION["nbArticles"]-=$lignePanier->getQuantiteCommande();
        $this->PanierModel->delete(array("id_user" => $id_user, "id_article" => $id_article));
        redirect("Panier");
    }

    /**
     * on supprime un article du panier en session
     * @param int $id_article
     * @return never
     */
    public function deleteSession(int $id_article){
        $_SESSION["nbArticles"]-=$_SESSION['panier'][$id_article];
        unset($_SESSION['panier'][$id_article]);
        redirect('Panier');
    }

    /**
     * Vide tous les articles du panier d'un User et met le nombre d'article à 0
     * @param int $id
     * @return void
     */
    private function videPanier(int $id){
        if (!$this->session->has_userdata("user")){
            redirect("");
        }
        $products = $this->PanierModel->findByUserId($id);
        foreach ($products as $product) {
            $this->PanierModel->delete(array("id_user" => $id, "id_article" => $product->getIdArticle()));
        }
        $_SESSION["nbArticles"]=0;
    }

    /**
     * Supprime les articles du panier d'un User puis redirige sur la page Panier
     * @param int $id
     * @return never
     */
    public function deleteAll(int $id){
        $this->videPanier($id);
        redirect("Panier");
    }

    /**
     * vide les articles du panier d'un User en session et met son nombre d'article à 0
     * @return never
     */
    public function deleteAllSession(){
        unset($_SESSION['panier']);
        $_SESSION["nbArticles"]=0;
        redirect("Panier");
    }

    /**
     * Diminue la quantite de chaque article commandé du panier dans la table Article
     * @param int $id l'id du User
     * @return void
     */
    private function diminueQuantite(int $idUser){
        $products = $this->PanierModel->findByUserId($idUser);
        foreach ($products as $product) {
            $article = $this->ArticleModel->findById($product->getIdArticle());
            $this->ArticleModel->diminueQuantite($article, $product->getQuantiteCommande());
        }
    }

    /**
     * Augmente la quantite de chaque article commandé du panier dans la table Article (utilisé si le User ne veut finalement pas acheté son panier)
     * @param int $id
     * @return void
     */
    private function augmenteQuantite(int $idUser){
        $products = $this->PanierModel->findByUserId($idUser);
        foreach ($products as $product) {
            $article = $this->ArticleModel->findById($product->getIdArticle());
            $this->ArticleModel->augmenteQuantite($article, $product->getQuantiteCommande());
        }
    }

    /**
     * Vérifie la quantité des article dans la panier par rapport à la quantité disponible en stock.
     * Redirige sur le panier si il y a un problème de quantité
     * @param UserEntity $user
     * @return void
     */
    private function verifyQuantity(int $idUser){
        $ok = true;
        $articlesPanier = $this->PanierModel->findByUserId($idUser);
        foreach ($articlesPanier as $article){
            $articleReference = $this->ArticleModel->findById($article->getIdArticle());
            //si au moins un article commandée est demandée dans une quantité supérieur à ce qui existe dans la bd alors : 
            if ($articleReference->getQuantite() < $article->getQuantiteCommande()) {
                //on met ok à false
                $ok = false;
                //on met en place les données
                $newdata = array(
                    "id_user" => $idUser,
                    "id_article" => $article->getIdArticle(),
                    "quantite_commande" => $articleReference->getQuantite()
                );
                //modifie la variable de session indiquant le nombre d'article et on modifie les données dans la bd du Panier du User
                $_SESSION["nbArticles"] +=  ($articleReference->getQuantite() - $article->getQuantiteCommande()); 
                $this->PanierModel->update($newdata);
            }
        }
        if (!$ok){
            redirect("Panier?popupType=alert&popupMessage=L'un des articles commandé n'est plus disponible dans la quantité demandée.");
            die();
        }
    }

    /**
     * renvoie sur la page où il faut remplir ses coordonnées bancaires, si on est pas connecté alors on renvoie sur la page de connexion
     * @return void
     */
    public function achat() {
        //met une variable paye à vrai pour que l'utilisateur soit directement redirigé vers la page du panier lorsqu'il se connectera
        $this->session->set_userdata("paye", true);
        if (!$this->session->has_userdata("user")) {
            redirect('User/login');
            die();
        }

        $user = $this->UserModel->findById($this->session->userdata("user")['id']);
        $data = array("user" => $user);

        if ($this->session->userdata("prix") == 0) {
            redirect("Panier");
            die();
            // Si dans l'url il y a "popupType" & "popupMessage" alors il faut afficher une popup
            // il faut donc mettre dans le type et le message demandé
        }
            
        if (isset($_REQUEST["popupType"], $_REQUEST["popupMessage"])) {
            $data["popupType"] = $_REQUEST["popupType"];
            $data["popupMessage"] = $_REQUEST["popupMessage"];
        }

        //compare les quantités acheter et en stock, cette fonction peut die et redirect sur panier avec une erreur
        $this->verifyQuantity($user->getId());

        //diminu la quantité commandé dans la bd
        $this->diminueQuantite($user->getId());

        $this->load->view("paiement", $data);
    }

    /**
     * annule l'achat du User et remet les quantiteCommande en stock
     * @return void
     */
    public function annuleAchat(){
        if (!$this->session->has_userdata("user") || !$this->session->userdata("paye")){
            redirect("");
        }
        $id = $this->session->userdata("user")["id"];
        $this->augmenteQuantite($id);
        $this->session->set_userdata("paye", false);
        redirect("Panier");
    }

    /**
     *  modifie les stock en fonction des quantites de chaque produit du panier
     * supprime tous les articles acheté du panier
     * si possible : envoie un mail pour confirmer l'achat + vérifie la carte bancaire
     * @return never
     */
    public function verifPaiement() {
        if (!$this->session->has_userdata("user")){
            redirect("");
        }

        //strip_tags permet de prévenir les injections
        $nom = strip_tags($this->input->post("nom"));
        $prenom = strip_tags($this->input->post("prenom"));
        $mail = strip_tags($this->input->post("mail"));
        $code_postal = strip_tags($this->input->post("code_postal"));
        $adresse_livraison = strip_tags($this->input->post("adresse"));
        $adresse = $adresse_livraison."|||".$code_postal;
        $cvv = strip_tags($this->input->post("cvv"));
        $card = strip_tags($this->input->post("card"));
        $date = strip_tags($this->input->post("date"));

        $data = array(
            "mail" => $mail,
            "dep" =>  $code_postal,
        );

        //vérification de la conformité de mail et code postal
        if (!$this->FunctionsModel->conforme($data)) {
            redirect("Panier/achat?popupType=alert&popupMessage=Le mail ou le code postal n'est pas valide");
            die();
        }

        if (!is_numeric($cvv) || strlen(strval($cvv)) != 3) {
            redirect("Panier/achat?popupType=alert&popupMessage=Le numéro du cvv doit être composé de 3 chiffres");
            die();
        }

        if (!is_numeric($card) || strlen(strval($card)) != 16) {
            redirect("Panier/achat?popupType=alert&popupMessage=Le numéro de carte doit être composé de 16 chiffres");
            die();
        }

        // vérification de la date
        $explodeDate = explode("/", strval($date));
        
        if (strpos($date, "/") == false || strlen($explodeDate[0]) != 2 || strlen($explodeDate[1]) != 2) {
            redirect("Panier/achat?popupType=alert&popupMessage=Le format de la date doit être MM/AA");
            die();
        }

        if ($explodeDate[0] < "1" || $explodeDate[0] > "12") {
            redirect("Panier/achat?popupType=alert&popupMessage=Le mois être compris entre 1 et 12");
            die();
        }

        if ($explodeDate[1] < date("y") || !is_numeric($explodeDate[1])){
            redirect("Panier/achat?popupType=alert&popupMessage=L'année doit être supérieure à l'année actuelle");
            die();

        }
        
        if (($explodeDate[1] == date("y") && ($explodeDate[0] < date("m"))) || !is_numeric($explodeDate[0])) {
            redirect("Panier/achat?popupType=alert&popupMessage=Le mois doit être supérieur au mois actuel");
            die();
        }
        $this->session->set_userdata("paye", false);
        $this->nouvelleCommande($adresse);
    }

    /**
     * créer une nouvelle commande et la met dans la bd
     * @param mixed $adresse
     * @return never
     */
    private function nouvelleCommande($adresse) {
        $this->load->model("CommandeModel");

        $id = $this->session->userdata("user")['id'];

        // les données constantes a envoyer a commandeModel
        $data = array(
            "id_user" => $id,
            "id_article" => "",
            "date_commande" => date('Y-m-d H:i:s'),
            "adresse_livraison" => $adresse,
            "quantite_commande" => "",
            "prix" => 0.0,
            "en_cours" => 1
        );

        // récupère tous les articles dans le panier
        $articles = $this->PanierModel->findByUserId($id);

        // pour tous les articles, on les ajoute à la base de données
        foreach ($articles as $article) {
            $prix = $this->ArticleModel->findById($article->getIdArticle())->getPrix();
            $data["id_article"] = $article->getIdArticle();
            $data["quantite_commande"] = $article->getQuantiteCommande();
            $data["prix"] = $prix;
            $this->CommandeModel->add($data);
        }

        // on vide le panier de l'utilisateur
        $this->videPanier($this->session->userdata("user")['id']);
        redirect("user/commandes?popupType=information&popupMessage=Commande confirmée");
    }
}

?>