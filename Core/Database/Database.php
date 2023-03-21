<?php
require_once('Constant.php');
// use PDO;

class Database
{
    /**
     * Method createConnect
     *
     * @return object
     */
    public static function createConnect(): object
    {
        try {
            $connection = new PDO(
                'mysql:host=' . Constant::$HOST . ';dbname=' . Constant::$DB_NAME . '',
                Constant::$LOGIN,
                Constant::$PASSWORD
            );
            return $connection;
        } catch (\PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    /**
     * Method closeConnect
     *
     * @param &$connection $connection [explicite description]
     *
     * @return void
     */
    public static function closeConnect(&$connection)
    {
        $connection = null;
    }
}
