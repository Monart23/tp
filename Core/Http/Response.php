<?php
class Response
{
    private object $template;

    public function __construct()
    {
        $this->template = Template::getInstance();
    }

    /**
     * Method setHeader
     *
     * @param array $header [explicite description]
     *
     * @return void
     */
    public function setHeader(array $header = []): void
    {
        if (!empty($header)) {
            foreach ($header as $prop) {
                header($prop);
            }
        }
    }

    /**
     * Method setTitle
     *
     * @param string $title [explicite description]
     *
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->template->setTitle($title);
    }

    /**
     * Method setHead
     *
     * @param string $head [explicite description]
     *
     * @return void
     */
    public function setHead(string $head): void
    {
        $this->template->setHead($head);
    }

    /**
     * Method sendHTML
     *
     * @param string $body [explicite description]
     * @param int $responseCode [explicite description]
     * @param array $header [explicite description]
     *
     * @return void
     */
    public function sendHTML(string $body = null, int $responseCode = 200, array $header = []): void
    {
        $this->setHeader($header);
        http_response_code($responseCode);
        $this->template->renderHTML($body);
    }

    /**
     * Method sendJSON
     *
     * @param mixed $value [explicite description]
     * @param int $responseCode [explicite description]
     *
     * @return void
     */
    public function sendJSON(mixed $value, int $responseCode = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($responseCode);

        if (is_object($value)) {
            echo json_encode((array)$value, JSON_FORCE_OBJECT);
        } else if (is_array($value)) {
            // to do...
        }
    }

    /**
     * Method redirectTo
     *
     * @param string $to [explicite description]
     *
     * @return void
     */
    public function redirectToURI(string $to): void
    {
        $matches = [];
        preg_match('/https:\/\/|http:\/\//i', $to, $matches);

        if ($matches) {
            header("Location: $to");
        } else {
            header("Location: https://" . $to);
        }

        die();
    }
}
