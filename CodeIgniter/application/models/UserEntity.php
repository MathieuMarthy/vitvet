<?php
class UserEntity
{
    private int $id;
    private string $login;
    private string $nom;
    private string $prenom;
    private string $password;
    private string $statut;
    private string $mail;
    private int $departement;
    private string $adresse;
	private string $codevalidation;
	private bool $verifier;

    public function isValidPassword(string $password):bool {
        return password_verify($password, $this->password);
    }

    public function getId() : int{
        return $this->id;
    }

    public function setId(int $new){
        $this->id = $new;
    }

    public function getLogin() : string{
        return $this->login;
    }

    public function setLogin(string $new){
        $this->login = $new;
    }

    public function getNom() : string{
		return $this->nom;
	}

	public function setNom(string $nom){
		$this->nom = $nom;
	}

	public function getPrenom():string{
		return $this->prenom;
	}

	public function setPrenom(string $prenom){
		$this->prenom = $prenom;
	}

	public function getPassword():string{
		return $this->password;
	}

	public function setPassword(string $password){
		$this->password = password_hash($password, PASSWORD_DEFAULT);
	}

    public function setEncryptedPassword(string $password): void{
    $this->password = $password;
    }

	public function getStatut(): string{
		return $this->statut;
	}

	public function setStatut(string $statut){
		$this->statut = $statut;
	}

	public function getMail(): string{
		return $this->mail;
	}

	public function setMail(string $mail){
		$this->mail = $mail;
	}

	public function getDepartement(): int{
		return $this->departement;
	}

	public function setDepartement(int $departement){
		$this->departement = $departement;
	}

	public function getAdresse(): string{
		return $this->adresse;
	}

	public function setAdresse(string $adresse){
		$this->adresse = $adresse;
	}

	public function getCodeValidation(): string{
		return $this->codevalidation;
	}

	public function setCodeValidation(string $codevalidation) {
		$this->codevalidation = $codevalidation;
	}

	public function getVerifier() {
		return $this->verifier;
	}

	public function setVerifier(int $verifier) {
		$this->verifier = $verifier == 1;
	}

}
?>