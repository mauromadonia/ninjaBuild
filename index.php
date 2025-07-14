<?php

try {
    include __DIR__ . '/includes/autoload.php';

    $route = ltrim(strtok($_SERVER['REQUEST_URI'], '?'), '/'); 

    $entryPoint = new \Ninja\EntryPoint($route, $_SERVER['REQUEST_METHOD'], new \Ijdb\Ijdbroutes());
    $entryPoint->run();
} catch (PDOException $e) {
    $title = 'Oops! Errore';
    $output = 'Errore Database : ' . $e;
    $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();

    include __DIR__ . '/templates/layout.html.php';
}
