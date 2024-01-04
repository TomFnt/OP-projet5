<?php
include "DBConnect.php";
include "Contact.php";


class ContactManager
{
    private static $i = 0;

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
        return $list;
    }

    public static function toString(array $list){

        echo " \nListe des contact : \n \nid, name, email, phone number \n \n";
        $i=0;
        foreach ($list as $row){
            $id=$list[$i]['id'];
            $name=$list[$i]['name'];
            $email=$list[$i]['email'];
            $tel=$list[$i]['tel'];

            echo "$id, $name, $email, $tel \n";
            echo "\n";
            $i++;
        }
    }
}