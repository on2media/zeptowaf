<?php

require_once __DIR__ . '/../src/Request.php';
require_once __DIR__ . '/../src/Routable.php';
require_once __DIR__ . '/../src/Router.php';
require_once __DIR__ . '/../src/Exception/Exception.php';
require_once __DIR__ . '/../src/Exception/BadRequest.php';
require_once __DIR__ . '/../src/Exception/MethodNotAllowed.php';
require_once __DIR__ . '/../src/Exception/NotFound.php';
require_once __DIR__ . '/../src/Exception/Validation.php';

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

} catch (\On2Media\Zeptowaf\Exception\NotFound $e) {

    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    echo 'Not Found: ' . $e->getMessage();
    exit;

} catch (\On2Media\Zeptowaf\Exception\BadRequest $e) {

    header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
    echo 'Bad Request: ' . $e->getMessage();
    exit;

} catch (\On2Media\Zeptowaf\Exception\MethodNotAllowed $e) {

    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    echo 'Method Not Allowed: ' . $e->getMessage();
    exit;

} catch (\On2Media\Zeptowaf\Exception\Validation $e) {

    header($_SERVER['SERVER_PROTOCOL'] . ' 422 Unprocessable Entity');
    echo 'Validation: ' . $e->getMessage();
    var_dump($e->getErrors());
    if ($e->getReasons() !== []) {
        var_dump($e->getReasons());
    }
    exit;

} catch (\Exception $e) {

    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
    echo 'Internal Server Error: ' . $e->getMessage();
    exit;

}
