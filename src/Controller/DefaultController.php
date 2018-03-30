<?php

namespace App\Controller;
use App\Model\Item;
use App\Dal\Connector;
use App\Dal\Repo;


use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    public function index()
    { 
        return new Response(
        '<html><body>Hello from first app</body></html>'
        );
    }

    public function about()
    { 
        // for querystring http://localhost:8000/about?name=pippala
        //$uri = $_SERVER['REQUEST_URI'];
        if(!isset($_GET['name'])) return new Response("Insert a proper querystring"); 
        $name = $_GET['name']; 
        return new Response("<p>{$name}</p>"); 
    }

    public function apipost()
    { 
        // for post parameters 
        if(!isset($_POST['name'])) return new Response("Insert a proper querystring"); 
        $name = $_POST['name'];
        return new Response(
        "<p>{$name}</p>"
        );
    }

    public function officeSuppliesForm()
    { 
        $cn = new Connector();
        $conn= $cn->Connect();
        $repo = new Repo();        
        $stringItems = $repo->GetItems($conn);
        $conn->close();            

        return new Response("
            <form action = '/submitform' method='POST'>  
            {$stringItems}
            <input type='submit'>
            </form>"          
        ); 
    }

    public function submitForm()
    { 

        $cn = new Connector();
        $conn= $cn->Connect();

        $sqlName = "SELECT itemName FROM items";
        $resultName = $conn->query($sqlName);          

        $itemNameCollection = Array();
        //mysqli is required for php7
        while($row = mysqli_fetch_array( $resultName)){
            $itemNameCollection[] = $row[0];  
        }

        $sqlPrice = "SELECT itemPrice FROM items";
        $resultPrice = $conn->query($sqlPrice);          

        $itemPriceCollection = array();           
        while($row = mysqli_fetch_array( $resultPrice)){
            $itemPriceCollection[] = $row[0];  
        };

        $sqlInputId = "SELECT itemInputId FROM items";
        $resultInputId = $conn->query($sqlInputId);          

        $itemInputIdCollection = array();            
        while($row = mysqli_fetch_array( $resultInputId)){
            $itemInputIdCollection[] = $row[0];  
        };
        $arrayLength = sizeof($itemNameCollection);          
        $totalAmount = 0;

        $responseString = "";

        for ($i = 0; $i < $arrayLength ; $i++ ) {
            ${$itemNameCollection[$i]} = new Item();
            ${$itemNameCollection[$i]}->name = $itemNameCollection[$i];
            ${$itemNameCollection[$i]}->price = $itemPriceCollection[$i];
                           
            if($_POST[$itemInputIdCollection[$i]]=="") {
                ${$itemInputIdCollection[$i]} = "0";
            }
            else {
                ${$itemInputIdCollection[$i]} = $_POST[$itemInputIdCollection[$i]];
            }

            ${$itemNameCollection[$i]}->quantity = ${$itemInputIdCollection[$i]};

            $priceForItem = ${$itemNameCollection[$i]}->quantity * ${$itemNameCollection[$i]}->price;

            $totalAmount +=  $priceForItem;

            $responseString .= "<h3>Item: ".${$itemNameCollection[$i]}->name."</h3><p>Price: £"
            .${$itemNameCollection[$i]}->price."</p><p>Quantity: ".${$itemNameCollection[$i]}->quantity.
            "</p><p>Total price for ".${$itemNameCollection[$i]}->name."s: £".$priceForItem;                       
        }

        return new Response(
            "<h2>Your order is as follows: </h2>
            {$responseString}
            <h3>Total amount: £{$totalAmount}</h3>
            "
        );
    }




}