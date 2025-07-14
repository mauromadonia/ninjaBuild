<?php

namespace Ijdb\Controllers;

class Login
{
    private $authentication;

    public function __construct(\Ninja\Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    public function loginForm()
    {
        return ['template' => 'login.html.php', 'title' => 'Accedi'];
    }

    public function error()
    {
        return ['template' => 'loginerror.html.php', 'title' => 'Non hai eseguito l\'accesso'];
    }

    public function processLogin()
    {
        if ($this->authentication->login($_POST['email'], $_POST['password'])) {
            header('location: /login/success');
        } else {
            return [
                'template' => 'login.html.php',
                'title' => 'Accedi',
                'variables' => [
                    'error' => 'Email e Password errati.'
                ]
            ];
        }
    }

    public function success()
    {
        return ['template' => 'loginsuccess.html.php', 'title' => 'Accesso eseguito'];
    }

    public function logout(){
        //Elimina solo le variabili di sessione per disconnettere l'utente
        unset($_SESSION['username'], $_SESSION['password']);

        return ['template' => 'logout.html.php', 'title' => 'Sei stato disconnesso.'];
    }
}
