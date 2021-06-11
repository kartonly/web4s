<?php

namespace App\Api\Utils;

class HeaderUtils
{
    public function check(string $login, string $pw){
        if($login=='admin' && $pw=='admin')
            return true;
        else return false;
    }
}