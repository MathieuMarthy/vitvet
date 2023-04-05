<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR."MailModel.php";

class Contact extends CI_Controller {

    private $MailModel;

    public function __construct() {
        parent::__construct();

        //créer une variable de session pour le panier et pour le nombre d'article
        $this->load->model("FunctionsModel");

        $this->MailModel = MailModel::getInstance();
  }

    /**
     * affiche la page de contact
     * @return void
     */
    public function index() {
        if ($this->session->has_userdata("user")){
            $data = array(
                "prenom" => $this->session->userdata("user")["prenom"],
                "nom" => $this->session->userdata("user")["nom"],
                "mail" => $this->session->userdata("user")["mail"],
            );
        }else{
            $data = array(
                "prenom" => "",
                "nom" => "",
                "mail" => "",
            );
        }

        // Si dans l'url il y a "popupType" & "popupMessage" alors il faut afficher une popup
        // il faut donc mettre dans le type et le message demandé
        if (isset($_REQUEST["popupType"], $_REQUEST["popupMessage"])) {
            $data["popupType"] = $_REQUEST["popupType"];
            $data["popupMessage"] = $_REQUEST["popupMessage"];
        }

        $this->load->view("contact", $data);
    }

    /**
     * affiche la page de contact avec la pop-up
     * @return void
     */
    public function sendMessage() {
        $prenom = strip_tags($this->input->post("prenom"));
        $nom = strip_tags($this->input->post("nom"));
        $mail = strip_tags($this->input->post("mail"));
        $message = strip_tags($this->input->post("message"));

        if (!isset($prenom, $nom, $mail, $message) || empty($prenom) || empty($nom) || empty($mail) || empty($message)) {
            redirect("Contact/index?popupType=alert&popupMessage=Il manque des champs");
        }

        $data = array(
            "prenom" => $prenom,
            "nom" => $nom,
            "message" => $message,
        );

        $this->MailModel->sendContact($mail, $data);

        redirect("Contact/index?popupType=information&popupMessage=Votre message a bien été envoyé");
    }
}
