<?php
class Route
{
    public string $pathURI;
    public string $pathURIRegex;
    public array $pathArgUnits;
    public string $requestMethod;
    public mixed $callback;

    /**
     * Method __construct
     *
     * @param string $pathURI [explicite description]
     * @param string $requestMethod [explicite description]
     * @param mixed $callback [explicite description]
     *
     * @return void
     */
    public function __construct(string $pathURI, string $requestMethod, mixed $callback)
    {
        $this->pathURI       = $pathURI;
        $this->requestMethod = $requestMethod;
        $this->callback      = $callback;

        $this->pathURIRegex  = $this->pathToRegex($pathURI);
        $this->pathArgUnits  = $this->getArgUnits($pathURI);
    }

    /**
     * Method pathToRegex
     *
     * @param string $pathURI [explicite description]
     *
     * @return string
     */
    private function pathToRegex(string $pathURI): string
    {
        $convertedPath = preg_replace('/:(?<arg>\w+)/i', '(?<$1>\\w+)', $pathURI);
        $convertedPath = preg_replace('/\//', '\\/', $convertedPath);

        return "/$convertedPath/";
    }

    /**
     * Method getArgUnits
     *
     * @param string $pathURI [explicite description]
     *
     * @return array
     */
    private function getArgUnits(string $pathURI): array
    {
        $pathArgUnits = [];
        preg_match_all('/:(\w+)/i', $pathURI, $pathArgUnits);

        return $pathArgUnits[1];
    }
}
