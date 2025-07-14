<?php

namespace Ijdb\Controllers;

use Ninja\DatabaseTable;

class Register
{
    private $authorTable;

    public function __construct(DatabaseTable $authorTable)
    {
        $this->authorTable = $authorTable;
    }

    public function registerForm()
    {
        return ['template' => 'register.html.php', 'title' => 'Registra un account'];
    }

    public function success()
    {
        return ['template' => 'registersuccess.html.php', 'title' => 'Registrazione effettuata'];
    }

    public function registerUser()
    {
        $author = $_POST['author'];

        // Per iniziare presumiamo che i dati siano validi;
        $valid = true;
        $errors = [];

        // Ma se uno dei campi viene lasciato vuoto impostiamo $value a false
        if (empty($author['name'])) {
            $valid = false;
            $errors[] = 'Il nome non può essere vuoto';
        }

        if (empty($author['email'])) {
            $valid = false;
            $errors[] = 'La mail non può essere vuota';
        } else if (filter_var($author['email'], FILTER_VALIDATE_EMAIL) == false) {
            $valid = false;
            $errors[] = 'Indirizzo email non valido';
        } else {
            // Se la mail non è vuota ed è valida, converti in minuscole
            $author['email'] = strtolower($author['email']);
            //Cerca la versione in minuscole di $author['email]
            if (count($this->authorTable->find('email', $author['email'])) > 0) {
                $valid = false;
                $errors[] = 'Indirizzo email risulta già registrato';
            }
        }

        if (empty($author['password'])) {
            $valid = false;
            $errors[] = 'La password non può essere vuota';
        }

        //Se $valid è ancora true, nessun campo è vuoto e quindi i dati possono essere aggiunti
        if ($valid == true) {
            //Calcola l'hash della password e lo salva nel database
            $author['password'] = password_hash(trim($author['password']), PASSWORD_DEFAULT);

            // Quando viene inviata, la variabile $author ora contiene il valore in minuscole dell'email
            $this->authorTable->save($author);

            header('location: /author/success');
        } else {
            //Se i dati non sono validi, ripresenta il form
            return [
                'template' => 'register.html.php',
                'title' => 'Registra un account',
                'variables' => ['errors' => $errors, 'author' => $author]
            ];
        }
    }
}
