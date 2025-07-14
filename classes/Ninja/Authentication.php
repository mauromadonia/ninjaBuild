<?php

namespace Ninja;


class Authentication
{
    private $users;
    private $usernameColumn;
    private $passwordColumn;
    /**
     * Inizializzo la classe fornendo un'istanza DatabaseTable per la tabella che memorizza gli account, nome colonna user e nome colonna password
     * Quando l'istanza viene creata, avvia la sessione
     */
    public function __construct(DatabaseTable $users, $usernameColumn, $passwordColumn)
    {
        session_start();
        $this->users = $users;
        $this->usernameColumn = $usernameColumn;
        $this->passwordColumn = $passwordColumn;
    }
    /**
     * Funzione di login.
     * La funzione cerca corrispondenza tra nome login fornito e nome login nel database
     * Se il confronto ha successo, controlla la password fornita con quella associata al nome di login trovato
     * Crea un nuovo session ID se nome login e password corrispondono e le memorizza nelle variabili SESSION
     * Restituisce true se l'operazione è andata a buon fine, altrimenti false
     */
    public function login($username, $password)
    {
        $user = $this->users->find($this->usernameColumn, strtolower($username));

        if (!empty($user) && password_verify($password, $user[0]->{$this->passwordColumn})) {
            session_regenerate_id();
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $user[0]->{$this->passwordColumn};

            return true;
        } else {
            return false;
        }
    }
    /**
     * Funzione per controllo di cambio password ad ogni pagina riservata
     * Controlla se esiste nella variabile di sessione  il nome di login, altrimenti restituisce false
     * Controlla se il nome di login nella variabile session coincide con il nome di login nel database
     * Restituisce true se il nome di login esiste e se la password nella variabile session coincide con la password nel database
     * Altrimenti restituisce false
     */
    public function isLoggedIn()
    {
        if (empty($_SESSION['username'])) {
            return false;
        }


        $user = $this->users->find($this->usernameColumn, strtolower($_SESSION['username']));

        //$passwordColumn = $this->passwordColumn;

        if (!empty($user) && $user[0]->{$this->passwordColumn} === $_SESSION['password']) {
            return true;
        } else {
            return false;
        }
    }

    public function getUser()
    {
        if ($this->isLoggedIn()) {
            return $this->users->find($this->usernameColumn, strtolower($_SESSION['username']))[0];
        } else {
            return false;
        }
    }
}
