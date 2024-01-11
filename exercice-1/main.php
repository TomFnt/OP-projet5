<?php
include "Command.php";

while (true) {

    $line = readline("Entrez votre commande (help, list, create, modify, delete, detail, quit) : ");
    $pattern = '/^\S+|\S+(?=[,])|\d+/i';
    preg_match_all($pattern,$line, $matches);

    $matches= $matches[0];
    $input = array_shift($matches);

    try
    {
        switch ($input)
        {
            case 'create':
            {
                echo Command::create($matches);
                break;
            }

            case 'modify':
            {
                echo Command::modify($matches);
                break;
            }

            case 'list':
            {
                echo " \n Liste des contact : \n \nid, name, email, phone number \n \n";
                echo Command::list();
                break;
            }


            case 'detail':
            {
                echo Command::detail($matches);
                break;
            }

            case 'delete':
            {
                echo Command::delete($matches);
                break;
            }

            case 'help':
            {
                echo "\n help : affiche cette aide \n
                 \n list : affiche l'ensemble des contacts \n
                 \n create [name, email, phone number] : crée un contact \n
                 \n modify [id] : permet de modifier un contact \n
                 \n delete [id] : supprime un contact \n
                 \n quit : quitter le programme \n";
            }
            case 'quit':
            {
                $check= readline("Êtes-vous sûr de vouloir modifier ce contact ? (yes/no) : ");
                if($check == "yes " || $check=="y")
                {
                    exit;
                }
                else
                {
                    echo "\n";
                    break;
                }
            }

            default:
            {
                throw new Exception("Commande non reconnue. Utilisez la commande help pour afficher l'ensemble des commandes et leur fonctionnalité.");
            }
        }
    }
    catch (Exception $e)
    {
        echo "\n \033[1;31m Erreur : " . $e->getMessage() . "\033[0m \n\n";
    }

}