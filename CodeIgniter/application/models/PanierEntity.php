<?php
class PanierEntity
{
    private int $idUser;
    private int $idArticle;
    private int $quantiteCommande;

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

}