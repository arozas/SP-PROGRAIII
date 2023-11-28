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
$app->group('/clients', function (RouteCollectorProxy $group) {
    $group->get('[/]', ClientController::class . ':GetAll');
    $group->get('/{id}', ClientController::class . ':Get');
    $group->post('[/]', ClientController::class . ':Add');
    $group->put('/{id}', ClientController::class . ':Update');
    $group->delete('/{id}', ClientController::class . ':Delete');
});

$app->get('[/]', function (Request $request, Response $response) {    
    $payload = json_encode(array("mensaje" => "Segundo Parcial."));
    
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
