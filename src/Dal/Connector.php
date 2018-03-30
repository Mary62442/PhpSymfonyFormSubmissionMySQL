<?php

namespace App\Dal;

   class Connector {

    public static function Connect() {

        $servername = "localhost";
        $username = "root";
        $password = "atreius";
        $dbname = "world";                        
        $conn = mysqli_connect($servername, $username, $password, $dbname);                
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;

        } 
    }
?>