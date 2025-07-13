<?php

namespace Ninja;

class EntryPoint

{
    private $route;
    private $routes;
    private $method;

    public function __construct(string $route, string $method, \Ninja\Routes $routes)
    {
        $this->route = $route;
        $this->routes = $routes;
        $this->method = $method;
        $this->checkUrl();
    }
    /**
     * Controlla che il route sia corretto (minuscole e maiuscole) e nel caso contrario
     * reindirizza alla versione in minuscole.
     */
    private function checkUrl()
    {
        if ($this->route !== strtolower($this->route)) {
            http_response_code(301);
            header('location: ' . strtolower($this->route));
        }
    }
    /**
     * Caricamento delle pagine template (html).
     */
    private function loadTemplate($templateFileName, $variables = [])
    {
        extract($variables);

        ob_start();

        include __DIR__ . '/../../templates/' . $templateFileName;

        return ob_get_clean();
    }
    /**
     * Caricamento del template appropriato e invia le variabili.
     */
    public function run()
    {
        $routes = $this->routes->getRoutes();
        $authentication = $this->routes->getAuthentication();

        if (isset($routes[$this->route]['login']) && !$authentication->isLoggedIn()) {
            header('location: /login/error');
        } else {

            $controller = $routes[$this->route][$this->method]['controller'];
            $action = $routes[$this->route][$this->method]['action'];

            $page = $controller->$action();

            $title = $page['title'];

            if (isset($page['variables'])) {
                $output = $this->loadTemplate($page['template'], $page['variables']);
            } else {
                $output = $this->loadTemplate($page['template']);
            }

            // Carica il template layout.html.php e passa al file html le variabili attraverso l'array
            echo $this->loadTemplate('layout.html.php' , [
                'loggedIn' => $authentication->isLoggedIn(),
                'output' => $output,
                'title' => $title
            ]);
            
            //include __DIR__ . '/../../templates/layout.html.php';
        }
    }
}
