<?php
class Template
{
    private static object $instance;
    private string $head    = '';
    private string $title   = '';
    private string $wrapper = '';
    private string $root    = '';
    private bool   $isWrap  = false;

    private function __construct() {}
    private function __clone() {}
    public function __wakeup() {}

    /**
     * Method getInstance
     *
     * @return object
     */
    public static function getInstance(): object
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Method setTitle
     *
     * @param string $title [explicite description]
     *
     * @return void
     */
    public function setTitle(string $title = 'Default'): void
    {
        $this->title = "
            <title>{$title}</title>
        ";
        $this->setHead($this->head);
    }

    /**
     * Method setHead
     *
     * @param string $head [explicite description]
     *
     * @return void
     */
    public function setHead(string $head = null): void
    {
        $title = $this->title;
        $this->head = "
            <head>
                $title
                $head
            </head>
        ";
    }
    
    /**
     * Method useWrapper
     *
     * @param string $wrapper [explicite description]
     * @param string $root [explicite description]
     *
     * @return void
     */
    public function useWrapper(string $wrapper = '<div id="root"></div>', string $root = 'root'): void
    {
        $this->wrapper = $wrapper;
        $this->root    = $root;
        $this->isWrap  = true;
    }
    
    /**
     * Method createNodesFromString
     *
     * @param object $doc [explicite description]
     * @param string $str [explicite description]
     *
     * @return DOMNode
     */
    private function createNodesFromString(object $doc, string $str): DOMNode|false {
        $d = new DOMDocument;
        $d->loadHTML(mb_convert_encoding($str, 'HTML-ENTITIES', 'UTF-8'));
        return $doc->importNode($d->documentElement,true);
    }

    /**
     * Method renderHTML
     *
     * @param string $body [explicite description]
     *
     * @return void
     */
    public function renderHTML(string $body = null): void
    {
        if ($this->isWrap) {
            $doc = new DOMDocument;

            libxml_use_internal_errors(true);
            $doc->loadHTML(mb_convert_encoding($this->wrapper, 'HTML-ENTITIES', 'UTF-8'));
            libxml_clear_errors();
            
            $root = $doc->getElementById($this->root);
            $child = $this->createNodesFromString($doc, $body);

            if ($child) {
                $root->appendChild($child);
                $body = $doc->saveHTML();
            }
        }
    
        $head = $this->head;

        echo <<< HTML
                $head
                $body
            HTML;
    }
}
