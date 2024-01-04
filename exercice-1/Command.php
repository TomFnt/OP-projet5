<?php
include "ContactManager.php";

class Command
{
 public static function list(){
     $result= ContactManager::findAll();
     $display= ContactManager::toString($result);
     return $display;
 }

 public static function detail($id){
     $result= ContactManager::findById($id);
     $display= ContactManager::toString($result);
     return $display;
 }
}