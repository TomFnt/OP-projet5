<?php
include "ContactManager.php";

class Command
{
 public static function list(){
     $result= ContactManager::findAll();
     $display= ContactManager::toString($result);
     return $display;
 }
}