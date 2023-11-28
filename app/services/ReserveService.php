<?php

class ReserveService
{
    public static function Add($reserve)
    {
        $DAO = DataAccessObject::getInstance();
        $request = $DAO->prepareRequest("INSERT INTO reserves 
                                            (clientType, 
                                             checkInDate, 
                                             checkOutDate, 
                                             roomType, 
                                             price, 
                                             status,
                                             active,
                                             modifiedDate)
                                            VALUES (:clientType, 
                                                    :checkInDate, 
                                                    :checkOutDate, 
                                                    :roomType, 
                                                    :price, 
                                                    :status,
                                                    true,
                                                    :modifiedDate
                                                    )");

        $request->bindValue(':clientType', $reserve->clientType, PDO::PARAM_STR);
        $request->bindValue(':checkInDate', date_format($reserve->checkInDate, 'Y-m-d H:i:s'));
        $request->bindValue(':checkOutDate', date_format($reserve->checkOutDate, 'Y-m-d H:i:s'));
        $request->bindValue(':roomType', $reserve->roomType, PDO::PARAM_STR);
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
        $request = $DAO->prepareRequest("SELECT * FROM reserves WHERE id = :id");
        $request->bindValue(':id', $id, PDO::PARAM_INT);
        $request->execute();

        return $request->fetchObject('Reserve');
    }

    public static function GetAll()
    {
        $DAO = DataAccessObject::getInstance();
        $request = $DAO->prepareRequest("SELECT * FROM reserves WHERE active = true");
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
}
