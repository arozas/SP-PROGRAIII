<?php

require_once './models/Reserve.php';
require_once './services/ReserveService.php';
require_once './services/ClientService.php';
require_once './interfaces/IApiUse.php';

class ReserveController implements IApiUse
{
    public function Add($request, $response, $args)
    {
        $IdPlaceholder = rand(1, 99);
        $parameters = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();


        $clientId = $parameters['clientId'];
        $clientType = ClientService::ValidateType($parameters['clientType']);
        $checkInDate = $parameters['checkInDate'];
        $checkOutDate = $parameters['checkOutDate'];
        $roomType = ReserveService::ValidateRoomType($parameters['roomType']);
        $totalAmount = $parameters['totalAmount'];

        $client = ClientService::Get($clientId);

        if (!$client || $clientType == 0) {
            $payload = json_encode(array("error" => "Cliente no encontrado en la base de datos"));
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }
        $reserve = new Reserve();
        $reserve->clientId = $clientId;
        $reserve->clientType = $clientType;
        $reserve->checkInDate = $checkInDate;
        $reserve->checkOutDate = $checkOutDate;
        $reserve->roomType = $roomType;
        $reserve->price = $totalAmount;
        $reserve->status = EReserveStatus::RESERVED->value;
        if (isset($uploadedFiles['fotoReserva'])) {
            $targetPath = './ImagenesDeReservas/2023/'. $clientId . $IdPlaceholder . '.jpg';
            $uploadedFiles['fotoReserva']->moveTo($targetPath);
            $reserve->reserveImage = $targetPath;
        }

        $reserveId = ReserveService::Add($reserve);

        $payload = json_encode(array("mensaje" => "Reserva creada con éxito", "reserveId" => $reserveId));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function Get($request, $response, $args)
    {
        $reserveId = $args['id'];
        $reserve = ReserveService::Get($reserveId);

        if (!$reserve) {
            $reserve = array("error" => "Reserva no encontrada");
        }

        $payload = json_encode($reserve);
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function GetAll($request, $response, $args)
    {
        $reserveList = ReserveService::GetAll();
        $payload = json_encode(array("listaReservas" => $reserveList));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function Delete($request, $response, $args)
    {
        $reserveId = $args['id'];

        if (ReserveService::Get($reserveId)) {
            ReserveService::Delete($reserveId);
            $payload = json_encode(array("mensaje" => "Reserva eliminada con éxito"));
        } else {
            $payload = json_encode(array("mensaje" => "ID no coincide con una reserva"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function Update($request, $response, $args)
    {
        $reserveId = $args['id'];
        $reserve = ReserveService::Get($reserveId);

        if ($reserve != false) {
            $parameters = $request->getParsedBody();

            $updated = false;

            if (isset($parameters['clientType'])) {
                $clientType = ClientService::ValidateType($parameters['clientType']);
                if($clientType != 0)
                {
                    $updated = true;
                    $reserve->clientType = $clientType;
                }else{
                    $payload = json_encode(array("error" => "Tipo de cliente incorrecto"));
                    $response->getBody()->write($payload);
                    return $response
                        ->withHeader('Content-Type', 'application/json');
                }
            }
            if (isset($parameters['checkInDate'])) {
                $updated = true;
                $reserve->checkInDate = $parameters['checkInDate'];
            }
            if (isset($parameters['checkOutDate'])) {
                $updated = true;
                $reserve->checkOutDate = $parameters['checkOutDate'];
            }
            if (isset($parameters['roomType'])) {
                $roomType = ReserveService::ValidateRoomType($parameters['roomType']);
                if ($roomType != 0)
                {
                    $updated = true;
                    $reserve->roomType = $roomType;
                }else{
                    $payload = json_encode(array("error" => "Tipo de habitación incorrecto"));
                    $response->getBody()->write($payload);
                    return $response
                        ->withHeader('Content-Type', 'application/json');
                }
            }
            if (isset($parameters['price'])) {
                $updated = true;
                $reserve->price = $parameters['price'];
            }
            if (isset($parameters['status'])) {
                $roomStatus = ReserveService::ValidateRoomStatus($parameters['status']);
                if($roomStatus != 0)
                {
                    $updated = true;
                    $reserve->status = $roomStatus;
                }else{
                    $payload = json_encode(array("error" => "Tipo de habitación incorrecto"));
                    $response->getBody()->write($payload);
                    return $response
                        ->withHeader('Content-Type', 'application/json');
                }
            }

            if ($updated) {
                ReserveService::Update($reserve);
                ReserveService::SaveReserveAdjustment($reserve);
                $payload = json_encode(array("mensaje" => "Reserva modificada con éxito"));
            } else {
                $payload = json_encode(array("mensaje" => "Reserva no modificada por falta de campos"));
            }

        } else {
            $payload = json_encode(array("error" => "Reserva no encontrada"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
