<?php

require_once './Database/DataAccessObject.php';
require_once './enums/UserType.php';
require_once './models/DTOs/UserDTO.php';


class User
{
    private $id;
    private $user;
    private $password;
    private $userType;
    private $active;
    private $modifiedDate;

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        } else {
            return null;
        }
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        } else {
            echo "No existe " . $property;
        }
    }
}