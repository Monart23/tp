<?php
require('Request.php');
require('Response.php');

class Http
{
    private object $router;
    private object $request;
    private object $response;

    /**
     * Method __construct
     *
     * @param object $router [explicite description]
     *
     * @return void
     */
    public function __construct(object &$router)
    {
        $this->router  = $router;
        $this->request  = new Request();
        $this->response = new Response();
    }

    /**
     * Method listen
     *
     * @return void
     */
    public function listen(): void
    {
        $route = $this->router->findRoute($this->request);

        if ($route) {
            call_user_func($route->callback, $this->request, $this->response);
        } else {
            $this->response->setTitle('Упс');
            $this->response->sendHTML("
                <h1 class='font-monospace'>Упс. Адрес не найден ;(</h1>
            ", 404);
        }
    }
}
