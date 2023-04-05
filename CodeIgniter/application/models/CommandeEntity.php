<?php
class CommandeEntity
{
    private int $idUser;
    private int $idArticle;
    private string $date;
    private string $adresse_livraison;
    private int $quantiteCommande;
    private int $enCours; #0==false, 1==true, Bool pas possible en sql
    private float $prix;

    public function getIdUser():int{
		return $this->idUser;
	}

	public function setIdUser(int $idUser){
		$this->idUser = $idUser;
	}

	public function getIdArticle():int{
		return $this->idArticle;
	}

	public function setIdArticle(int $idArticle){
		$this->idArticle = $idArticle;
	}

	public function getQuantiteCommande():int{
		return $this->quantiteCommande;
	}

	public function setQuantiteCommande(int $new){
		$this->quantiteCommande = $new;
	}

    public function getDate(){
        return $this->date;
    }

    public function setDate(string $new){
        $this->date = $new;
    }

    public function getAdresseLivraison(){
        return $this->adresse_livraison;
    }

    public function setAdresseLivraison(string $new){
        $this->adresse_livraison = $new;
    }

    public function getEnCours(){
        return $this->enCours;
    }

    public function setEnCours(int $new){
        $this->enCours = $new;
    }

    public function getPrix(){
        return $this->prix;
    }

    public function setPrix(float $new){
        $this->prix = $new;
    }
    
}

?>