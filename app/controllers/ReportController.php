<?php
require_once './services/ReportService.php';
class ReportController
{
    public function GetAmountByRoomAndDate($request, $response, $args)
    {
        $fecha = $args['fecha'] ?? null;

        $result = ReportService::GetAmountByRoomAndDate($fecha);

        $payload = json_encode($result);
        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    public function GetReserveByClientId($request, $response, $args)
    {
        $clientId = $args['clientId'] ?? null;

        $result = ReportService::GetReserveByClientId($clientId);

        $payload = json_encode($result);
        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function GetReservesBetweenDates($request, $response, $args)
    {
        $startDate = $args['startDate'] ?? null;
        $endDate = $args['endDate'] ?? null;

        $result = ReportService::GetReservesBetweenDates($startDate, $endDate);

        $payload = json_encode($result);
        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function GetAllReservesByRoom($request, $response, $args)
    {
        $result = ReportService::GetAllReservesByRoom();

        $payload = json_encode($result);
        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    }
   //nuevos:
    public function GetCancelledAmountByClientAndDate($request, $response, $args)
    {
        $fecha = $args['fecha'] ?? null;

        $cancelledAmount = ReportService::GetCancelledAmountByClientAndDate($fecha);

        $payload = json_encode($cancelledAmount);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function GetCancelledReservesByClientId($request, $response, $args)
    {
        $clientId = $args['id'];

        $cancelledReserves = ReportService::GetCancelledReservesByClientId($clientId);

        $payload = json_encode($cancelledReserves);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function GetCancelledReservesBetweenDates($request, $response, $args)
    {
        $startDate = $args['startDate'];
        $endDate = $args['endDate'];

        $cancelledReserves = ReportService::GetCancelledReservesBetweenDates($startDate, $endDate);

        $payload = json_encode($cancelledReserves);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function GetCancelledReservesByClientType($request, $response, $args)
    {
        $cancelledReservesByClientType = ReportService::GetCancelledReservesByClientType();

        $payload = json_encode($cancelledReservesByClientType);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function GetAllOperationsByUser($request, $response, $args)
    {
        $allOperations = ReportService::GetAllOperationsByUser();

        $payload = json_encode($allOperations);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function GetReservesByModality($request, $response, $args)
    {
        $modality = $args['modality'];

        $reservesByModality = ReportService::GetReservesByModality($modality);

        $payload = json_encode($reservesByModality);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

}
