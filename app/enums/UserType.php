<?php
enum UserType: string
{
    case ADMIN = 'admin';
    case MANAGER = 'gerente';
    case RECEPCIONIST = 'recepcionista';
    case CLIENT = 'cliente';
    case WAITER = 'mozo';
    case CANDYBAR = 'candybar';

    public function getStringValue(): string
    {
        return $this->value;
    }
}