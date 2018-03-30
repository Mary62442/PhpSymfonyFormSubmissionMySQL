<?php

namespace App\Dal;

   class Repo {

    public static function GetItems($connection)
    {

        $sql = "SELECT * FROM items";
        $result = $connection->query($sql);
        $stringItems = "";
        while($row = $result->fetch_assoc()) {
            $stringItems .= "<span class ='test'>".$row["itemName"]." Price: £".$row["itemPrice"]
            ." </span>Quantity: <input type ='number' min='0' max='100' name = '".$row["itemInputId"]."'> <br><br>";                                      
        }
        return $stringItems;
    }

    
    }
?>