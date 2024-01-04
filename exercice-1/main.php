<?php
include "Command.php";

while (true) {
    $line = readline("Entrez votre commande (help, list, create, delete, detail) : ");
    $pattern = '/^(?P<command>\w+)(?: (?P<arguments>.*\d+|\S+))?$/i';
    preg_match($pattern,$line, $matches);


    $input = strtolower($matches['command']);
    $arguments = isset($matches['arguments']) ? $matches['arguments'] : null;

    switch ($input) {
        case 'create':

            var_dump( $arguments);
            break;

        case 'list':
            echo " \n Liste des contact : \n \nid, name, email, phone number \n \n";
            echo Command::list();
            break;

        case 'detail':
            if ($arguments !=null && is_numeric($arguments))
            {
                $id = (int)$arguments;
                echo "\n Détail pour la commande n° $id : \n\n";
                echo Command::detail($id);
            }
            elseif ($arguments !=null )
            {
                echo "\n Vous n'avez pas saisi un ID valide. Exemple pour a commande detail : detail 0 \n\n";
            }
            else {
                echo "\n La commande detail a été saisie sans ID. Exemple pour a commande detail: detail 0 \n\n";
            }
            break;

        case 'delete':
            if ($arguments !=null && is_numeric($arguments))
            {
                $id = (int)$arguments;
                echo "delete l'id $id \n\n";
            }
            elseif ($arguments !=null )
            {
                echo "\n Vous n'avez pas saisi un ID valide. Exemple pour a commande delete : delete 0 \n\n";
            }
            else {
                echo "\n La commande delete a été saisie sans ID. Exemple pour a commande delete: delete 0 \n\n";
            }
            break;

        default:
            echo " \n Commande non reconnue. Utilisez la commande help pour afficher l'ensemble des commandes et leur fonctionnalité. \n\n";
            break;
    }
}