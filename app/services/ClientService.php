<?php
require_once './interfaces/IApiUse.php';
require_once './database/DataAccessObject.php';
require_once './models/Client.php';
require_once './models/DTOs/ClientDTO.php';
require_once './enums/EDocumentType.php';
require_once './enums/EClientType.php';
require_once './enums/EPaymentMethods.php';

class ClientService
{
    public static function Add($client)
    {
        $DAO = DataAccessObject::getInstance();
        $request = $DAO->prepareRequest("INSERT INTO clients (name, 
                                                                 surname, 
                                                                 documentType, 
                                                                 documentNumber, 
                                                                 email, 
                                                                 clientType, 
                                                                 country, 
                                                                 city, 
                                                                 phone, 
                                                                 paymentMethod, 
                                                                 active, 
                                                                 modifiedDate) 
                                                        VALUES (:name, 
                                                                :surname, 
                                                                :documentType, 
                                                                :documentNumber, 
                                                                :email, 
                                                                :clientType, 
                                                                :country, 
                                                                :city, 
                                                                :phone, 
                                                                :paymentMethod,
                                                                true,
                                                                :modifiedDate)");
        //$passHash = password_hash($user->password, PASSWORD_DEFAULT);
        //$request->bindValue(':password', $passHash);
        $request->bindValue(':name', $client->name, PDO::PARAM_STR);
        $request->bindValue(':surname', $client->surname, PDO::PARAM_STR);
        $request->bindValue(':documentType', $client->documentType, PDO::PARAM_INT);
        $request->bindValue(':documentNumber', $client->documentNumber, PDO::PARAM_INT);
        $request->bindValue(':email', $client->email, PDO::PARAM_STR);
        $request->bindValue(':clientType', $client->clientType, PDO::PARAM_INT);
        $request->bindValue(':country', $client->country, PDO::PARAM_STR);
        $request->bindValue(':city', $client->city, PDO::PARAM_STR);
        $request->bindValue(':phone', $client->phone, PDO::PARAM_STR);
        $request->bindValue(':paymentMethod', $client->paymentMethod, PDO::PARAM_INT);
        $date = new DateTime(date("d-m-Y"));
        $request->bindValue(':modifiedDate', date_format($date, 'Y-m-d H:i:s'));
        $request->execute();
        return $DAO->getLastId();
    }

