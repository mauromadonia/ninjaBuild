<?php

namespace Ijdb;

class Ijdbroutes implements \Ninja\Routes
{

    private $authorTable;
    private $jokesTable;
    private $authentication;


    public function __construct()
    {
        include __DIR__ . '/../../includes/DatabaseConnection.php';

        $this->jokesTable = new \Ninja\DatabaseTable($pdo, 'joke', 'id');
        $this->authorTable = new \Ninja\DatabaseTable($pdo, 'author', 'id');
        $this->authentication = new \Ninja\Authentication($this->authorTable, 'email', 'password');
    }
    /**
     * Richiama l'azione appropriata del controller e restituisce la variabile page
     */
    public function getRoutes(): array
    {

        $jokeController = new \Ijdb\Controllers\Joke($this->jokesTable, $this->authorTable, $this->authentication);
        $authorController = new \Ijdb\Controllers\Register($this->authorTable);
        $loginController = new \Ijdb\Controllers\Login($this->authentication);

        $routes = [

            '' => [
                'GET' => [
                    'controller' => $jokeController,
                    'action' => 'home'
                ],
            ],

            'joke/list' => [
                'GET' => [
                    'controller' => $jokeController,
                    'action' => 'list'
                ],

            ],

            'joke/edit' => [
                'GET' => [
                    'controller' => $jokeController,
                    'action' => 'edit'
                ],

                'POST' => [
                    'controller' => $jokeController,
                    'action' => 'saveEdit'
                ],
                'login' => true
            ],

            'joke/delete' => [
                'POST' => [
                    'controller' => $jokeController,
                    'action' => 'delete'
                ],
                'login' => true
            ],

            'author/register' => [
                'GET' => [
                    'controller' => $authorController,
                    'action' => 'registerForm'
                ],
                'POST' => [
                    'controller' => $authorController,
                    'action' => 'registerUser'
                ]
            ],

            'author/success' => [
                'GET' => [
                    'controller' => $authorController,
                    'action' => 'success'
                ],
            ],

            'login' => [
                'GET' => [
                    'controller' => $loginController,
                    'action' => 'loginForm'
                ],
                'POST' => [
                    'controller' => $loginController,
                    'action' => 'processLogin'
                ]
            ],

            'login/error' => [
                'GET' => [
                    'controller' => $loginController,
                    'action' => 'error'
                ],
            ],

            'login/success' => [
                'GET' => [
                    'controller' => $loginController,
                    'action' => 'success'
                ],
                'login' => true
            ],

            'logout' => [
                'GET' => [
                    'controller' => $loginController,
                    'action' => 'logout'
                ],
            ],


        ];

        return $routes;
    }

    public function getAuthentication(): \Ninja\Authentication
    {
        return $this->authentication;
    }
}
