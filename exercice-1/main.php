<?php
include "Command.php";

while (true) {
    $line = readline("Entrez votre commande (help, list, create, modify, delete, detail) : ");
    $pattern = '/^\S+|\S+(?=[,])|\d+/i';
    preg_match_all($pattern,$line, $matches);


    $matches= $matches[0];
    $input = array_shift($matches);

    switch ($input) {
        case 'create':
            if(count($matches)== 3){
                $name= array_shift($matches);
                $email= array_shift($matches);
                $phoneNumber=array_shift($matches);
                return Command::create($name, $email, $phoneNumber);
            }
            elseif (!empty($matches)){
                echo "\nAucun argument n'a été saisi. \n\n";
                echo "Exemple pour créer une nouveau contact : create John Doe, johnd@gmail.com, 0312345678 \n\n";
            }
            else{
                echo "\nLe nombre d'argument n'est pas valide. \n\n";
                echo "Exemple pour créer une nouveau contact : create John Doe, johnd@gmail.com, 0312345678 \n\n";
            }

            break;

        case 'modify':
        {
            if (!empty($matches) && is_numeric($matches[0]))
            {
                $id = (int)$matches[0];
                echo "\n Détail pour la commande n° $id : \n\n";
                echo Command::detail($id);
                $check= readline("Êtes-vous sûr de vouloir modifier ce contact ? (yes/no) : ");

                if($check = "yes")
                {
                    $contact = ContactManager::getContact($id);
                    $defaultName=$contact->getName();
                    $defaultEmail=$contact->getEmail();
                    $defaultPhoneNumber=$contact->getPhoneNumber();

                    $name = readline("Nom (valeur actuelle : $defaultName) :");
                    $email = readline("Adresse mail (valeur actuelle : $defaultEmail) :");
                    $phoneNumber= readline("Numéro de téléphone (valeur actuelle : $defaultPhoneNumber) :");

                    Command::modify($defaultName, $defaultEmail, $defaultPhoneNumber, $name, $email, $phoneNumber, $id);
                }
                else{
                    echo "Annluation de la modification.";
                }

            }
            elseif (!empty($matches))
            {
                echo "\n Vous n'avez pas saisi un ID valide. \n\nExemple pour a commande modify : modify 0 \n\n";
            }
            else {
                echo "\n La commande detail a été saisie sans ID. \n\nExemple pour a commande modify : modify 0 \n\n";
            }
            break;
        }

        case 'list':
            echo " \n Liste des contact : \n \nid, name, email, phone number \n \n";
            echo Command::list();
            break;

        case 'detail':
            if (!empty($matches) && is_numeric($matches[0]))
            {
                $id = (int)$matches[0];
                echo "\n Détail pour la commande n° $id : \n\n";
                echo Command::detail($id);
            }
            elseif (!empty($matches))
            {
                echo "\n Vous n'avez pas saisi un ID valide. \n\nExemple pour a commande detail : detail 0 \n\n";
            }
            else {
                echo "\n La commande detail a été saisie sans ID. \n\nExemple pour a commande detail: detail 0 \n\n";
            }
            break;

        case 'delete':
            if (!empty($matches) && is_numeric($matches[0]))
            {
                $id = (int)$matches[0];
                $check= readline("Êtes-vous sûr de vouloir supprimer le contact n°$id ? (yes/no) : ");
                if($check=="yes" || $check=="y")
                {
                   Command::delete($id);
                }
                else
                {
                echo "\nAnnulation de la suppression du contact n°$id. \n\n";
                }
            }
            elseif (!empty($matches))
            {
                echo "\n Vous n'avez pas saisi un ID valide. Exemple pour a commande delete : delete 0 \n\n";
            }
            else {
                echo "\n La commande delete a été saisie sans ID. Exemple pour a commande delete: delete 0 \n\n";
            }
            break;

        case 'help':
            {
                echo "\n help : affiche cette aide \n
                 \n list : affiche l'ensemble des contacts \n
                 \n create [name, email, phone number] : crée un contact \n
                 \n modify [id] : permet de modifier un contact \n
                 \n delete [id] : supprime un contact \n
                 \n quit : quitter le programme \n";
            }

        default:
            echo " \n Commande non reconnue. Utilisez la commande help pour afficher l'ensemble des commandes et leur fonctionnalité. \n\n";
            break;
    }
}