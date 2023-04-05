<?php
require_once APPPATH.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR."ArticleEntity.php";
class ArticleModel extends CI_Model {
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * créer un tableau d'article avec ce qu'on récupère d'une procédure
     * @param mixed $in
     * @return array
     */
    private function jeVeuxUnTableauArticle($in) {
        $res = array();
        foreach ($in->result() as $row){
            $prod = new ArticleEntity;
            $prod->setId($row->id);
            $prod->setNom($row->nom);
            $prod->setPrix($row->prix);
            $prod->setQuantite($row->quantite);
            $prod->setDescription($row->description);
            $prod->setCategorie($row->categorie);
            $prod->setTaille($row->taille);
            $prod->setImage($row->image);
            array_push($res, $prod);
        }
        return $res;
    }

    /**
     * trouve tous les articles de la base de données
     * @return array
     */
    function findAll(){
        $q = $this->db->query("CALL findAllArticle()");
        $res = $this->jeVeuxUnTableauArticle($q);
        $q->next_result();
        $q->free_result();
        return $res;
    }

    /**
     * trouve tous les articles de la base de données sans doublons de nom
     * @return array
     */
    function findAllNom(){
        $q = $this->db->query("CALL findAllArticleUnParNom()");
        $res = $this->jeVeuxUnTableauArticle($q);
        $q->next_result();
        $q->free_result();
        return $res;

    }

    /**
     * Affiche les 4 derniers articles ajoutés dans la base de données sans doublons de nom
     * @return array(Article)
     */
    function findRecent(){
        $q = $this->db->query("CALL findRecentArticle()");
        $res = $this->jeVeuxUnTableauArticle($q);
        $q->next_result();
        $q->free_result();
        return $res;
    }

    /**
     * trouve l'article avec l'id correspondant
     * @param int $id
     * @return mixed
     */
    public function findById(int $id){
        $q = $this->db->query("CALL findArticleById(?)", array("id"=>$id));
        $res = $this->jeVeuxUnTableauArticle($q)[0];
        $q->next_result();
        $q->free_result();
        return $res;
    }

    /**
     * Renvoie un tableau d'article avec le nom entré en parametre
     * @param string $nom
     * @return array
     */
    public function findByNom(string $nom){
        $q = $this->db->query("CALL findArticleByNom(?)", array("nom"=>$nom));
        $res = $this->jeVeuxUnTableauArticle($q);
        $q->next_result();
        $q->free_result();
        return $res;
    }

    /**
     * trouve tous les articles d'une catégorie
     * @param string $cat
     * @return array
     */
    public function findByCategorie(string $cat){
        $q = $this->db->query("CALL findArticleByCategorie(?)", array("categorie"=>$cat));
        $res = $this->jeVeuxUnTableauArticle($q);
        $q->next_result();
        $q->free_result();
        return $res;
    }

    /**
     * trouve tous les articles d'une catégorie sans doublons de nom
     * @param string $cat
     * @return array
     */
    public function findByCategorieNom(string $cat){
        $q = $this->db->query("CALL findArticleByCategorieUnParNom(?)", array("categorie"=>$cat));
        $res = $this->jeVeuxUnTableauArticle($q);
        $q->next_result();
        $q->free_result();
        return $res;
    }

    /**
     * recherche tout les articles correspondant au string en entrée (dans le tableau)
     * recherche dans le nom, la description et la categorie en égalité non strict et dans l'id, la quantite et la taille en égalité strinct
     * @param array $data
     * @return array
     */
    public function recherche(array $data){
        $q = $this->db->query("CALL rechercheArticle(?)",$data);
        $res = $this->jeVeuxUnTableauArticle($q);
        $q->next_result();
        $q->free_result();
        return $res;
    }

    /**
     * recherche tout les articles correspondant au string en entrée (dans le tableau), ne renvoie qu'un articles par nom d'article
     * recherche dans le nom, la description et la categorie en égalité non strict et dans l'id, la quantite et la taille en égalité strinct
     * @param array $data
     * @return array
     */
    public function rechercheNom(array $data){
        $q = $this->db->query("CALL rechercheArticleUnParNom(?)",$data);
        $res = $this->jeVeuxUnTableauArticle($q);
        $q->next_result();
        $q->free_result();
        return $res;
    }

    /**
     * ajoute un article dans la bd
     * @param array $data
     * @return bool
     */
    public function add(array $data): bool {
        /*data(nom, prix, quantite, description, categorie, taille, image)*/
        $this->db->query("CALL addArticle(?,?,?,?,?,?,?)",$data);
        return $this->db->affected_rows()==1;
    }

    /**
     * modifie un article dans la bd sauf l'id
     * @param array $data
     * @return bool
     */
    public function update(array $data): bool {
        /*data(id, nom, prix, quantite, description, categorie, taille, image)*/
        $this->db->query("CALL updateArticle(?,?,?,?,?,?,?,?)",$data);
        return $this->db->affected_rows()==1;
    }

    /**
     * supprime un article dans la bd
     * @param array $data
     * @return bool
     */
    public function delete(array $data): bool{
        /*data(id)*/
        $this->db->query("CALL deleteArticle(?)", $data);
        return $this->db->affected_rows() == 1;
    }

    /**
     * diminue la quantite d'un article
     * @param ArticleEntity $article
     * @param int $quantite
     * @return void
     */
    public function diminueQuantite(ArticleEntity $article, int $minusQuantite) : bool {
        $id = $article->getId();
        $nom = $article->getNom();
        $prix = $article->getPrix();
        $quantite = $article->getQuantite() - $minusQuantite;
        $description = $article->getDescription();
        $categorie = $article->getCategorie();
        $taille = $article->getTaille();
        $image = $article->getImage();
        $data = array(
            "id" => $id,
            "nom" => $nom,
            "prix" => $prix,
            "quantite" => $quantite,
            "description" => $description,
            "categorie" => $categorie,
            "taille" => $taille,
            "image" => $image
        );
        return $this->update($data);
    }

    /**
     * augmente la quantite d'un article
     * @param ArticleEntity $article
     * @param int $quantite
     * @return void
     */
    public function augmenteQuantite(ArticleEntity $article, int $plusQuantite) : bool {
        $id = $article->getId();
        $nom = $article->getNom();
        $prix = $article->getPrix();
        $quantite = $article->getQuantite() + $plusQuantite;
        $description = $article->getDescription();
        $categorie = $article->getCategorie();
        $taille = $article->getTaille();
        $image = $article->getImage();
        $data = array(
            "id" => $id,
            "nom" => $nom,
            "prix" => $prix,
            "quantite" => $quantite,
            "description" => $description,
            "categorie" => $categorie,
            "taille" => $taille,
            "image" => $image
        );
        return $this->update($data);
    }

}
?>