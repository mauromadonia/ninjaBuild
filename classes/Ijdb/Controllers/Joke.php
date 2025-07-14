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

        $title = 'Lista Barzellette';

        return [
            'template' => 'jokes.html.php',
            'title' => $title,
            'variables' => [
                'totalJokes' => $totalJokes,
                'jokes' => $jokes,
                'userId' => $author['id'] ?? null
            ]
        ];
    }

    public function edit()
    {

        if (isset($_GET['id'])) {
            $joke = $this->jokesTable->findById($_GET['id']);
        }

        $title = 'Modifica Barzelletta';

        return [
            'template' => 'editjoke.html.php',
            'title' => $title,
            'variables' => [
                'joke' => $joke ?? null,
            ]
        ];
    }

    public function saveEdit()
    {
        $author = $this->authentication->getUser(); //Recupera l'utente connesso

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
        $this->jokesTable->delete($_POST['id']);

        header('location: /joke/list');
    }
}
