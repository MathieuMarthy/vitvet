<?php
require_once APPPATH.DIRECTORY_SEPARATOR."models".DIRECTORY_SEPARATOR."UserEntity.php";
require_once APPPATH.DIRECTORY_SEPARATOR."models".DIRECTORY_SEPARATOR."MailDecorator.php";

class MailModel {

    private static $instance = null;

    private function __construct() {
    }

    private function __clone() {}

    static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new MailModel();
        }
        return self::$instance;
    }


    /**
     * Envoi un mail de réinitialisation du mot de passe à l'adresse mail spécifiée
     * @param string $destination
     * @param string $newPassword
     * @return bool Si l'envoi a fonctionné ou non
     */
    public function sendPasswordReset(string $destination, string $newPassword): bool {
        $decorator = new DecoratorResetPassword($destination, $newPassword);
        return $decorator->sendEmail();
    }


    /**
     * Envoi un mail de bienvenue à l'adresse mail spécifiée
     * @param string $destination
     * @param UserEntity $user
     * @return bool Si l'envoi a fonctionné ou non
     */
    public function sendWelcome(UserEntity $user): bool {
        $decorator = new DecoratorWelcome($user);
        return $decorator->sendEmail();
    }


    /**
     * Envoi un mail de retour
     * @param string $destination
     * @param array $data
     * @return bool
     */
    public function sendContact(string $destination, array $data): bool {
        $decorator = new DecoratorContact($destination, $data);
        return $decorator->sendEmail();
    }


    /**
     * Envoi un mail de validation d'email
     * @param UserEntity $user
     * @return bool
     */
    public function sendVerification(UserEntity $user) {
        $decorator = new DecoratorVerif($user);
        return $decorator->sendEmail();
    }
}
?>