    public static function Get($id)
    {
        $DAO = DataAccessObject::getInstance();
        $request = $DAO->prepareRequest("SELECT id,
                                                    name, 
                                                    surname, 
                                                    documentType, 
                                                    documentNumber, 
                                                    email, 
                                                    clientType, 
                                                    country, 
                                                    city, 
                                                    phone, 
                                                    paymentMethod FROM clients 
                                                                  WHERE id = :id 
                                                                  AND active = true");
        $request->bindValue(':id', $id, PDO::PARAM_STR);
        $request->execute();

        $request->setFetchMode(PDO::FETCH_CLASS, 'ClientDTO');

        $clientDTO = $request->fetch();

        if($clientDTO)
        {
            //MAPEO la respuesta.
            $clientDTO->documentType = $clientDTO->getDocumentTypeText();
            $clientDTO->clientType = $clientDTO->getClientTypeText();
            $clientDTO->paymentMethod = $clientDTO->getPaymentMethodText();
        }

        return $clientDTO;
    }

    public static function GetAll()
    {
        $DAO = DataAccessObject::getInstance();
        $request = $DAO->prepareRequest("SELECT id,
                                                    name, 
                                                    surname, 
                                                    documentType, 
                                                    documentNumber, 
                                                    email, 
                                                    clientType, 
                                                    country, 
                                                    city, 
                                                    phone, 
                                                    paymentMethod FROM clients 
                                                                  WHERE active = true");
        $request->execute();

        $request->setFetchMode(PDO::FETCH_CLASS, 'ClientDTO');

        $clients = $request->fetchAll();

        foreach ($clients as $client) {
            $client->documentType = $client->getDocumentTypeText();
            $client->clientType = $client->getClientTypeText();
            $client->paymentMethod = $client->getPaymentMethodText();
        }

        return $clients;
    }

    public static function Delete($id)
    {
        $DAO = DataAccessObject::getInstance();
        $request = $DAO->prepareRequest("UPDATE clients SET modifiedDate = :modifiedDate, active = false WHERE id = :id");
        $date = new DateTime(date("d-m-Y"));
        $request->bindValue(':id', $id, PDO::PARAM_INT);
        $request->bindValue(':modifiedDate', date_format($date, 'Y-m-d H:i:s'));
        $request->execute();
    }

    public static function Update($client)
    {
        $DAO = DataAccessObject::getInstance();
        $request = $DAO->prepareRequest("UPDATE clients 
                                                SET name = :name, 
                                                    surname = :surname, 
                                                    documentType = :documentType, 
                                                    documentNumber = :documentNumber, 
                                                    email = :email, 
                                                    clientType = :clientType, 
                                                    country = :country, 
                                                    city = :city, 
                                                    phone = :phone, 
                                                    paymentMethod = :paymentMethod,
                                                    modifiedDate = :modifiedDate
                                                WHERE id = :id AND active = true");
        $request->bindValue(':id', $client->id, PDO::PARAM_INT);
        $request->bindValue(':name', $client->name, PDO::PARAM_STR);
        $request->bindValue(':surname', $client->surname, PDO::PARAM_STR);
        $request->bindValue(':documentType', $client->documentType, PDO::PARAM_INT);
        $request->bindValue(':documentNumber', $client->documentNumber, PDO::PARAM_INT);
        $request->bindValue(':email', $client->email, PDO::PARAM_STR);
        $request->bindValue(':clientType', $client->clientType, PDO::PARAM_INT);
        $request->bindValue(':country', $client->country, PDO::PARAM_STR);
        $request->bindValue(':city', $client->city, PDO::PARAM_STR);
        $request->bindValue(':phone', $client->phone, PDO::PARAM_STR);
        $request->bindValue(':paymentMethod', $client->paymentMethod, PDO::PARAM_INT);
        $date = new DateTime(date("d-m-Y"));
        $request->bindValue(':modifiedDate', date_format($date, 'Y-m-d H:i:s'));
        $request->execute();
    }

    public static function ValidateType($clientType)
    {
        $lowercaseType = strtolower($clientType);

        if ($lowercaseType === 'individual') {
            return EClientType::INDIVIDUAL->value;
        } elseif ($lowercaseType === 'corporativo') {
            return EClientType::CORPORATE->value;
        } else {
            return 0;
        }
    }
    public static function ValidateDocumentType($documentType)
    {
        $lowercaseType = strtolower($documentType);
        $rtn = 0;
        switch ($lowercaseType)
        {
            case 'dni':
                $rtn = EDocumentType::DNI->value;
                break;
            case 'cuil':
                $rtn = EDocumentType::CUIL->value;
                break;
            case 'cuit':
                $rtn = EDocumentType::CUIT->value;
                break;
            case 'pasaporte':
                $rtn = EDocumentType::PASSPORT->value;
                break;
            default;
                return $rtn;
        }

        return $rtn;
    }
    public static function ValidatePaymentMethod($paymentMethod)
    {
        $lowercaseType = strtolower($paymentMethod);
        $rtn = 0;
        switch ($lowercaseType)
        {
            case 'efectivo':
                $rtn = EPaymentMethods::CASH->value;
                break;
            case 'debito':
                $rtn = EPaymentMethods::DEBIT->value;
                break;
            case 'credito':
                $rtn = EPaymentMethods::CREDIT->value;
                break;
            case 'tranferencia':
                $rtn = EPaymentMethods::BANKTRANSFER->value;
                break;
            default;
                return $rtn;
        }

        return $rtn;
    }
}