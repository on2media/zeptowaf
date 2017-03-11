<?php

require_once __DIR__ . '/../src/Request.php';
require_once __DIR__ . '/../src/Routable.php';
require_once __DIR__ . '/../src/Router.php';
require_once __DIR__ . '/../src/NotFoundException.php';

require_once __DIR__ . '/app/Demo.php';

$request = \On2Media\Zeptowaf\Request::getInstance();

$container = [
    'foo' => 'bar',
];

$router = new \On2Media\Zeptowaf\Router($request, $container);

$router->get('/^\/$/', 'Demo', 'getDemo');
$router->post('/^\/$/', 'Demo', 'postDemo');

try {

    $router->route();

} catch (\On2Media\Zeptowaf\NotFoundException $e) {

    echo 'Not Found: ' . $e->getMessage();
    exit;

}