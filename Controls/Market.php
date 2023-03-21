<?php
class Market
{
    public static function getMarkets(Request $req, Response $res)
    {
        $res->setTitle('Магазины');
        $res->sendHTML('
            <h1 class="font-semibold text-5xl mb-4 text-gray-800 underline decoration-sky-500">Магазины</h1>
        ');
    }
}