<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR."ArticleEntity.php";

class Boutique extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('ArticleModel');
    $this->load->model("FunctionsModel");

  }

  /**
   * affiche tous les articles sans doublons de nom
   * @return void
   */
  public function index()
  {
    $products = $this->ArticleModel->findAllNom();
    $data = array("articles" => $products);
    $this->load->view('boutique', $data);
  }

  /**
   * affiche tous les articles de catégories pantalons sans doublons de nom
   * @return void
   */
  public function pantalon()
  {
    $products = $this->ArticleModel->findByCategorieNom("pantalon");
    $data = array("articles" => $products);
    $this->load->view('boutique', $data);
  }

  /**
   * affiche tous les articles de catégories t-shirts sans doublons de nom
   * @return void
   */
  public function tshirt()
  {
    $products = $this->ArticleModel->findByCategorieNom("Tshirt");
    $data = array("articles" => $products);
    $this->load->view('boutique', $data);
  }

  /**
   * affiche tous les articles de catégories chaussure sans doublons de nom
   * @return void
   */
  public function chaussure()
  {
    $products = $this->ArticleModel->findByCategorieNom("chaussure");
   
    $data = array("articles" => $products);
    $this->load->view('boutique', $data);
  }

  /**
   * affiche l'article correspondant à l'id en entrée
   * @param mixed $id
   * @return void
   */
  public function article($id)
  {
    $products = $this->ArticleModel->findById($id);
    $autreProd = $this->ArticleModel->findByNom($products->getNom());
    $data = array("article" => $products, "autres" => $autreProd);
    $this->load->view('article', $data);
  }

  /**
   * affiche un article correpondant pas forcément en entier avec le nom en entrée
   * @return void
   */
  public function recherche(){
    $data = array("recherche" => $this->input->get("recherche"));
    $products = $this->ArticleModel->rechercheNom($data);
    $data = array("articles" => $products);
    $this->load->view('boutique', $data);
  }
}
