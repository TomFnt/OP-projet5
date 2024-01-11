<?php
include "DBConnect.php";
include "Contact.php";


class ContactManager
{
    private static $i = 0;

    public static function findById($matches)
    {
        if (!empty($matches) && is_numeric($matches[0])) {
            $id = (int)$matches[0];

            $connect = DBConnect::getPDO();
            $sql = "SELECT * FROM contact WHERE id =" . $id;

            $request = $connect->prepare($sql);
            $request->execute();
            $result = $request->fetch();

            $detail = new Contact($result['id'], $result['name'], $result['email'], $result['phone_number']);
            $list[0]["id"] = $detail->getId();
            $list[0]["name"] = $detail->getName();
            $list[0]["email"] = $detail->getEmail();
            $list[0]["tel"] = $detail->getPhoneNumber();
            echo "\n Détail pour la commande n° $id : \n\n";
            return self::toString($list);
        }

        elseif (!empty($matches) && !is_numeric($matches[0]))
        {
            throw new Exception("Vous n'avez pas saisi un ID valide. Exemple pour a commande detail : detail 0 ");
        }
        else {
            throw new Exception(" La commande detail a été saisie sans ID. Exemple pour a commande detail: detail 0 ");
        }
    }

    public static function getContact($id)
    {
        $connect = DBConnect::getPDO();
        $sql= "SELECT * FROM contact WHERE id =".$id;

        $request =$connect->prepare($sql);
        $request->execute();
        $result = $request->fetch();

        return $contact= New Contact($result['id'],$result['name'],$result['email'],$result['phone_number']);
    }

    public static function findAll()
    {

        $connect = DBConnect::getPDO();
        $sql="SELECT * FROM contact";

        $request =$connect->prepare($sql);
        $request->execute();
        $results = $request->fetchAll();

        foreach ($results as $result){
            $contact[self::$i]= New Contact($result['id'],$result['name'],$result['email'],$result['phone_number']);

            $list[self::$i]["id"]= $contact[self::$i]->getId();
            $list[self::$i]["name"]= $contact[self::$i]->getName();
            $list[self::$i]["email"]= $contact[self::$i]->getEmail();
            $list[self::$i]["tel"]= $contact[self::$i]->getPhoneNumber();
            self::$i++;
        }
        return self::toString($list);
    }

    public static function toString(array $list){

        foreach ($list as $row){
            $id=$row['id'];
            $name=$row['name'];
            $email=$row['email'];
            $tel=$row['tel'];

            echo "$id, $name, $email, $tel \n";
            echo "\n";

        }
    }

    public static function createContact($matches)
    {

        if(count($matches)== 3){
            $name= array_shift($matches);
            $email= array_shift($matches);
            $phoneNumber=array_shift($matches);
            $connect = DBConnect::getPDO();
            $sql="INSERT INTO `contact` (`name`, `email`, `phone_number`) VALUES (?, ?, ?);";
            $request =$connect->prepare($sql);

            if ($request->execute([$name, $email, $phoneNumber]) ==true){
                echo "\nCréation du nouveau contact réussi\n\n";
            }
            else {
                echo "\néchec lors de la création du nouveau contact\n\n";
            }
        }
        elseif (empty($matches)== true){
            throw new Exception("Aucun argument n'a été saisi. Exemple pour créer un nouveau contact : create John Doe, johnd@gmail.com, 0312345678 ");
        }
        else{
            throw new Exception("Le nombre d'arguments n'est pas valide. Exemple pour créer un nouveau contact : create John Doe, johnd@gmail.com, 0312345678 ");
        }
    }

    public static function modifyContact($matches)
    {

        if (!empty($matches) && is_numeric($matches[0]))
        {
            $id = (int)$matches[0];

            echo Command::detail($matches);
            $check= readline("Êtes-vous sûr de vouloir modifier ce contact ? (yes/no) : ");

            if($check == "yes" || $check=="y")
            {
                $contact = ContactManager::getContact($id);
                $defaultName=$contact->getName();
                $defaultEmail=$contact->getEmail();
                $defaultPhoneNumber=$contact->getPhoneNumber();

                $name = readline("Nom (valeur actuelle : $defaultName) :");
                $email = readline("Adresse mail (valeur actuelle : $defaultEmail) :");
                $phoneNumber= readline("Numéro de téléphone (valeur actuelle : $defaultPhoneNumber) :");
                if($name ==null)
                {
                    $name= $defaultName;
                }
                if($email ==null)
                {
                    $email= $defaultEmail;
                }
                if($phoneNumber ==null)
                {
                    $phoneNumber= $defaultPhoneNumber;
                }

                $connect = DBConnect::getPDO();
                $sql="UPDATE `contact` SET `name`=?, `email`=?, `phone_number`=? WHERE  `id`=?;";
                $request =$connect->prepare($sql);

                if ($request->execute([$name, $email, $phoneNumber, $id]) ==true){
                    echo "\nModification du contact n°$id réussi\n\n";
                }
                else {
                    echo "\néchec lors de la modification du contact \n\n";
                }
            }
            else{
                echo "\nAnnluation de la modification.\n\n";
            }

        }
        elseif (!empty($matches) && !is_numeric($matches[0]))
        {
            throw new Exception(" Vous n'avez pas saisi un ID valide. Exemple pour a commande modify : modify 0 ");
        }
        else {
            throw new Exception(" La commande modify a été saisie sans ID. Exemple pour a commande modify : modify 0 ");
        }
    }

    public static function delete($matches)
    {
        if (!empty($matches) && is_numeric($matches[0])) {
            $id = (int)$matches[0];
            $check = readline("Êtes-vous sûr de vouloir supprimer le contact n°$id ? (yes/no) : ");

            if ($check == "yes" || $check == "y") {
                $connect = DBConnect::getPDO();
                $sql = "DELETE FROM `contact` WHERE  `id`=" . $id;
                $request = $connect->prepare($sql);

                if ($request->execute() == true)
                {
                    echo "\nSuppression du contact réussi\n\n";
                }
                else
                {
                    echo "\néchec lors de la suppression contact\n\n";
                }
            }

            else
            {
                echo "\nAnnulation de la suppression du contact n°$id. \n\n";
            }
        }
        elseif (!empty($matches) && !is_numeric($matches[0]))
        {
            throw new Exception(" Vous n'avez pas saisi un ID valide. Exemple pour a commande delete : delete 0 ");
        }
        else
        {
            throw new Exception(" La commande delete a été saisie sans ID. Exemple pour a commande delete: delete 0 ");
        }
    }
}