<?php

// if ((empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest')
//     && !defined('MY_APP')
//     && (empty($_SERVER['HTTP_CONTENT_TYPE']) || strtolower($_SERVER['HTTP_CONTENT_TYPE']) !== 'application/json')
// ) {
//     header("location: ../index.php");
// }else{
//     if(!defined('MY_APP')){
//         define('MY_APP', true);
//     }
// }

class Customer {
    public $username;
    public $firstName;
    public $lastName;
    public $address;
    public $postalCode;
    public $location;
    public $country;
    public $gender;

    public function __construct($username, $firstName, $lastName, $address, $postalCode, $location, $country, $gender) {
        $this->username = $username;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->address = $address;
        $this->postalCode = $postalCode;
        $this->location = $location;
        $this->country = $country;
        $this->gender = $gender;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getPostalCode() {
        return $this->postalCode;
    }

    public function getLocation() {
        return $this->location;
    }

    public function getCountry() {
        return $this->country;
    }

    // // Methoden für Datenbankoperationen

    // public function saveToDatabase() {
    //     // Implementierung, um Daten in die Datenbank zu speichern
    // }

    // public static function fetchFromDatabase($username) {
    //     // Implementierung, um Daten aus der Datenbank abzurufen
    // }

    // public function updateInDatabase() {
    //     // Implementierung, um Daten in der Datenbank zu aktualisieren
    // }
}

?>
