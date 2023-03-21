<?php
class Request
{
    public string $protocol;
    public string $host;
    public string $requestScheme;
    public string $queryString;
    public string $requestURI;
    public string $requestMethod;
    public string $httpAccept;
    public array $params;
    public array|null $body;

    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->protocol      = $_SERVER['SERVER_PROTOCOL'];
        $this->host          = $_SERVER['HTTP_HOST'];
        $this->requestScheme = $_SERVER['REQUEST_SCHEME'];
        $this->queryString   = $_SERVER['QUERY_STRING'];
        $this->requestURI    = $_SERVER['REQUEST_URI'];
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->httpAccept    = $_SERVER['HTTP_ACCEPT'];
    }
}
