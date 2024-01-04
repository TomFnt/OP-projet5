<?php
include "ContactManager.php";

while (true) {
    $line = readline("Entrez votre commande (help, list, create, delete, test) : ");

    if($line == "list")
    {
        $result= ContactManager::findAll();
        ContactManager::toString($result);

    }
    if($line == "test")
    {

    }

}