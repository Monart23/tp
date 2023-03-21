<?php
require('Core/autoload.php');

Template::getInstance()->setHead('
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script src="https://cdn.tailwindcss.com"></script>
');

Template::getInstance()->useWrapper('
    <div style="background: snow;">
        <div class="container mx-auto">
            <div class="grid grid-cols-12 h-full">
                <div class="col-span-1 p-4 font-semibold text-slate-900 text-xl">
                        <ul class="list-none">
                            <li class="underline decoration-sky-500 hover:no-underline"><a href="delivery">Завоз</a></li>
                            <li class="underline decoration-pink-500 hover:no-underline"><a href="stocks">Склады</a></li>
                            <li class="underline decoration-indigo-500 hover:no-underline"><a href="products">Продукты</a></li>
                            <li class="underline decoration-amber-500 hover:no-underline"><a href="cars">Машины</a></li>
                            <li class="underline decoration-green-500 hover:no-underline"><a href="markets">Магазины</a></li>
                            <li class="underline decoration-red-500 hover:no-underline"><a href="polygon">Полигон</a></li>
                        </ul>
                </div>
                <div class="col-span-11 p-4 rounded-lg" id="root"></div>
            </div>
        </div>
    </div>
', 'root');

$route = new Router();

$CarModel       = new Model(CarModel::class);
$DevileryModel  = new Model(DevileryModel::class);
$MarketModel    = new Model(MarketModel::class);
$PolygonModel   = new Model(PolygonModel::class);
$ProductModel   = new Model(ProductModel::class);
$ScladModel     = new Model(ScladModel::class);

$route->get('/', function (Request $req, Response $res) {
    $res->redirectToURI('http://localhost/delivery');
});

$route->get('/delivery', 'Devilery::getDelivery');
$route->get('/stocks', 'Stocks::getStocks');
$route->get('/products', 'Product::getProducts');

$route->get('/cars', 'Car::getCars');
$route->get('/cars/sp', 'Car::callStoredProcedure');
$route->post('/cars/add', 'Car::addCar');
$route->delete('/cars/:id/delete', 'Car::deleteCar');
$route->patch('/cars/:id/edit', 'Car::editCar');

$route->get('/markets', 'Market::getMarkets');
$route->get('/polygon', 'Polygon::getPolygon');

$http = new Http($route);
$http->listen();