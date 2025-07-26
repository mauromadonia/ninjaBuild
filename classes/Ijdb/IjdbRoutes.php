<?php

namespace Ijdb;

class Ijdbroutes implements \Ninja\Routes
{

    private $authorTable;
    private $jokesTable;
    private $authentication;
    private $categoryTable;
    private $jokeCategoriesTable;


    public function __construct()
    {
        include __DIR__ . '/../../includes/DatabaseConnection.php';

        $this->jokesTable = new \Ninja\DatabaseTable($pdo, 'joke', 'id', '\Ijdb\Entity\Joke', [&$this->authorTable, &$this->jokeCategoriesTable, &$this->categoryTable]);
        $this->authorTable = new \Ninja\DatabaseTable($pdo, 'author', 'id', '\Ijdb\Entity\Author', [&$this->jokesTable]);
        $this->authentication = new \Ninja\Authentication($this->authorTable, 'email', 'password');
        $this->categoryTable = new \Ninja\DatabaseTable($pdo, 'category', 'id', '\Ijdb\Entity\Category',[&$this->jokesTable, &$this->jokeCategoriesTable]);
        $this->jokeCategoriesTable = new \Ninja\DatabaseTable($pdo, 'jokecategory', 'categoryid');
    }
    /**
     * Richiama l'azione appropriata del controller e restituisce la variabile page
     */
    public function getRoutes(): array
    {

        $jokeController = new \Ijdb\Controllers\Joke($this->jokesTable, $this->authorTable, $this->categoryTable, $this->authentication);
        $authorController = new \Ijdb\Controllers\Register($this->authorTable);
        $loginController = new \Ijdb\Controllers\Login($this->authentication);
        $categoryController = new \Ijdb\Controllers\Category($this->categoryTable);

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

            'category/edit' => [
                'GET' => [
                    'controller' => $categoryController,
                    'action' => 'edit',
                ],
                'POST' => [
                    'controller' => $categoryController,
                    'action' => 'saveEdit',
                ],
                'login' => true
            ],
            'category/list' => [
                'GET' => [
                    'controller' => $categoryController,
                    'action' => 'list',
                ],
                'login' => true
            ],
            'category/delete' => [
                'POST' => [
                    'controller' => $categoryController,
                    'action' => 'delete',
                ],
                'login' => true
            ],

        ];

        return $routes;
    }

    public function getAuthentication(): \Ninja\Authentication
    {
        return $this->authentication;
    }
}
