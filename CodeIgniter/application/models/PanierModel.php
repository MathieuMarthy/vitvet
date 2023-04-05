<?php
require_once APPPATH.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR."PanierEntity.php";
class PanierModel extends CI_Model {

    /**
     * créer un tableau de Panier avec ce qu'on récupère d'une procédure
     * @param mixed $in
     * @return array
     */
    private function jeVeuxUnTableauPanier($in) {
        $res = array();
        foreach ($in->result() as $row){
            $prod = new PanierEntity;
            $prod->setIdUser($row->id_user);
            $prod->setIdArticle($row->id_article);
            $prod->setQuantiteCommande($row->quantite_commande);
            array_push($res, $prod);
        }
        return $res;
    }

    /**
     * renvoie une ligne de commande. Cette ligne met en relation un User avec un Article et une quantite commandé
     * @param array $data
     * @return array
     */
    public function findOneLine(array $data){
        /*data(id_user, id_article)*/
        $q = $this->db->query("CALL findOneLine(?,?)", $data);
        $res = $this->jeVeuxUnTableauPanier($q);
        $q->next_result();
        $q->free_result();
        return $res;
    }

    /**
     * renvoie le nombre d'article que le User a dans son panier
     * @param int $idUser
     * @return int
     */
    public function count(int $idUser){
        $q = $this->db->query("CALL compteNombreArticleDuPanier(?)", array("id_user" => $idUser));
        $res = $q->result()[0]->nb;
        $res = intval($res);
        $q->next_result();
        $q->free_result();
        return $res;
    }

    /**
     * trouve le panier d'un User
     * @param int $id_user
     * @return array
     */
    public function findByUserId(int $id_user){
        $q = $this->db->query("CALL findPanierByUserId(?)", array("id_user"=>$id_user));
        $res = $this->jeVeuxUnTableauPanier($q);
        $q->next_result();
        $q->free_result();
        return $res;
    }

    /**
     * ajoute une ligne dans la table Panier
     * @param array $data
     * @return bool
     */
    public function add(array $data):bool{
        /*data(id_user, id_article, quantiteCommande)*/
        $this->db->query("CALL addPanier(?,?,?)",$data);
        return $this->db->affected_rows() == 1;
    }

    /**
     * modifie une ligne du Panier (quantiteCommande seulement)
     * @param array $data
     * @return bool
     */
    public function update(array $data):bool{
        /*data(id_user, id_article, quantiteCommande)*/
        $this->db->query("CALL updatePanier(?,?,?)",$data);
        return $this->db->affected_rows() == 1;
    }

    /**
     * supprime une ligne d'un panier d'un user correspondant à l'id article
     * @param array $data
     * @return bool
     */
    public function delete(array $data):bool{
        /*data(id_user, id_article)*/
        $this->db->query("CALL deletePanier(?,?)",$data);
        return $this->db->affected_rows() == 1;
    }

    /**
     * supprime tout le panier d'un user
     * @param array $data
     * @return bool
     */
    public function deleteUserPanier(array $data):bool{
        /*data(id_user)*/
        $this->db->query("CALL deleteUserPanier(?)",$data);
        return $this->db->affected_rows() >= 1;
    }
}
?> 