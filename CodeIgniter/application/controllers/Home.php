<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function __construct() {
		parent::__construct();
        $this->load->model("FunctionsModel");
		$this->load->model('ArticleModel');
	}

	/**
	 * affiche la page d'accueil avec les 4 articles les plus récents sans doublons de nom
	 * @return void
	 */
	public function index() {
		$products = $this->ArticleModel->findRecent();
		$data = array("articles" => $products);
		$this->load->view('accueil', $data);
	}

	/**
	 * affiche la page de condition général d'utilisation
	 * @return void
	 */
	public function cgu() {
		$this->load->view("cgu");
	}

	/**
	 * affiche les crédits sur site
	 * @return void
	 */
	public function credits() {
		$this->load->view("credits");	
	}
}
