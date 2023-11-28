<?php
require_once './enums/EReserveStatus.php';
class ReportService
{
    public static function GetAmountByRoomAndDate($fecha)
    {
        $DAO = DataAccessObject::getInstance();
        $fecha = $fecha ? new DateTime($fecha) : new DateTime();
        $request = $DAO->prepareRequest("SELECT B.description AS roomType, SUM(price) as totalAmount
                                            FROM segundo_parcial.reserves AS A
                                            INNER JOIN segundo_parcial.room_types AS B
                                            ON A.roomType = B.id
                                            WHERE DATE(A.checkInDate) = COALESCE(:fecha, CURDATE() - INTERVAL 1 DAY)
                                            GROUP BY roomType;");
        $request->bindValue(':fecha', $fecha->format('Y-m-d H:i:s'));
        $request->execute();

        return $request->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function GetReserveByClientId($clientId)
    {
        $DAO = DataAccessObject::getInstance();
        $request = $DAO->prepareRequest("SELECT A.id,
                                                   clientId,
                                                   B.description AS clientType,
                                                   checkInDate,
                                                   checkOutDate,
                                                   C.description AS roomType,
                                                   price,
                                                   D. description AS status
                                                   FROM segundo_parcial.reserves AS A
                                                   INNER JOIN segundo_parcial.client_types AS B
                                                       ON A.clientType = B.id
                                                   INNER JOIN segundo_parcial.room_types AS C
                                                       ON A.roomType = C.id
                                                   INNER JOIN segundo_parcial.reserve_status AS D
                                                       ON A.status = D.id
                                                   WHERE A.clientId = :clientId AND active = true");
        $request->bindValue(':clientId', $clientId, PDO::PARAM_INT);
        $request->execute();

        return $request->fetchObject('Reserve');
    }
    public static function GetReservesBetweenDates($startDate, $endDate )
    {
        $DAO = DataAccessObject::getInstance();
        $request = $DAO->prepareRequest("SELECT A.id,
                                                   clientId,
                                                   B.description AS clientType,
                                                   checkInDate,
                                                   checkOutDate,
                                                   C.description AS roomType,
                                                   price,
                                                   D. description AS status
                                                   FROM segundo_parcial.reserves AS A
                                                   INNER JOIN segundo_parcial.client_types AS B
                                                       ON A.clientType = B.id
                                                   INNER JOIN segundo_parcial.room_types AS C
                                                       ON A.roomType = C.id
                                                   INNER JOIN segundo_parcial.reserve_status AS D
                                                       ON A.status = D.id
                                                   WHERE DATE(checkInDate) BETWEEN :startDate AND :endDate
                                                   AND active = true");
        $request->bindValue(':startDate', date_format(new DateTime($startDate), 'Y-m-d H:i:s'));
        $request->bindValue(':endDate', date_format(new DateTime($endDate), 'Y-m-d H:i:s'));
        $request->execute();

        return $request->fetchObject('Reserve');
    }
    public static function GetAllReservesByRoom()
    {
        $DAO = DataAccessObject::getInstance();
        $request = $DAO->prepareRequest("SELECT B.description AS roomType, COUNT(*) as reservationCount
                                            FROM segundo_parcial.reserves AS A
                                            INNER JOIN segundo_parcial.room_types AS B
                                            ON A.roomType = B.id
                                            GROUP BY roomType;");
        $request->execute();

        return $request->fetchAll(PDO::FETCH_ASSOC);
    }

    //nuevos:
    public static function GetCancelledAmountByClientAndDate($fecha)
    {
        $DAO = DataAccessObject::getInstance();
        $fecha = $fecha ? new DateTime($fecha) : new DateTime();

        $request = $DAO->prepareRequest("SELECT B.description AS clientType, SUM(price) as totalCancelledAmount
                                        FROM segundo_parcial.reserves AS A
                                        INNER JOIN segundo_parcial.client_types AS B
                                        ON A.clientType = B.id
                                        WHERE DATE(A.checkInDate) = COALESCE(:fecha, CURDATE() - INTERVAL 1 DAY)
                                        AND A.status = :cancelledStatus
                                        GROUP BY clientType;");

        $request->bindValue(':fecha', $fecha->format('Y-m-d H:i:s'));
        $request->bindValue(':cancelledStatus', EReserveStatus::CANCELED->value);

        $request->execute();

        return $request->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function GetCancelledReservesByClientId($clientId)
    {
        $DAO = DataAccessObject::getInstance();
        $request = $DAO->prepareRequest("SELECT A.id,
                                               clientId,
                                               B.description AS clientType,
                                               checkInDate,
                                               checkOutDate,
                                               C.description AS roomType,
                                               price,
                                               D.description AS status
                                               FROM segundo_parcial.reserves AS A
                                               INNER JOIN segundo_parcial.client_types AS B
                                                   ON A.clientType = B.id
                                               INNER JOIN segundo_parcial.room_types AS C
                                                   ON A.roomType = C.id
                                               INNER JOIN segundo_parcial.reserve_status AS D
                                                   ON A.status = D.id
                                               WHERE A.clientId = :clientId
                                               AND A.status = :cancelledStatus
                                               AND active = true");

        $request->bindValue(':clientId', $clientId, PDO::PARAM_INT);
        $request->bindValue(':cancelledStatus', EReserveStatus::CANCELED->value);

        $request->execute();

        return $request->fetchAll(PDO::FETCH_CLASS, 'Reserve');
    }

    public static function GetCancelledReservesBetweenDates($startDate, $endDate)
    {
        $DAO = DataAccessObject::getInstance();
        $request = $DAO->prepareRequest("SELECT A.id,
                                               clientId,
                                               B.description AS clientType,
                                               checkInDate,
                                               checkOutDate,
                                               C.description AS roomType,
                                               price,
                                               D.description AS status
                                               FROM segundo_parcial.reserves AS A
                                               INNER JOIN segundo_parcial.client_types AS B
                                                   ON A.clientType = B.id
                                               INNER JOIN segundo_parcial.room_types AS C
                                                   ON A.roomType = C.id
                                               INNER JOIN segundo_parcial.reserve_status AS D
                                                   ON A.status = D.id
                                               WHERE DATE(checkInDate) BETWEEN :startDate AND :endDate
                                               AND A.status = :cancelledStatus
                                               AND active = true");

        $request->bindValue(':startDate', date_format(new DateTime($startDate), 'Y-m-d H:i:s'));
        $request->bindValue(':endDate', date_format(new DateTime($endDate), 'Y-m-d H:i:s'));
        $request->bindValue(':cancelledStatus', EReserveStatus::CANCELED->value);

        $request->execute();

        return $request->fetchAll(PDO::FETCH_CLASS, 'Reserve');
    }

    public static function GetCancelledReservesByClientType()
    {
        $DAO = DataAccessObject::getInstance();
        $request = $DAO->prepareRequest("SELECT B.description AS clientType, COUNT(*) as cancellationCount
                                        FROM segundo_parcial.reserves AS A
                                        INNER JOIN segundo_parcial.client_types AS B
                                        ON A.clientType = B.id
                                        WHERE A.status = :cancelledStatus
                                        GROUP BY clientType;");

        $request->bindValue(':cancelledStatus', EReserveStatus::CANCELED->value);

        $request->execute();

        return $request->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function GetAllOperationsByUser()
    {
        $DAO = DataAccessObject::getInstance();
        $request = $DAO->prepareRequest("SELECT A.id,
                                               clientId,
                                               B.description AS clientType,
                                               checkInDate,
                                               checkOutDate,
                                               C.description AS roomType,
                                               price,
                                               D.description AS status
                                               FROM segundo_parcial.reserves AS A
                                               INNER JOIN segundo_parcial.client_types AS B
                                                   ON A.clientType = B.id
                                               INNER JOIN segundo_parcial.room_types AS C
                                                   ON A.roomType = C.id
                                               INNER JOIN segundo_parcial.reserve_status AS D
                                                   ON A.status = D.id
                                               WHERE active = true
                                               UNION
                                               SELECT A.id,
                                                      clientId,
                                                      B.description AS clientType,
                                                      checkInDate,
                                                      checkOutDate,
                                                      '' AS roomType,
                                                      price,
                                                      D.description AS status
                                                      FROM segundo_parcial.reserves AS A
                                                      INNER JOIN segundo_parcial.client_types AS B
                                                      ON A.clientType = B.id
                                                      INNER JOIN segundo_parcial.reserve_status AS D
                                                      ON A.status = D.id
                                                      WHERE active = true");

        $request->execute();

        return $request->fetchAll(PDO::FETCH_CLASS, 'Reserve');
    }

    public static function GetReservesByModality($paymentMethod)
    {
        $DAO = DataAccessObject::getInstance();
        $request = $DAO->prepareRequest("SELECT A.id,
                                                    clientId,
                                                    B.description AS clientType,
                                                    checkInDate,
                                                    checkOutDate,
                                                    C.description AS roomType,
                                                    price,
                                                    D.description AS status
                                            FROM segundo_parcial.reserves AS A
                                                INNER JOIN segundo_parcial.client_types AS B
                                                    ON A.clientType = B.id
                                                INNER JOIN segundo_parcial.room_types AS C
                                                    ON A.roomType = C.id
                                                INNER JOIN segundo_parcial.reserve_status AS D
                                                    ON A.status = D.id
                                                INNER JOIN segundo_parcial.clients AS E
                                                    ON A.clientId = E.id
                                                INNER JOIN segundo_parcial.payment_types AS F
                                                    ON E.paymentMethod = :paymentMethod
                                                AND E.active = true");

        $request->bindValue(':paymentMethod', $paymentMethod);

        $request->execute();

        return $request->fetchAll(PDO::FETCH_CLASS, 'Reserve');
    }
}