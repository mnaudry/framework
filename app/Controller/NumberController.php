<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NumberController {

    public function index(Request $request) : Response {
        [$msec , $sec] = explode(" ",microtime());
        srand(($msec * 1000000) + $sec );
        $number = rand(1,100);

        return new Response("Number is $number");
    }
}