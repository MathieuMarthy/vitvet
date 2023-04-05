<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR."UserEntity.php";
require APPPATH.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR."MailModel.php";
class User extends CI_Controller {

    private $MailModel;

    public function __construct()
	{
		parent::__construct();
		$this->load->model('UserModel');
        $this->load->model("FunctionsModel");
        $this->load->model("CommandeModel");
        $this->load->model("ArticleModel");

        $this->MailModel = MailModel::getInstance();
        
        //vérifie que le user existe toujours dans la base de données (si un Admin supprime un User, il sera déconnecté)
        if (isset($_SESSION["user"]) && is_null($this->UserModel->findById($this->session->userdata("user")["id"]))) {
            $this->session->unset_userdata("user");
            redirect(""); 
            die();
        }
	}


    /**
     * créer un mot de passe aléatoire 
     * @param int $length
     * @throws RangeException
     * @return string
     */
    private function random_string(int $length = 10): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $keyspace = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

        $pieces = [];
        $max = mb_strlen($keyspace, "8bit") - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        return implode("", $pieces);
    }

    /**
     * chiffre le mot de passe
     * @param string $pass
     * @return string
     */
    private function chiffre(string $pass){
        return password_hash($pass, PASSWORD_DEFAULT);
    }

    /**
     * vérifie que la taille des données ne soit pas trop grande par rapport à ce qui peut être stocké dans la bd
     * @param mixed $in
     * @return bool
     */
    private function taille($in){
        if (strlen($in["login"])>50 || empty($in["login"])
            || strlen($in["nom"])>100 || empty($in["nom"])
            || strlen($in["prenom"])>50 || empty($in["prenom"])
            || strlen($in["password"])>100 || empty($in["password"])
            || strlen($in["mail"])>100 || empty($in["mail"])
            || $in["departement"]<1000 || empty($in["departement"])
            || $in["departement"]>98000 || empty($in["departement"])
            || strlen($in["adresse"])>200 || empty($in["adresse"])
        ) {
            return false;
        }
        return true;
    }

    /**
     * ajoute tous les produits de la session dans le panier enregistré dans la base de données 
     * @param mixed $idUser
     * @return void
     */
    private function ajoutSessionPanier($idUser){
        $this->load->model('PanierModel'); //charge PanierModel pour l'ajout au panier des articles

        //ajoute le nombre d'article du panier dans la variable de session
        $_SESSION["nbArticles"] += $this->PanierModel->count($idUser);
        
        //si le panier a une taille de 0 (il n'y a rien dedans) alors on sort de la fonction
        $panier = $this->session->userdata("panier");
        if (sizeof($panier)==0){
            return;
        }
        $article = array_keys($panier); //récupère un tableau des clés du panier (panier = array(idArticle => quantite) devient array(chiffre => idArticle))

        //pour chaque article dans le panier on l'ajoute dans le panier de l'utilisateur ou on augmente la quantite si il y existe déjà
        foreach ($article as $id_article){
            $quantite = $panier[$id_article];
            $existe = $this->PanierModel->findOneLine(array('id_user' => $idUser, "id_article" => $id_article));
            if (count($existe) == 0) {
                $this->PanierModel->add(array("id_user" => $idUser, "id_article" => $id_article, "quantiteCommande" => $quantite));
            }else{
                $q = $existe[0]->getQuantiteCommande();
                $this->PanierModel->update(array("id_user" => $idUser, "id_article" => $id_article, "quantiteCommande" => $q+$quantite));
            }
        }
    }

    /**
     * affiche la page de connexion
     * @return void
     */
    public function login() {
        if ($this->session->has_userdata("user")){
            redirect("User/monCompte");
        }
        $data = array();

        // Si dans l'url il y a "popupType" & "popupMessage" alors il faut afficher une popup
        // il faut donc mettre dans le type et le message demandé
        if (isset($_REQUEST["popupType"], $_REQUEST["popupMessage"])) {
            $data["popupType"] = $_REQUEST["popupType"];
            $data["popupMessage"] = $_REQUEST["popupMessage"];
        }

        $this->load->view("connexion", $data);
    }

    /**
     * affiche la page du compte de l'utilisateur
     * @return void
     */
    public function monCompte() {
        //redirige si personne n'est connecté
        if (!$this->session->has_userdata("user")){
            redirect("");
        }
        $id = $this->session->userdata("user")["id"];
        $user = $this->UserModel->findById($id);
        $data = array("user" => $user);

        // Si dans l'url il y a "popupType" & "popupMessage" alors il faut afficher une popup
        // il faut donc mettre dans le type et le message demandé
        if (isset($_REQUEST["popupType"], $_REQUEST["popupMessage"])) {
            $data["popupType"] = $_REQUEST["popupType"];
            $data["popupMessage"] = $_REQUEST["popupMessage"];
        }

        $this->load->view("monCompte", $data);
    }

    /**
     * affiche la page de modification d'information et une pop-up si besoin
     * @return void
     */
    public function modifInfos() {
        if (!$this->session->has_userdata("user")){
            redirect("");
        }
        $id = $this->session->userdata("user")["id"];
        $user = $this->UserModel->findById($id);
        $data = array("user" => $user);

        // Si dans l'url il y a "popupType" & "popupMessage" alors il faut afficher une popup
        // il faut donc mettre dans le type et le message demandé
        if (isset($_REQUEST["popupType"], $_REQUEST["popupMessage"])) {
            $data["popupType"] = $_REQUEST["popupType"];
            $data["popupMessage"] = $_REQUEST["popupMessage"];
        }

        $this->load->view("modifInfos", $data);
    }


    /**
     * vérifie que les informations sont correctes lors de la connexion d'un User
     * @return never
     */
    public function loginCheck() {
        $login = strip_tags($this->input->post("id"));
        $pass = strip_tags($this->input->post('password'));
        $user = $this->UserModel->findByLogin($login);

        //redirige sur la page de connexion si il y a un problème
        if ($user==null || !$user->isValidPassword($pass)) {
            redirect("User/login?popupType=alert&popupMessage=Identifiant ou mot de passe incorrect");
            die();
        }

        if (!$user->getVerifier()) {
            $this->MailModel->sendVerification($user);
            redirect("User/mailvalide/false");
            die();
        }

        //création d'une session pour le user
        $this->session->set_userdata("user", 
                        array(
                        "id"=>$user->getId(),
                        "prenom"=>$user->getPrenom(),
                        "nom"=>$user->getNom(),
                        "login"=>$user->getLogin(),
                        "statut"=>$user->getStatut(),
                        "mail"=>$user->getMail()
                    ));

        // redirige sur la page admin si le user est un admin
        if ($user->getStatut()=="admin"){ 
            $this->session->unset_userdata("paye");
            redirect("Admin");
            die();
        }

        $idUser = $user->getId();
        $this->ajoutSessionPanier($idUser);

        //redirige vers l'achat du panier si le user s'est connecté au moment de payer, redirige sur la page d'accueil sinon
        if ($this->session->has_userdata("paye")){
            redirect('Panier');
        } else {
            redirect('Home');
        }
    }

    /**
     * Déconnecte le User et détruit la session
     * @return never
     */
    public function logout(){
        if (!$this->session->has_userdata("user")){
            redirect("");
        }
        $this->session->sess_destroy();
        redirect('');
    }

    /**
     * affiche la page de création de compte et une pop-up si besoin
     * @return void
     */
    public function register() {
        if ($this->session->has_userdata("user")){
            redirect("User/monCompte");
        }
        $data = array();

        // Si dans l'url il y a "popupType" & "popupMessage" alors il faut afficher une popup
        // il faut donc mettre dans le type et le message demandé
        if (isset($_REQUEST["popupType"], $_REQUEST["popupMessage"])) {
            $data["popupType"] = $_REQUEST["popupType"];
            $data["popupMessage"] = $_REQUEST["popupMessage"];
        }

        $this->load->view("creerUnCompte", $data);
    }

    /**
     * créer un compte utilisateur
     * @return never
     */
    public function createAccount() {
        $login = strip_tags($this->input->post('id'));
        $passBlanc = strip_tags($this->input->post('password'));
        if (empty($passBlanc)){
            redirect("User/register?popupType=alert&popupMessage=Le mot ne passe ne peut pas être vide");
            die();
        }
        $pass = $this->chiffre($passBlanc);
        $nom = strip_tags($this->input->post('nom'));
        $prenom = strip_tags($this->input->post('prenom'));
        $mail = strip_tags($this->input->post('mail'));
        $dep = strip_tags($this->input->post('dep'));
        $adresse = strip_tags($this->input->post('adresse'));
        $suite = $this->FunctionsModel->conforme(array("mail" => $mail, "dep" => $dep));

        if (!$suite) {
            redirect("User/register?popupType=alert&popupMessage=Le mail ou le code postal n'est pas conforme");
            die();
        }
        $db_debug = $this->db->db_debug; //permet d'éviter que les erreurs s'affiche à l'écran (en cas de problème d'attributs en double)
        $this->db->db_debug = FALSE;

        $data = array(
            "login" => $login,
            "nom" => $nom,
            "prenom" => $prenom,
            "password" => $pass,
            "statut" => "client",
            "mail" => $mail,
            "departement" => $dep,
            "adresse" => $adresse,
            "code_validation" => $this->random_string(24)
        );
        if (!$this->taille($data)){
            redirect("User/register?popupType=alert&popupMessage=Une ou plusieurs des valeurs rentrées sont nulles trop grandes");
            die();
        }

        if (isset($_POST["admin"]) && $this->session->userdata("user")["statut"] == "admin") {
            $data["statut"] = "admin";
        }

        $res = $this->UserModel->add($data);
        $this->db->db_debug = $db_debug;

        if (isset($_POST["admin"]) && $this->session->userdata("user")["statut"] == "admin") {
            redirect("Admin/listeUtilisateurs");
            die();
        }

        if ($res) {
            $user = $this->UserModel->findByLogin($login);  
            $this->MailModel->sendVerification($user);


            redirect("User/mailvalide/false");
            die();
        }

        redirect("User/register?popupType=alert&popupMessage=Un compte avec ce identifiant ou mail existe déjà");
    }


    /**
     * Met à jour les information d'un User
     * (Pour mettre à jour un compte il faut être connecté à celui-ci donc une session est set)
     * @return never
     */
    public function updateAccount() {
        if (!$this->session->has_userdata("user")){
            redirect("");
        }
        $id = $this->session->userdata("user")['id'];
        $login = $this->session->userdata("user")['login'];
        $pass = strip_tags($this->input->post('password'));
        $pass2 = strip_tags($this->input->post('password2'));
        $nom = strip_tags($this->input->post('nom'));
        $prenom = strip_tags($this->input->post('prenom'));
        $mail = $this->session->userdata("user")['mail'];
        $dep = strip_tags($this->input->post('dep'));
        $adresse = strip_tags($this->input->post('adresse'));
        $suite = $this->FunctionsModel->conforme(array("mail" => $mail, "dep" => $dep));

        if ($pass != $pass2) {
            redirect("user/modifInfos?popupType=alert&popupMessage=Les deux mots de passe ne correspondent pas");
            die();
        }

        if (!$suite) {
            redirect("user/modifInfos?popupType=alert&popupMessage=Le mail ou le département n'est pas valide");
            die();
        }
        
        $data = array(
            "id" => $id,
            "login" => $login,
            "nom" => $nom,
            "prenom" => $prenom,
            "password" => $this->chiffre($pass),
            "statut" => $this->session->userdata("user")["statut"],
            "mail" => $mail,
            "departement" => $dep,
            "adresse" => $adresse
        );
        
        //si le mot de passe n'est pas renseigner alors ça veut dire qu'il ne change pas
        if (empty($pass)) { //on ne vérifie pas pass2 car pass==pass2
            $user = $this->UserModel->findById($id);
            $data["password"] = $user->getPassword();
        }
        
        if (!$this->taille($data)) {
            redirect("User/monCompte?popupType=alert&popupMessage=Une ou plusieurs des valeurs rentrées sont nulles ou trop grandes");
            die();
        }

        $db_debug = $this->db->db_debug; //permet d'éviter que les erreurs s'affiche à l'écran (en cas de problème d'attributs en double)
        $this->db->db_debug = FALSE;
        $res = $this->UserModel->update($data);
        $this->db->db_debug = $db_debug;

        if ($res) {
            $user = $this->UserModel->findById($id);
            $this->session->unset_userdata("user");
            $this->session->set_userdata("user",
                    array(
                    "id"=>$user->getId(),
                    "login"=>$user->getLogin(),
                    "statut"=>$user->getStatut(),
                    "mail"=>$user->getMail(),
                    "prenom"=>$user->getPrenom(),
                    "nom"=>$user->getNom(),
                ));
            redirect("User/monCompte?popupType=information&popupMessage=Votre compte a bien été mis à jour");
        } else {
            redirect("User/monCompte?popupType=alert&popupMessage=Une erreur est survenue ou vous n'avez pas modifier vos informations");
        }
    }


    /**
     * supprime un compte utilisateur
     * (Pour mettre à jour un compte il faut être connecté à celui-ci donc une session est set)
     * @return never
     */
    public function deleteAccount() {
        if (!$this->session->has_userdata("user")){
            redirect("");
        }
        $id = $this->session->userdata("user")["id"];
        $res = $this->UserModel->deleteById(array("id"=>$id));
        if ($res){
            $this->session->unset_userdata("user");
            redirect('Home');
            die();
        }
        redirect("User/monCompte");
    }

    /**
     * affiche une commande d'un User (on peut y voir chaques articles avec leur quantiteCommande)
     * @param mixed $date
     * @return void
     */
    public function commande($date) {
        $date = urldecode($date);
        $id = $this->session->userdata("user")["id"];
        $commandes = $this->CommandeModel->findByUserIdAndDate($id, $date);

        //pas dans la boucle du dessous pour éviter tous problèmes avec le tableau `articles` qu'on rempli ici et qu'on utilise en dessous
        $articles = array();
        foreach ($commandes as $commande) {
            array_push($articles, $this->ArticleModel->findById($commande->getIdArticle()));
        }
        
        $quantite = 0;        
        $prix = 0;
        for ($i = 0; $i < count($commandes); ++$i) {
            $quantite += $commandes[$i]->getQuantiteCommande();
            $prix += $articles[$i]->getPrix() * $commandes[$i]->getQuantiteCommande();
        }

        $data = array(
            "commandes" => $commandes,
            "articles" => $articles,
            "quantite" => $quantite,
            "prix" => $prix
        );

        $this->load->view("commande", $data);
    }

    /**
     * met la date dans un jolie format d'affichage
     * @param string $date
     * @return string
     */
    private function jolieDate(string $date) : string {
        $explodeDate = explode(" ", strval($date));
        $explodeDebutDate = explode("-", $explodeDate[0]);
        $resDebutDate = "$explodeDebutDate[2]-$explodeDebutDate[1]-$explodeDebutDate[0]";
        $finJolieDate = $explodeDate[1];
        return "$resDebutDate à $finJolieDate";
    }

    /**
     * regroupe les commandes par date
     * @param array $data
     * @return array
     */
    private function regroupeParDate(array $data) {
        $res = array();
        $oldDate = $data[0]->getDate();

        $byDate = array(
            "date" => $this->jolieDate($oldDate),
            "prix" => 0.0,
            "quantite"=> 0,
            "adresse" => str_replace("|||", " ", $data[0]->getAdresseLivraison()),
            "statut" => $data[0]->getEnCours(),
            "dateSql" => $oldDate
        );

        foreach ($data as $val) {
            if ($oldDate != $val->getDate()) {
                array_push($res, $byDate);
                $oldDate = $val->getDate();
                $byDate["date"] = $this->jolieDate($oldDate);
                $byDate["prix"] = 0.0;
                $byDate["quantite"] = 0;
                $byDate["adresse"] = str_replace("|||", " ", $val->getAdresseLivraison());
                $byDate["statut"] = $val->getEnCours();
                $byDate["dateSql"] = $oldDate;
            }
            $this->load->model("ArticleModel");
            $article = $this->ArticleModel->findById($val->getIdArticle());
            $byDate["prix"] += $article->getPrix()*$val->getQuantiteCommande();
            $byDate["quantite"] += $val->getQuantiteCommande();
        }
        array_push($res, $byDate);
        return $res;
    }

    /**
     * affiche toute les commandes d'un User (on peut y voir une brochure récapitulative avec le prix total, l'adresse de livraison ...)
     * @return void
     */
    public function commandes() {
        if (!$this->session->has_userdata("user")){
            redirect("");
        }
        $this->load->model("CommandeModel");
        $id = $this->session->userdata("user")['id'];
        //les commandes récupéré sont triées par date de commande pour que la fontion regroupeParDate fontionne bien
        $commandes = $this->CommandeModel->findByUserId($id);

        if (is_null($commandes)) {
            $commandes = array();
        }

        if (!empty($commandes)) {
            $commandes = $this->regroupeParDate($commandes);
        }

        $data = array(
            "commandes" => $commandes,
        );

        // Si dans l'url il y a "popupType" & "popupMessage" alors il faut afficher une popup
        // il faut donc mettre dans le type et le message demandé
        if (isset($_REQUEST["popupType"], $_REQUEST["popupMessage"])) {
            $data["popupType"] = $_REQUEST["popupType"];
            $data["popupMessage"] = $_REQUEST["popupMessage"];
        }

        $this->load->view("commandesCompte", $data);
    }

    /**
     * affiche la page de de mot de passe oublié
     * @return void
     */
    public function motdepasseoublie() {
        $data = array();

        // Si dans l'url il y a "popupType" & "popupMessage" alors il faut afficher une popup
        // il faut donc mettre dans le type et le message demandé
        if (isset($_REQUEST["popupType"], $_REQUEST["popupMessage"])) {
            $data["popupType"] = $_REQUEST["popupType"];
            $data["popupMessage"] = $_REQUEST["popupMessage"];
        }

        $this->load->view("oublie", $data);
    }

    /**
     * change le mot de passe d'un utilisateur et lui envoie ce nouveau mdp par mail
     * @return never
     */
    public function resetmotdepasse() {

        $login = strip_tags($this->input->post("login"));
        $mail = strip_tags($this->input->post("mail"));

        if (!isset($login, $mail)) {
            redirect("User/motdepasseoublie?popupType=alert&popupMessage=Veuillez remplir tous les champs");
        }

        $user = $this->UserModel->findByLogin($login);

        if (is_null($user)) {
            redirect("User/motdepasseoublie?popupType=alert&popupMessage=L'identifiant n'existe pas");
        }

        if ($user->getMail() != $mail) {
            redirect("User/motdepasseoublie?popupType=alert&popupMessage=L'adresse mail ne correspond pas a l'identifiant");
        }

        $newPassword = $this->random_string();
        $hashedPassword = $this->chiffre($newPassword);
        $this->UserModel->changePassword($user, $hashedPassword);
    

        $this->MailModel->sendPasswordReset($mail, $newPassword);


        redirect("User/login?popupType=information&popupMessage=Un mail vous a été envoyé avec votre nouveau mot de passe");

    }


    /**
     * Confirme le mail d'un utilisateur avec son code de verification et son login
     * @param string $login
     * @param string $code
     */
    public function valideMail(string $login, string $code) {
        if (!isset($login, $code)) {
            redirect("Home");
        }

        // si l'utilisateur n'existe pas ou qu'il est déjà verifié. urldecode permet que le;login fonctionne si on a un prénom avec des caractère avec des accents tel que pacôme
        $user = $this->UserModel->findByLogin(urldecode($login));
        if (is_null($user) || $user->getVerifier()) {
            redirect("Home");
        }


        // si le code est bon
        if ($user->getCodevalidation() == $code) {
            $data = array(
                "id" => $user->getId()
            );

            // on met le user en vérifié et lui envoie un mail de bienvenue
            $this->UserModel->verifieUser($data);
            $this->MailModel->sendWelcome($user);

            //création d'une session pour le user
            $this->session->set_userdata("user", 
                        array(
                        "id"=>$user->getId(),
                        "prenom"=>$user->getPrenom(),
                        "nom"=>$user->getNom(),
                        "login"=>$user->getLogin(),
                        "statut"=>$user->getStatut(),
                        "mail"=>$user->getMail()
            ));

            redirect("User/mailvalide/true");
            die();
        }
        redirect("Home");
    }


    public function mailvalide(string $verifie) {

        $data = array(
            "verifie" => $verifie == "true"
        );

        // Si dans l'url il y a "popupType" & "popupMessage" alors il faut afficher une popup
        // il faut donc mettre dans le type et le message demandé
        if (isset($_REQUEST["popupType"], $_REQUEST["popupMessage"])) {
            $data["popupType"] = $_REQUEST["popupType"];
            $data["popupMessage"] = $_REQUEST["popupMessage"];
        }


        $this->load->view("mailValide", $data);
    }


    public function resend() {
        $login = strip_tags($this->input->post("login"));

        if (!isset($login)) {
            redirect("User/mailvalide/false?popupType=alert&popupMessage=Veuillez remplir tous les champs");
        }

        $user = $this->UserModel->findByLogin($login);
        // si l'utilisateur n'existe pas ou qu'il est déjà verifié on redirige vers Home
        if (is_null($user)) {
            redirect("User/mailvalide/false?popupType=alert&popupMessage=Cet identifiant n'existe pas");
            die();
        }

        if ($user->getVerifier()) {
            redirect("User/login?popupType=information&popupMessage=Ce compte est déjà validé");
            die();
        }

        $this->MailModel->sendVerification($user);
        redirect("User/mailvalide/false?popupType=information&popupMessage=un nouveau de confirmation vous à été envoyé");
    }
}
?>
