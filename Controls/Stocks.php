<?php
class Stocks
{
    public static function getStocks(Request $req, Response $res)
    {
        $res->setTitle('Склады');
        $res->sendHTML('
            <h1 class="font-semibold text-5xl mb-4 text-gray-800 underline decoration-sky-500">Склады</h1>
        ');
    }
}