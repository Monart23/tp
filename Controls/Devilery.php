<?php
class Devilery
{
    public static function getDelivery(Request $req, Response $res)
    {
        $res->setTitle('Завозы');
        $res->sendHTML('
            <h1 class="font-semibold text-5xl mb-4 text-gray-800 underline decoration-sky-500">Завозы</h1>
        ');
    }
}