<?php
class Polygon
{
    public static function getPolygon(Request $req, Response $res)
    {
        $res->setTitle('Полигон');
        $res->sendHTML('
            <h1 class="font-semibold text-5xl mb-4 text-gray-800 underline decoration-sky-500">Полигон</h1>
        ');
    }
}