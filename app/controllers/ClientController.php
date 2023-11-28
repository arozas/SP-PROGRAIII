<?php
require_once './models/Client.php';
require_once './services/ClientService.php';
require_once './interfaces/IApiUse.php';

class ClientController implements IApiUse
{
    public function Add($request, $response, $args)
    {
        $parameters = $request->getParsedBody();
        $clientName = $parameters['nombre'];
        $clientSurname = $parameters['apellido'];
        $clientType = ClientService::ValidateType($parameters['tipoCliente']);
        $documentType = ClientService::ValidateDocumentType($parameters['tipoDocumento']);
        $documentNumber = $parameters['numeroDocumento'];
        $clientEmail = $parameters['email'];
        $clientCountry = $parameters['pais'];
        $clientCity = $parameters['ciudad'];
        $clientPhone = $parameters['telefono'];
        $paymentMethod = ClientService::ValidatePaymentMethod($parameters['tipoPago']);

        echo($clientType);

        $client = new Client();
        $client->name = $clientName;
        $client->surname = $clientSurname;
        $client->clientType = $clientType;
        $client->documentType = $documentType;
        $client->documentNumber = $documentNumber;
        $client->email = $clientEmail;
        $client->country = $clientCountry;
        $client->city = $clientCity;
        $client->phone = $clientPhone;
        $client->paymentMethod = $paymentMethod;

        ClientService::Add($client);
        $payload = json_encode(array("mensaje" => "Cliente creado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function Get($request, $response, $args)
    {
        $clientsID = $args['id'];
        $client = ClientService::Get($clientsID);
        if(!$client)
        {
            $client = array("error" => "Cliente no existe");
        }
        $payload = json_encode($client);
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function GetAll($request, $response, $args)
    {
        $clientList = ClientService::GetAll();
        $payload = json_encode(array("listaUsuario" => $clientList));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function Delete($request, $response, $args)
    {
        $clientId = $args['id'];

        if (ClientService::Get($clientId)) {

            ClientService::Delete($clientId);
            $payload = json_encode(array("mensaje" => "Cliente borrado con exito"));
        } else {

            $payload = json_encode(array("mensaje" => "ID no coincide con un cliente"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function Update($request, $response, $args)
    {

        $id = $args['id'];

        $client = ClientService::Get($id);

        if ($client != false) {
            $parameters = $request->getParsedBody();

            $updated = false;
            if (isset($parameters['nombre'])) {
                $updated = true;
                $client->name = $parameters['nombre'];
            }
            if (isset($parametros['apellido'])) {
                $updated = true;
                $client->surname = $parameters['apellido'];
            }
            if (isset($parameters['tipoDocumento'])) {
                $updated = true;
                $client->documentType = ClientService::ValidateDocumentType($parameters['tipoDocumento']);;
            }
            if (isset($parameters['numeroDocumento'])) {
                $updated = true;
                $client->documentNumber = $parameters['numeroDocumento'];
            }
            if (isset($parameters['email'])) {
                $updated = true;
                $client->email = $parameters['email'];
            }
            if (isset($parameters['tipoCliente'])) {
                $updated = true;
                $client->clientType = ClientService::ValidateType($parameters['tipoCliente']);
            }
            if (isset($parameters['pais'])) {
                $updated = true;
                $client->country = $parameters['pais'];
            }
            if (isset($parameters['ciudad'])) {
                $updated = true;
                $client->city = $parameters['ciudad'];
            }
            if (isset($parameters['telefono'])) {
                $updated = true;
                $client->phone = $parameters['telefono'];
            }
            if (isset($parameters['tipoPago'])) {
                $updated = true;
                $client->paymentMethod = ClientService::ValidatePaymentMethod($parameters['tipoPago']);
            }

            if ($updated) {
                ClientService::Update($client);
                $payload = json_encode(array("mensaje" => "Cliente modificado con exito"));
            } else {
                $payload = json_encode(array("mensaje" => "Cliente no modificado por falta de campos"));
            }

        } else {
            $payload = json_encode(array("error" => "Usuario no existe"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
