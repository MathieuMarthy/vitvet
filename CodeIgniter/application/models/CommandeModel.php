<?php
require_once APPPATH.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR."CommandeEntity.php";
class CommandeModel extends CI_Model
{
    /**
     * créer un tableau de commande avec ce qu'on récupère d'une procédure
     * @param mixed $in
     * @return array
     */
    private function jeVeuxUnTableauCommandes($in) {
        $res = array();
        foreach ($in->result() as $row){
            $prod = new CommandeEntity;
            $prod->setIdUser($row->id_user);
            $prod->setIdArticle($row->id_article);
            $prod->setQuantiteCommande($row->quantite_commande);
            $prod->setDate($row->date_commande);
            $prod->setAdresseLivraison($row->adresse_livraison);
            $prod->setEnCours($row->en_cours);
            $prod->setPrix($row->prix);
            array_push($res, $prod);
        }
        return $res;
    }

    /**
     * trouve toutes les commandes de la base de données
     * @return mixed
     */
    function findAll(){
        $this->db->select('*');
        $q = $this->db->get('Commande');
        $response = $q-> custom_result_object("CommandeEntity");
        return $response;
    }

    /**
     * trouve toutes les commandes d'un User
     * @param int $id_user
     * @return array|null
     */
    public function findByUserId(int $id_user):?Array{
		$q = $this->db -> query("CALL findCommandeByUserId(?)", array("id_user" => $id_user));
        $res = $this->jeVeuxUnTableauCommandes($q);
        $q->next_result();
        $q->free_result();
        return $res;
    }

    /**
     * trouve toutes les commandes d'un User d'une certaine date
     * @param int $id_user
     * @param string $date
     * @return array
     */
    public function findByUserIdAndDate(int $id_user, string $date){
        $q = $this->db->query("CALL findCommandeByUserIdAndDate(?,?)", array("id_user" => $id_user, "date_commande" => $date));
        $res = $this->jeVeuxUnTableauCommandes($q);
        $q->next_result();
        $q->free_result();
		return $res;
    }

    /**
     * ajoute une commande à la bd
     * @param array $data
     * @return bool
     */
    public function add(array $data): bool{
        /*data(id_user, id_article, date_commande, adresse_livraison, quantite_commande, prix, en_cours)*/
        $this->db->query("CALL addCommande(?,?,?,?,?,?,?)", $data);
        return $this->db->affected_rows() == 1;
    }

    /**
     * modifie une commande (en_cours seulement) en_cours est le statut de la commande
     * @param array $data
     * @return bool
     */
    public function update(array $data):bool{
        /*data(id_user, date_commande, en_cours)*/
        $this->db->query("CALL updateCommande(?,?,?)",$data);
        return $this->db->affected_rows()==1;
    }

    /**
     * supprime une commande de la bd
     * @param array $data
     * @return bool
     */
    public function delete(array $data):bool{
        /*data(id_user, id_article)*/
        $this->db->query("CALL deleteCommande(?,?)",$data);
        return $this->db->affected_rows()==1;
    }

    /**
     * supprime toute les commandes d'un user
     * @param array $data
     * @return bool
     */
    public function deleteUserCommande(array $data):bool{
        /*data(id_user)*/
        $this->db->query("CALL deleteUserCommande(?)",$data);
        return $this->db->affected_rows()==1;
    }

    /**
     * Met toutes les commande d'un User d'une date à livré (en_cours=0)
     * @param array $data
     * @return bool
     */
    public function estLivre(array $data): bool
    {
        /*data(id_user, date)*/
        $this->db->query("CALL updateCommande(?,?,0)", $data);
        return $this->db->affected_rows() == 1;
    }
    
}

?>