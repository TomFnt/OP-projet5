<?php

class DBConnect
{
    public static function getPDO(){
        $PDO = New PDO('mysql:host=localhost;dbname=projet5_exo2;charset=utf8',
            'root',
            '');

        return $PDO;
    }

}
