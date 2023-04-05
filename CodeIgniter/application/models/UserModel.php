<?php
require_once APPPATH.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR."UserEntity.php";
class UserModel extends CI_Model {
    
    /**
     * créer un tableau de User avec ce qu'on récupère d'une procédure
     * @param mixed $in
     * @return array
     */
    private function jeVeuxUnTableauUser($in) {
        $res = array();
        foreach ($in->result() as $row){
            $prod = new UserEntity;
            $prod->setId($row->id);
            $prod->setlogin($row->login);
            $prod->setNom($row->nom);
            $prod->setPrenom($row->prenom);
            $prod->setEncryptedPassword($row->password);
            $prod->setStatut($row->statut);
            $prod->setMail($row->mail);
            $prod->setDepartement($row->departement);
            $prod->setAdresse($row->adresse);
            $prod->setCodeValidation($row->code_validation);
            $prod->setVerifier($row->verifier);

            array_push($res, $prod);
        }
        return $res;
    }


    /**
     * trouve tous les User
     * @return array
     */
    function findAll(){
        $q = $this->db->query("CALL findAllUser()");
        $res = $this->jeVeuxUnTableauUser($q);
        $q->next_result();
        $q->free_result();
        return $res;
    }

    /**
     * trouve le User correspondant à l'id en entrée
     * @param int $id
     * @return mixed
     */
    public function findById(int $id){
        $q = $this->db->query("CALL findUserById(?)", array("id"=>$id));

        if ($this->db->affected_rows() != 1) {
            return null;
        }

        $res = $this->jeVeuxUnTableauUser($q)[0];
        $q->next_result();
        $q->free_result();
        return $res;
    }

    /**
     * trouve le User avec le login en entrée
     * @param string $login
     * @return mixed
     */
    public function findByLogin(string $login){
        $q = $this->db->query("CALL findUserByLogin(?)", array("login"=>$login));

        if ($this->db->affected_rows() != 1) {
            return null;
        }

        $res = $this->jeVeuxUnTableauUser($q)[0];
        $q->next_result();
        $q->free_result();
        return $res;
    }

    /**
     * vérifie si un User est Admin
     * @param mixed $id
     * @return bool
     */
    public function isAdmin($id): bool {
        $user = $this->findById($id);

        if (is_null($user)) {
            return false;
        }
        return $user->getStatut() == "admin";
    }

    /**
     * recherche tout les users correspondant au string en entrée (dans le tableau)
     * recherche en égalité non strict dans l'id, le login, le nom, le prenom, le statut, l'adresse mail et le code postal
     * @param array $data
     * @return array
     */
    public function recherche(array $data){
        $q = $this->db->query("CALL rechercheUser(?)", $data);
        $res = $this->jeVeuxUnTableauUser($q);
        $q->next_result();
        $q->free_result();
        return $res;
    }

    /**
     * ajoute un User à la bd
     * @param array $data
     * @return bool
     */
    public function add(array $data):bool{
        /*data(login, nom, prenom, pass, statut, mail, departement, adresse, code_validation)*/
        $this->db->query("CALL addUser(?,?,?,?,?,?,?,?, ?)",$data);
        return $this->db->affected_rows() == 1;
    }


    /**
     * change le mot de passe d'un user
     * 
     * @param UserEntity $user
     * @param string $newPassword
     */
    public function changePassword(UserEntity $user, string $newPassword) {
        $this->update(
            array(
                "id" => $user->getId(),
                "login" => $user->getLogin(),
                "nom" => $user->getNom(),
                "prenom" => $user->getPrenom(),
                "pass" => $newPassword,
                "statut" => $user->getStatut(),
                "mail" => $user->getMail(),
                "departement" => $user->getDepartement(),
                "adresse" => $user->getAdresse()
            )
            );
    }
    
    /**
     * modifie les informations d'un User (sauf l'id)
     * @param array $data
     * @return bool
     */
    public function update(array $data):bool{
        /*data(id, login, nom, prenom, pass, statut, mail, departement, adresse)*/
        $this->db->query("CALL updateUser(?,?,?,?,?,?,?,?,?)",$data);
        return $this->db->affected_rows()==1;
    }

    /**
     * supprime un User grace à son id
     * @param array $data
     * @return bool
     */
    public function deleteById(array $data):bool{
        /*data(id)*/
        $this->db->query("CALL deleteUserById(?)",$data);
        return $this->db->affected_rows()==1;
    }

    /**
     * supprime un User grace à son login
     * @param array $data
     * @return bool
     */
    public function deleteByLogin(array $data):bool{
        /*data(id)*/
        $this->db->query("CALL deleteUserByLogin(?)",$data);
        return $this->db->affected_rows()==1;
    }


    /**
     * Valide le compte d'un utilisateur
     * @param array $data
     * @return bool
     */
    public function verifieUser(array $data): bool{
        /*data(id)*/
        $this->db->query("CALL verifMailOk(?)", $data);
        return $this->db->affected_rows() == 1;
    }

}
?>