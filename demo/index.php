<?php

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/app/ContainerDemo.php';
require_once __DIR__ . '/app/LegacyDemo.php';
require_once __DIR__ . '/app/RoutableDemo.php';

$request = \On2Media\Zeptowaf\Request::getInstance();

// psr container
$container = new \On2Media\Zeptowaf\Container();
$container->set('foo', 'bar');
$container->set('env', 'demo');
$container->set(ContainerDemo::class, static function ($container) {
    return new ContainerDemo($container->get('env'));
});
$container->set(RoutableDemo::class, static function ($container) {
    return new RoutableDemo(
        $container->get(ContainerDemo::class),
        $container->get('env')
    );
});

// // legacy array access (lacks lazy initialization)
// $container = [
//     'foo' => 'bar',
//     'env' => 'demo',
// ];
// $container[ContainerDemo::class] = new ContainerDemo($container['env']);

// // migrate legacy array access to psr container
// $container = new \On2Media\Zeptowaf\Container($container);
// $container->set(RoutableDemo::class, static function ($container) {
//     return new RoutableDemo(
//         $container->get(ContainerDemo::class),
//         $container->get('env')
//     );
// });

$router = new \On2Media\Zeptowaf\Router($request, $container);

$router->get('/^\/$/', RoutableDemo::class, 'getDemo');
$router->post('/^\/$/', RoutableDemo::class, 'postDemo');
$router->get('/^\/legacy$/', 'LegacyDemo', 'getDemo');

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
