<?php
class Product
{
    public static function getProducts(Request $req, Response $res)
    {
        $res->setTitle('Продукты');
        $res->sendHTML('
            <h1 class="font-semibold text-5xl mb-4 text-gray-800 underline decoration-sky-500">Продукты</h1>
        ');
    }
}