<?php
include "Command.php";

while (true) {
    $line = readline("Entrez votre commande (help, list, create, delete, test) : ");

    if($line == "list")
    {
    echo Command::list();

    }
    if($line == "test")
    {

    }

}