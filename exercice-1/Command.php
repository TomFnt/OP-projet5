<?php
include "ContactManager.php";

class Command
{
 public static function list(){
     return $result= ContactManager::findAll();
 }

 public static function detail($id){
     return $result= ContactManager::findById($id);
 }

 public static function create($name, $email, $phoneNumber){
return $result= ContactManager::createContact($name, $email, $phoneNumber);
 }

 public static function delete($id){
     return $result= ContactManager::delete($id);
 }
}