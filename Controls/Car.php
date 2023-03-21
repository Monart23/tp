<?php
class Car
{
    public static function getCars(Request $req, Response $res)
    {
        global $CarModel;

        $cars = $CarModel->find([]);

        $res->setTitle('Машины');

        if (empty($cars)) {
            $res->sendHTML('
                <h1 class="font-semibold text-5xl mb-4 text-gray-800 underline decoration-sky-500">Машины</h1>
                <p>Автомобили не найдены</p>
            ');
        } else {
            $carsMarkup = '';

            foreach ($cars as $car) {
                $carsMarkup .= "
                    <tr id=$car->_id>
                        <td>$car->mark</td>
                        <td>$car->owner</td>
                        <td>$car->Mweight</td>
                        <td class='underline cursor-pointer select-none' onclick='edit(event)'>Редактировать</td>
                        <td class='underline cursor-pointer select-none' onclick='remove(event.target.parentElement.attributes.id.nodeValue)'>Удалить</td>
                    </tr>
                ";
            }

            $res->sendHTML("
                <h1 class='font-semibold text-5xl mb-4 text-gray-800 underline decoration-sky-500'>Машины</h1>
                <div class='flex flex-row gap-8'>
                    <div>
                        <table class='text-xl border-separate border-spacing-2'>
                            <thead style='background: khaki;'>
                                <tr>
                                    <th>Марка</th>
                                    <th>Владелец</th>
                                    <th>Грузоподъемность</th>
                                    <th>Редактирование</th>
                                    <th>Удаление</th>
                                </tr>
                            </thead>
                            <tbody class=''>
                                $carsMarkup
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <div class='mb-4'>
                            <input type='text' id='brand'
                                placeholder='Марка'
                                class='p-1 focus:outline-none focus:ring focus:border-blue-500 rounded outline outline-offset-2 outline-2 outline-blue-500' />
                        </div>
                        <div class='mb-4'>
                            <input type='text' id='model'
                            placeholder='Владелец'
                            class='p-1 focus:outline-none focus:ring focus:border-blue-500 rounded outline outline-offset-2 outline-2 outline-blue-500' />
                        </div>
                        <div class='mb-4'>
                            <input type='text' id='weight'
                            placeholder='Грузоподъемность'
                            class='p-1 focus:outline-none focus:ring focus:border-blue-500 rounded outline outline-offset-2 outline-2 outline-blue-500' />
                        </div>
                        <button onclick='add()' class='p-1 font-bold rounded outline outline-2 outline-blue-500 hover:bg-blue-500 hover:text-white'>Добавить</button>
                        <button onclick='call()' class='p-1 font-bold rounded outline outline-2 outline-blue-500 hover:bg-blue-500 hover:text-white'>Посчитать машины</button>
                    </div>
                    <div id='bEdit' class='hidden'>
                        <div class='mb-4'>
                            <input type='text' id='brandSave'
                            placeholder=''
                            class='p-1 focus:outline-none focus:ring focus:border-blue-500 rounded outline outline-offset-2 outline-2 outline-blue-500' />
                        </div>
                        <div class='mb-4'>
                            <input type='text' id='modelSave'
                            placeholder=''
                            class='p-1 focus:outline-none focus:ring focus:border-blue-500 rounded outline outline-offset-2 outline-2 outline-blue-500' />
                        </div>
                        <div class='mb-4'>
                            <input type='text' id='weightSave'
                            placeholder=''
                            class='p-1 focus:outline-none focus:ring focus:border-blue-500 rounded outline outline-offset-2 outline-2 outline-blue-500' />
                        </div>
                        <button onclick='save()' class='p-1 font-bold rounded outline outline-2 outline-blue-500 hover:bg-blue-500 hover:text-white'>Сохранить</button>
                    </div>
                </div>

                <script>
                    function call() {
                        let xhr = new XMLHttpRequest()
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState == XMLHttpRequest.DONE) {
                                alert(JSON.parse(xhr.responseText).count)
                            }
                        }
                        xhr.open('GET', '/cars/sp')

                        xhr.send()
                    }

                    function add() {
                        if (!brand.value || !model.value || !weight.value)
                            return false

                        let xhr = new XMLHttpRequest()
                        xhr.open('POST', '/cars/add')
                        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8')

                        xhr.send(JSON.stringify({
                            b: brand.value,
                            m: model.value,
                            w: weight.value
                        }))

                        if (xhr.status != 200)
                            alert('Ошибка запроса')

                        location.reload()
                    }

                    function remove(id) {
                        let xhr = new XMLHttpRequest()
                        let path = '/cars/' + id + '/delete'
                        xhr.open('DELETE', path)

                        xhr.send()

                        if (xhr.status != 200)
                            alert('Ошибка запроса')

                        location.reload()
                    }

                    function save() {
                        let id = bEdit.dataset.id

                        let xhr = new XMLHttpRequest()
                        let path = '/cars/' + id + '/edit'
                        xhr.open('PATCH', path)
                        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8')

                        xhr.send(JSON.stringify({
                            b: brandSave.value || brandSave.placeholder,
                            m: modelSave.value || modelSave.placeholder,
                            w: weightSave.value || weightSave.placeholder
                        }))

                        if (xhr.status != 200)
                            alert('Ошибка запроса')

                        location.reload()
                    }

                    function edit(e) {
                        brandSave.placeholder = e.target.parentElement.children[0].textContent
                        modelSave.placeholder = e.target.parentElement.children[1].textContent
                        weightSave.placeholder = e.target.parentElement.children[2].textContent
                        bEdit.setAttribute('data-id', e.target.parentElement.id)

                        bEdit.classList.remove('hidden')
                    }
                </script>
            ");
        }
    }

    public static function addCar(Request $req, Response $res)
    {
        global $CarModel;

        $CarModel->create([
            "mark"    => $req->body["b"],
            "owner"   => $req->body["m"],
            "Mweight" => $req->body["w"]
        ]);
    }

    public static function deleteCar(Request $req, Response $res)
    {
        global $CarModel;

        $CarModel->deleteOne(['id' => $req->params['id']]);
    }

    public static function editCar(Request $req, Response $res)
    {
        global $CarModel;

        $CarModel->updateOne([
            "mark"    => $req->body["b"],
            "owner"   => $req->body["m"],
            "Mweight" => $req->body["w"]
        ], [
            'id' => $req->params['id']
        ]);
    }

    public static function callStoredProcedure(Request $req, Response $res)
    {
        $connection = new PDO('mysql:host=localhost;dbname=stock', 'root');
        $stm = $connection->prepare('CALL pGetCountCars');
        $stm->execute();

        $result = $stm->fetch(PDO::FETCH_ASSOC);
        $result = json_encode($result, JSON_FORCE_OBJECT);

        header('Content-Type: application/json');  
        print_r($result);
    }
}