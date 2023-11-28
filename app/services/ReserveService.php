<?php
require_once './database/DataAccessObject.php';
require_once './models/Reserve.php';
require_once './models/DTOs/ClientDTO.php';
require_once './enums/ERoomType.php';
require_once './enums/EReserveStatus.php';
class ReserveService
{
    public static function Add($reserve)
    {
        $DAO = DataAccessObject::getInstance();
        $request = $DAO->prepareRequest("INSERT INTO reserves 
                                            (clientId,
                                             clientType, 
                                             checkInDate, 
                                             checkOutDate, 
                                             roomType, 
                                             price, 
                                             status,
                                             active,
                                             modifiedDate)
                                            VALUES (:clientId,
                                                    :clientType, 
                                                    :checkInDate, 
                                                    :checkOutDate, 
                                                    :roomType, 
                                                    :price, 
                                                    :status,
                                                    true,
                                                    :modifiedDate
                                                    )");
        $request->bindValue(':clientId', $reserve->clientId, PDO::PARAM_INT);
        $request->bindValue(':clientType', $reserve->clientType, PDO::PARAM_INT);
        $request->bindValue(':checkInDate', date_format(new DateTime($reserve->checkInDate), 'Y-m-d H:i:s'));
        $request->bindValue(':checkOutDate', date_format(new DateTime($reserve->checkOutDate), 'Y-m-d H:i:s'));
        $request->bindValue(':roomType', $reserve->roomType, PDO::PARAM_INT);
        $request->bindValue(':price', $reserve->price, PDO::PARAM_STR);
        $request->bindValue(':status', $reserve->status, PDO::PARAM_INT);
        $date = new DateTime(date("d-m-Y"));
        $request->bindValue(':modifiedDate', date_format($date, 'Y-m-d H:i:s'));

        $request->execute();

        return $DAO->getLastId();
    }

    public static function Get($id)
    {
        $DAO = DataAccessObject::getInstance();
        $request = $DAO->prepareRequest("SELECT A.id,
                                                   clientId,
                                                   B.description AS clientType,
                                                   checkInDate,
                                                   checkOutDate,
                                                   C.description AS roomType,
                                                   price,
                                                   status
                                                   FROM segundo_parcial.reserves AS A
                                                   INNER JOIN segundo_parcial.client_types AS B
                                                       ON A.clientType = B.id
                                                   INNER JOIN segundo_parcial.room_types AS C
                                                       ON A.roomType = C.id
                                                   WHERE A.id = :id AND active = true");
        $request->bindValue(':id', $id, PDO::PARAM_INT);
        $request->execute();

        return $request->fetchObject('Reserve');
    }

    public static function GetAll()
    {
        $DAO = DataAccessObject::getInstance();
        $request = $DAO->prepareRequest("SELECT A.id,
                                                   clientId,
                                                   B.description AS clientType,
                                                   checkInDate,
                                                   checkOutDate,
                                                   C.description AS roomType,
                                                   price,
                                                   status
                                                   FROM segundo_parcial.reserves AS A
                                                   INNER JOIN segundo_parcial.client_types AS B
                                                       ON A.clientType = B.id
                                                   INNER JOIN segundo_parcial.room_types AS C
                                                       ON A.roomType = C.id
                                                   WHERE active = true");
        $request->execute();

        return $request->fetchAll(PDO::FETCH_CLASS, 'Reserve');
    }

    public static function Delete($id)
    {
        $DAO = DataAccessObject::getInstance();
        $request = $DAO->prepareRequest("UPDATE reserves SET active = false WHERE id = :id");
        $request->bindValue(':id', $id, PDO::PARAM_INT);
        $request->execute();
    }

    public static function Update($reserve)
    {
        $DAO = DataAccessObject::getInstance();
        $request = $DAO->prepareRequest("UPDATE reserves
                                         SET clientType = :clientType,
                                             checkInDate = :checkInDate,
                                             checkOutDate = :checkOutDate,
                                             roomType = :roomType,
                                             price = :price,
                                             status = :status
                                         WHERE id = :id");

        $request->bindValue(':id', $reserve->id, PDO::PARAM_INT);
        $request->bindValue(':clientId', $reserve->clientId, PDO::PARAM_INT);
        $request->bindValue(':clientType', $reserve->clientType, PDO::PARAM_STR);
        $request->bindValue(':checkInDate', $reserve->checkInDate, PDO::PARAM_STR);
        $request->bindValue(':checkOutDate', $reserve->checkOutDate, PDO::PARAM_STR);
        $request->bindValue(':roomType', $reserve->roomType, PDO::PARAM_STR);
        $request->bindValue(':price', $reserve->price, PDO::PARAM_INT);
        $request->bindValue(':status', $reserve->status, PDO::PARAM_INT);

        $request->execute();
    }

    public static function ValidateRoomType($roomType)
    {
        $lowercaseType = strtolower($roomType);

        if ($lowercaseType === 'simple') {
            return ERoomType::SIMPLE->value;
        } elseif ($lowercaseType === 'doble') {
            return ERoomType::DOUBLE->value;
        } elseif ($lowercaseType === 'suite') {
            return ERoomType::SUITE->value;
        } else {
            return 0;
        }
    }
}
