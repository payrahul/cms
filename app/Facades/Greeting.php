<?php 

namespace App\Facades;

use illuminate\Support\Facades\Facade;

class Greeting extends Facade{


    protected static function getFacadeAccessor(){
        return 'greetings';
    }

}