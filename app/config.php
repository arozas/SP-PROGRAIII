<?php
require_once './enums/UserType.php';

return [
    'user_types' => [
        'admin' => [
            'allowed_routes' => [
                'GET:/app/users/',
                'GET:/app/users/{id}',
                'POST:/app/users/',
                'PUT:/app/users/{id}',
                'DELETE:/app/users/{id}',

                'GET:/app/clients/',
                'GET:/app/clients/{id}',
                'POST:/app/clients/',
                'PUT:/app/clients/{id}',
                'DELETE:/app/clients/{id}',

                'GET:/app/reserves/',
                'GET:/app/reserves/{id}',
                'POST:/app/reserves/',
                'PUT:/app/reserves/{id}',
                'DELETE:/app/reserves/{id}',

                'GET:/app/reports/room_and_date/[{fecha}]',
                'GET:/app/reports/by_client/{clientId}',
                'GET:/app/reports/reserves_between_dates/{startDate}/{endDate}',
                'GET:/app/reports/by_rooms',
                'GET:/app/reports/cancelled-amount-by-client-and-date[/{fecha}]',
                'GET:/app/reports/cancelled-reserves-by-client/{id}',
                'GET:/app/reports/cancelled-reserves-between-dates/{startDate}/{endDate}',
                'GET:/app/reports/cancelled-reserves-by-client-type',
                'GET:/app/reports/all-operations-by-user',
                'GET:/app/reports/reserves-by-payment-method/{paymentMethod}',
            ],
        ],
        'gerente' => [
            'allowed_routes' => [
                'POST:/app/users/',
                'PUT:/app/users/{id}',
                'DELETE:/app/users/{id}',

                'POST:/app/clients/',
                'PUT:/app/clients/{id}',
                'DELETE:/app/clients/{id}',
            ],
        ],
        'recepcionista' => [
            'allowed_routes' => [
                'GET:/app/reserves/',
                'GET:/app/reserves/{id}',
                'POST:/app/reserves/',
                'PUT:/app/reserves/{id}',
                'DELETE:/app/reserves/{id}',

                'GET:/app/reports/room_and_date/[{fecha}]',
                'GET:/app/reports/by_client/{clientId}',
                'GET:/app/reports/reserves_between_dates/{startDate}/{endDate}',
                'GET:/app/reports/by_rooms',
                'GET:/app/reports/cancelled-amount-by-client-and-date[/{fecha}]',
                'GET:/app/reports/cancelled-reserves-by-client/{id}',
                'GET:/app/reports/cancelled-reserves-between-dates/{startDate}/{endDate}',
                'GET:/app/reports/cancelled-reserves-by-client-type',
                'GET:/app/reports/all-operations-by-user',
                'GET:/app/reports/reserves-by-payment-method/{paymentMethod}',
            ],
        ],
        'cliente' => [
            'allowed_routes' => [
                'GET:/app/reserves/',
                'GET:/app/reserves/{id}',
                'POST:/app/reserves/',
                'PUT:/app/reserves/{id}',
                'DELETE:/app/reserves/{id}',

                'GET:/app/reports/room_and_date/[{fecha}]',
                'GET:/app/reports/by_client/{clientId}',
                'GET:/app/reports/reserves_between_dates/{startDate}/{endDate}',
                'GET:/app/reports/by_rooms',
                'GET:/app/reports/cancelled-amount-by-client-and-date[/{fecha}]',
                'GET:/app/reports/cancelled-reserves-by-client/{id}',
                'GET:/app/reports/cancelled-reserves-between-dates/{startDate}/{endDate}',
                'GET:/app/reports/cancelled-reserves-by-client-type',
                'GET:/app/reports/all-operations-by-user',
                'GET:/app/reports/reserves-by-payment-method/{paymentMethod}',
            ],
        ],
        // Agregar más tipos de usuarios...
    ],
];

