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

require_once './middlewares/Logger.php';
require_once './middlewares/AuthMiddleware.php';
require_once './middlewares/Validator.php';

require_once './database/DataAccessObject.php';

require_once './controllers/ClientController.php';
require_once './controllers/UserController.php';
require_once './controllers/ReserveController.php';
require_once './controllers/ReportController.php';


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
// Users
$app->group('/users', function (RouteCollectorProxy $group) {
    $group->get('[/]', UserController::class . ':GetAll')->add(new AuthMiddleware());
    $group->get('/{id}', UserController::class . ':Get')->add(new AuthMiddleware());
    $group->post('[/]', UserController::class . ':Add')->add(new AuthMiddleware())->add(Validator::class . '::NewUserValidation');
    $group->put('/{id}', UserController::class . '::Update')->add(new AuthMiddleware());
    $group->delete('/{id}', UserController::class . '::Delete')->add(new AuthMiddleware());
});

//Clients
$app->group('/clients', function (RouteCollectorProxy $group) {
    $group->get('[/]', ClientController::class . ':GetAll')->add(new AuthMiddleware());
    $group->get('/{id}', ClientController::class . ':Get')->add(new AuthMiddleware());
    $group->post('[/]', ClientController::class . ':Add')->add(new AuthMiddleware());
    $group->put('/{id}', ClientController::class . ':Update')->add(new AuthMiddleware());
    $group->delete('/{id}', ClientController::class . ':Delete')->add(new AuthMiddleware());
});

//Reserves
$app->group('/reserves', function (RouteCollectorProxy $group) {
    $group->get('[/]', ReserveController::class . ':GetAll')->add(new AuthMiddleware());
    $group->get('/{id}', ReserveController::class . ':Get')->add(new AuthMiddleware());
    $group->post('[/]', ReserveController::class . ':Add')->add(new AuthMiddleware());
    $group->put('/{id}', ReserveController::class . ':Update')->add(new AuthMiddleware());
    $group->delete('/{id}', ReserveController::class . ':Delete')->add(new AuthMiddleware());
});

// Reports
$app->group('/reports', function (RouteCollectorProxy $group) {
    $group->get('/room_and_date[/{fecha}]', ReportController::class . ':GetAmountByRoomAndDate')->add(new AuthMiddleware());
    $group->get('/by_client/{clientId}', ReportController::class . ':GetReserveByClientId')->add(new AuthMiddleware());
    $group->get('/reserves_between_dates/{startDate}/{endDate}', ReportController::class . ':GetReservesBetweenDates')->add(new AuthMiddleware());
    $group->get('/by_rooms', ReportController::class . ':GetAllReservesByRoom')->add(new AuthMiddleware());
    $group->get('/cancelled-amount-by-client-and-date[/{fecha}]', ReportController::class . ':GetCancelledAmountByClientAndDate')->add(new AuthMiddleware());
    $group->get('/cancelled-reserves-by-client/{id}', ReportController::class . ':GetCancelledReservesByClientId')->add(new AuthMiddleware());
    $group->get('/cancelled-reserves-between-dates/{startDate}/{endDate}', ReportController::class . ':GetCancelledReservesBetweenDates')->add(new AuthMiddleware());
    $group->get('/cancelled-reserves-by-client-type', ReportController::class . ':GetCancelledReservesByClientType')->add(new AuthMiddleware());
    $group->get('/all-operations-by-user', ReportController::class . ':GetAllOperationsByUser')->add(new AuthMiddleware());
    $group->get('/reserves-by-payment-method/{paymentMethod}', ReportController::class . ':GetReservesByModality')->add(new AuthMiddleware());
});

// LOG IN
$app->group('/login', function (RouteCollectorProxy $group) {
    $group->post('[/]', UserController::class . '::LogIn')->add(Logger::class . '::LoginValidation');
});

$app->add(Logger::class . '::UserLogger');

$app->get('[/]', function (Request $request, Response $response) {    
    $payload = json_encode(array("mensaje" => "Segundo Parcial."));
    
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
