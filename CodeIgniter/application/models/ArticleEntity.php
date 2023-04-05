<?php
class ArticleEntity
{
    private int $id;
    private string $nom;
    private float $prix;
    private int $quantite;
    private string $description;
    private string $categorie;
    private string $taille;
    private string $image;

    public function getId():int{
		return $this->id;
	}

	public function setId(int $id){
		$this->id = $id;
	}

	public function getNom():string{
		return $this->nom;
	}

	public function setNom(string $nom){
		$this->nom = $nom;
	}

	public function getPrix():float{
		return $this->prix;
	}

	public function setPrix(float $prix){
		$this->prix = $prix;
	}

	public function getQuantite():int{
		return $this->quantite;
	}

	public function setQuantite(int $quantite){
		$this->quantite = $quantite;
	}

	public function getDescription():string{
		return $this->description;
	}

	public function setDescription(string $description){
		$this->description = $description;
	}

	public function getCategorie():string{
		return $this->categorie;
	}

	public function setCategorie(string $categorie){
		$this->categorie = $categorie;
	}

	public function getTaille():string{
		return $this->taille;
	}

	public function setTaille(string $taille){
		$this->taille = $taille;
	}

	public function getImage():string{
		return $this->image;
	}

	public function setImage(string $image){
		$this->image = $image;
	}

}
