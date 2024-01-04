<?php
include "Command.php";

while (true) {
    $line = readline("Entrez votre commande (help, list, create, delete, detail) : ");
    $pattern = '/^(?P<command>\w+)(?: (?P<id>\d+|\S+))?$/i';
    preg_match($pattern,$line, $command);


    if($command['command'] == "list")
    {
        echo " \nListe des contact : \n \nid, name, email, phone number \n \n";
        echo Command::list();

    }
    elseif($command['command'] == "detail")
    {
        if (isset($command['id']) && is_numeric($command['id']))
        {
            $id = (int)$command['id'];
            echo "\n Détail pour la commande n° $id : \n\n";
            echo Command::detail($id);
        }
        else
        {
            echo "\n La commande detail a été saisie sans ID ou avec un ID invalide. Exemple de commande attendue : detail 0\n\n";
        }
    }
    else
    {
        echo "\nCette commande n'existe pas. Utilisez help si vous souhaitez voir l'ensemble de commande et leur fonctionnalité \n\n";
    }
}