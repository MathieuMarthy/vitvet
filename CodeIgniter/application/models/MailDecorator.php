<?php

require_once APPPATH.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR."UserEntity.php";
require APPPATH."..".DIRECTORY_SEPARATOR."system".DIRECTORY_SEPARATOR."core".DIRECTORY_SEPARATOR."Model.php";

/**
 * Classe qui permet d'envoyer des mails, à utiliser avec les décorateurs
 */
class Mail extends CI_Model {

    public string $image_logo = "https://media.discordapp.net/attachments/1029094521461538917/1061991061649637421/image-3.png";

    public string $contactMail = "vitvet@contact.fr";
    public string $defaultMailType = "html";
    public string $myMail = "l4p0ubelle@gmail.com";

    public function __construct() {
        $this->load->library("email");

        $config["mailtype"] = $this->defaultMailType;
        $this->email->initialize($config);
    }

    function send(string $sender, string $destination, string $subject, string $file, array $data): bool {
        $sender_name = explode("@", $sender)[0];
        $this->email->from($sender, $sender_name);
        $this->email->to($destination);
        $this->email->subject($subject);

        // on recupere le contenu du fichier
        $email_html = file_get_contents($file);

        // remplacement des variables dans le html
        foreach ($data as $key => $value) {
            $email_html = str_replace($key, $value, $email_html);
        }
        $this->email->message($email_html);

        // envoi du mail
        return $this->email->send();     
    }
}

/**
 * Décorateur de la classe Mail, permet d'envoyer des mails de vérification
 */
class DecoratorVerif extends Mail {

    private Mail $mail;
    private $subject = "Validation Mail VitVet";
    private $file = "assets/mail/verification.html";
    private UserEntity $user;

    public function __construct(UserEntity $user) {
        $this->mail = new Mail;
        $this->user = $user;
    }

    function sendEmail(): bool {
        $data = array(
            "{{PRENOM}}" => $this->user->getPrenom(),
            "{{CODEVALIDATION}}" => $this->user->getCodevalidation(),
            "{{LOGIN}}" => $this->user->getLogin(),
            "{{IMAGE1}}" => $this->image_logo,
            "{{IMAGE2}}" => "https://media.discordapp.net/attachments/1029094521461538917/1062327888306778122/image.png"
        );

        return $this->mail->send($this->contactMail, $this->user->getMail(), $this->subject, $this->file, $data);
    }
}


/**
 * Décorateur de la classe Mail, permet d'envoyer des mails de retour utilisateurs
 */
class DecoratorContact extends Mail {

    private Mail $mail;
    private $subject = "Contact VitVet";
    private $file = "assets/mail/contact.html";
    private string $sender;
    private array $data;

    public function __construct(string $sender, array $data) {
        $this->mail = new Mail;
        $this->sender = $sender;
        $this->data = $data;
    }

    function sendEmail(): bool {
        $data = array(
            "{{MAIL}}" => $this->sender,
            "{{PRENOM}}" => $this->data["prenom"],
            "{{NOM}}" => $this->data["nom"],
            "{{MESSAGE}}" => $this->data["message"],
            "{{IMAGE1}}" => "https://media.discordapp.net/attachments/1029094521461538917/1062029021795336233/image-1.png",
            "{{IMAGE2}}" => $this->image_logo
        );

        return $this->mail->send($this->sender, $this->myMail, $this->subject, $this->file, $data);
    }
}


/**
 * Décorateur de la classe Mail, permet d'envoyer des mails de bienvenue
 */
class DecoratorWelcome extends Mail {

    private Mail $mail;
    private $subject = "Bienvenue sur VitVet !";
    private $file = "assets/mail/bienvenue.html";
    private UserEntity $user;

    public function __construct(UserEntity $user) {
        $this->mail = new Mail;
        $this->user = $user;
    }

    function sendEmail(): bool {
        $data = array(
            "{{IMAGE1}}" => "https://media.discordapp.net/attachments/1029094521461538917/1062008566778712215/image-1.png",
            "{{IMAGE2}}" => $this->image_logo,
            "{{PRENOM}}" => $this->user->getPrenom(),
            "{{NOM}}" => $this->user->getNom(),
            "{{LOGIN}}" => $this->user->getLogin(),
            "{{MAIL}}" => $this->user->getMail(),
            "{{ADRESSE}}" => $this->user->getAdresse(),
            "{{CODEPOSTAL}}" => $this->user->getDepartement(),
        );

        return $this->mail->send($this->contactMail, $this->user->getMail(), $this->subject, $this->file, $data);
    }
}

/**
 * Décorateur de la classe Mail, permet d'envoyer des mails de réinitialisation 
 */
class DecoratorResetPassword extends Mail {

    private Mail $mail;
    private $subject = "Réinitialisation de votre mot de passe";
    private $file = "assets/mail/resetmotdepasse.html";
    private string $destination;
    private string $newPassword;

    public function __construct(string $destination, string $newPassword) {
        $this->mail = new Mail;
        $this->destination = $destination;
        $this->newPassword = $newPassword;
    }

    function sendEmail(): bool {
        $data = array(
            "{{MOTDEPASSE}}" => $this->newPassword,
            "{{IMAGE2}}" => "https://media.discordapp.net/attachments/1029094521461538917/1061991061200830504/image-2.png",
            "{{IMAGE3}}" => $this->image_logo,
            "{{IMAGE4}}" => "https://media.discordapp.net/attachments/1029094521461538917/1061991061976776787/image-4.png",
            "{{BASEURL}}" => base_url()
        );

        return $this->mail->send($this->contactMail, $this->destination, $this->subject, $this->file, $data);
    }
}
