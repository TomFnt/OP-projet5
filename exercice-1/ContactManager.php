<?php
include "DBConnect.php";
include "Contact.php";


class ContactManager
{
    private static $i = 0;

    public static function findById($id)
    {
        $connect = DBConnect::getPDO();
        $sql= "SELECT * FROM contact WHERE id =".$id;

        $request =$connect->prepare($sql);
        $request->execute();
        $result = $request->fetch();

        $detail= New Contact($result['id'],$result['name'],$result['email'],$result['phone_number']);
        $list[0]["id"]= $detail->getId();
        $list[0]["name"]= $detail->getName();
        $list[0]["email"]= $detail->getEmail();
        $list[0]["tel"]= $detail->getPhoneNumber();

        return self::toString($list);
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

    public static function createContact($name, $email, $phoneNumber)
    {

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

    public static function modifyContact($defaultName, $defaultEmail, $defaultPhoneNumber, $name, $email, $phoneNumber, $id)
    {
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

    public static function delete($id)
    {
        $connect = DBConnect::getPDO();
        $sql="DELETE FROM `contact` WHERE  `id`=".$id;
        $request =$connect->prepare($sql);

        if ( $request->execute() ==true ){
            echo "\nSuppression du contact réussi\n\n";
        }
        else {
            echo "\néchec lors de la suppression contact\n\n";
        }
    }
}