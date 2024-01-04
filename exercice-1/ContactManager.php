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
        return $list;
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
        return $list;
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
}