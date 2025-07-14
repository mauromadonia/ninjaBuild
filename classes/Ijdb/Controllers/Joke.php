<?php

namespace Ijdb\Controllers;

use \Ninja\DatabaseTable;
use \Ninja\Authentication;

class Joke
{
    private $jokesTable;
    private $authorTable;
    private $authentication;

    public function __construct(DatabaseTable $jokesTable, DatabaseTable $authorTable, Authentication $authentication)
    {
        $this->jokesTable = $jokesTable;
        $this->authorTable = $authorTable;
        $this->authentication = $authentication;
    }


    public function home()
    {
        $title = 'Database Barzellette';

        return ['template' => 'home.html.php', 'title' => $title];
    }
    public function list()
    {
        $result = $this->jokesTable->findAll();

        $jokes = [];

        foreach ($result as $joke) {
            $author = $this->authorTable->findById($joke['authorid']);

            $jokes[] = [
                'id' => $joke['id'],
                'joketext' => $joke['joketext'],
                'jokedate' => $joke['jokedate'],
                'name' => $author['name'],
                'email' => $author['email'],
                'authorid' => $author['id']
            ];
        }

        $totalJokes = $this->jokesTable->total();

        $author = $this->authentication->getUser();

        return [
            'template' => 'jokes.html.php',
            'title' => 'Lista Barzellette',
            'variables' => [
                'totalJokes' => $totalJokes,
                'jokes' => $jokes,
                'userId' => $author['id'] ?? null
            ]
        ];
    }

    public function edit()
    {
        $author = $this->authentication->getUser();


        if (isset($_GET['id'])) {
            $joke = $this->jokesTable->findById($_GET['id']);
        }

        $title = 'Modifica Barzelletta';

        return [
            'template' => 'editjoke.html.php',
            'title' => $title,
            'variables' => [
                'joke' => $joke ?? null,
                'userId' => $author['id'] ?? null
            ]
        ];
    }

    public function saveEdit()
    {
        $author = $this->authentication->getUser(); //Recupera l'utente connesso

        //Controlla se l'id della barzelletta da modificare, coincide con l'id dell'utente connesso.
        if (isset($_GET['id'])) {
            $joke = $this->jokesTable->findById($_GET['id']);

            if ($joke['authorid'] != $author['id']) {
                return;
            }
        }
        if (isset($_POST['joke'])) {

            $joke = $_POST['joke'];
            $joke['jokedate'] = new \DateTime();
            $joke['authorid'] = $author['id'];

            $this->jokesTable->save($joke);

            header('location: /joke/list');
        }
    }

    public function delete()
    {

        $author = $this->authentication->getUser(); //Recupera l'utente connesso

        //Recupera l'id della barzelletta che si vuole eliminare
        $joke = $this->jokesTable->findById($_POST['id']);

        //Controlla che l'id dell'utente connesso coincida con l'id della barzelletta da eliminare.
        if ($joke['authorid'] != $author['id']) { 
            return;
        }

        $this->jokesTable->delete($_POST['id']);

        header('location: /joke/list');
    }
}
