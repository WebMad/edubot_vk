<?php


namespace App\API;


abstract class AbstractController
{
    protected function asJson($value)
    {
        header('Content-type: application/json; charset=utf-8');
        return json_encode($value);
    }
}