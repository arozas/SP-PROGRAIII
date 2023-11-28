<?php

require_once './enums/EDocumentType.php';

class ClientDTO
{
    public $id;
    public $name;
    public $surname;
    public $documentType;
    public $documentNumber;
    public $email;
    public $clientType;
    public $country;
    public $city;
    public $phone;
    public $paymentMethod;

    public function getDocumentTypeText(): string
    {
        switch ($this->documentType) {
            case EDocumentType::DNI->value:
                return 'DNI';
            case EDocumentType::CUIL->value:
                return 'CUIL';
            case EDocumentType::CUIT->value:
                return 'CUIT';
            case EDocumentType::PASSPORT->value:
                return 'Pasaporte';
            default:
                return '';
        }
    }

    public function getClientTypeText(): string
    {
        switch ($this->clientType) {
            case EClientType::INDIVIDUAL->value:
                return 'Individual';
            case EClientType::CORPORATE->value:
                return 'Corporativo';
            default:
                return '';
        }
    }

    public function getPaymentMethodText(): string
    {
        switch ($this->paymentMethod) {
            case EPaymentMethods::CASH->value:
                return 'Efectivo';
            case EPaymentMethods::DEBIT->value:
                return 'Debito';
            case EPaymentMethods::CREDIT->value:
                return 'Crédito';
            case EPaymentMethods::BANKTRANSFER->value:
                return 'Tranferencia Bancaria';
            default:
                return '';
        }
    }
}