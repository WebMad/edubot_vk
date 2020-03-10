<?php


namespace App\API;


class SiteController extends AbstractController
{
    public function index()
    {
        return $this->asJson([
            'message' => 'Вау, он работает!'
        ]);
    }
}