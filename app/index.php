<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './database/DataAccessObject.php';
// require_once './middlewares/Logger.php';
require_once './controllers/ClientController.php';
require_once './controllers/ReserveController.php';

// Set Timezone
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Set base path
$app->setBasePath('/app');

// Add error middleware
$errorMiddleware = function ($request, $exception, $displayErrorDetails) use ($app) {
    $statusCode = 500;
    $errorMessage = $exception->getMessage();
    $response = $app->getResponseFactory()->createResponse($statusCode);
    $response->getBody()->write(json_encode(['error' => $errorMessage]));

    return $response->withHeader('Content-Type', 'application/json');
};
$app->addErrorMiddleware(true, true, true)
    ->setDefaultErrorHandler($errorMiddleware);;

// Add parse body
$app->addBodyParsingMiddleware();

// Routes
//Clients
$app->group('/clients', function (RouteCollectorProxy $group) {
    $group->get('[/]', ClientController::class . ':GetAll');
    $group->get('/{id}', ClientController::class . ':Get');
    $group->post('[/]', ClientController::class . ':Add');
    $group->put('/{id}', ClientController::class . ':Update');
    $group->delete('/{id}', ClientController::class . ':Delete');
});

//Reserves
$app->group('/reserves', function (RouteCollectorProxy $group) {
    $group->get('[/]', ReserveController::class . ':GetAll');
    $group->get('/{id}', ReserveController::class . ':Get');
    $group->post('[/]', ReserveController::class . ':Add');
    $group->put('/{id}', ReserveController::class . ':Update');
    $group->delete('/{id}', ReserveController::class . ':Delete');
});

$app->get('[/]', function (Request $request, Response $response) {    
    $payload = json_encode(array("mensaje" => "Segundo Parcial."));
    
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
