<?php
include "ContactManager.php";

class Command
{
 public static function list(){
     return $result= ContactManager::findAll();
 }

 public static function detail($matches){
     return $result= ContactManager::findById($matches);
 }

 public static function create($matches){
return $result= ContactManager::createContact($matches);
 }

 public static function delete($matches){
     return $result= ContactManager::delete($matches);
 }

 public static function modify($matches){
    return ContactManager::modifyContact($matches);
 }
}