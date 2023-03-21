<?php
require('Core/Database/Database.php');

class Model
{
    private string $className       = '';
    private array $classProperties  = [];
    private string $classPropertyAI = '';
    private string $chainQuery      = '';

    /**
     * Method __construct
     *
     * @param string $class [explicite description]
     *
     * @return void
     */
    public function __construct(string $class)
    {
        $classReflection = new \ReflectionClass($class);
        $this->className = str_replace('Model', '', $classReflection->getShortName());
        $classProperties = $classReflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($classProperties as $prop) {
            $nameProp = $prop->getName();
            $isAutoIncrementProp = $nameProp[0] === '_';

            if ($isAutoIncrementProp) {
                $this->classPropertyAI = substr($nameProp, 1);
            }

            array_push($this->classProperties, $prop->getName());
        }
    }

    /**
     * Method select
     *
     * @param string $columns [explicite description]
     *
     * @return object
     */
    public function select(string $columns = '*'): object
    {
        $this->chainQuery .= "SELECT {$columns} FROM {$this->className} ";

        return $this;
    }

    /**
     * Method insert
     *
     * @param array $params [explicite description]
     *
     * @return object
     */
    public function insert(array $params = []): object
    {
        $prepareColumns = [];
        $prepareParams  = [];

        foreach ($params as $key => $value) {
            if (in_array($key, $this->classProperties)) {
                $prepareColumns[] = $key;
                $prepareParams[]  = "'{$value}'";
            }
        }

        $prepareColumns = implode(',', $prepareColumns);
        $prepareParams  = implode(',', $prepareParams);

        $this->chainQuery .= "INSERT INTO {$this->className} ({$prepareColumns}) VALUES ({$prepareParams})";

        return $this;
    }

    /**
     * Method delete
     *
     * @return object
     */
    public function delete(): object
    {
        $this->chainQuery .= "DELETE FROM {$this->className} ";

        return $this;
    }

    /**
     * Method limit
     *
     * @param int $number [explicite description]
     *
     * @return object
     */
    public function limit(int $number = 1): object
    {
        $this->chainQuery .= " LIMIT {$number}";

        return $this;
    }

    public function leftJoin(string $table, array $conditions): object
    {
        $this->chainQuery .= " LEFT JOIN {$table} ON";
        $index = 0;

        foreach ($conditions as $key => $value) {
            if ($index === count($conditions) - 1) {
                $this->chainQuery .= " {$table}.{$key} OP {$this->className}.{$value}";
            } else {
                $this->chainQuery .= "{$table}.{$key} OP {$this->className}.{$value} AND ";
            }
            
            $index = $index + 1;
        }

        return $this;
    }

    public function eq(): object
    {
        $this->chainQuery = str_replace('OP', '=', $this->chainQuery);

        return $this;
    }
    
    /**
     * Method gt
     *
     * @return object
     */
    public function gt(): object
    {
        $this->chainQuery = str_replace('OP', '>', $this->chainQuery);

        return $this;
    }
    
    /**
     * Method lt
     *
     * @return object
     */
    public function lt(): object
    {
        $this->chainQuery = str_replace('OP', '<', $this->chainQuery);

        return $this;
    }
        
    /**
     * Method ge
     *
     * @return object
     */
    public function ge(): object
    {
        $this->chainQuery = str_replace('OP', '>=', $this->chainQuery);

        return $this;
    }
    
    /**
     * Method le
     *
     * @return object
     */
    public function le(): object
    {
        $this->chainQuery = str_replace('OP', '<=', $this->chainQuery);

        return $this;
    }

    /**
     * Method where
     *
     * @param array $conditions [explicite description]
     *
     * @return object
     */
    public function where(array $conditions = []): object
    {
        if (empty($conditions)) {
            return $this;
        }

        $this->chainQuery .= " WHERE ";
        $index = 0;

        foreach ($conditions as $key => $value) {
            if ($index === count($conditions) - 1) {
                $this->chainQuery .= "$key = '$value'";
            } else {
                $this->chainQuery .= "$key = '$value' AND ";
            }
            
            $index = $index + 1;
        }

        return $this;
    }

    /**
     * Method request
     *
     * @return object|bool
     */
    public function request(): object|bool
    {
        $connection = Database::createConnect();
        $result = $connection->query($this->chainQuery)->fetch(\PDO::FETCH_ASSOC);
        Database::closeConnect($connection);

        if ($result) {
            return $this->morph($result);
        }

        return false;
    }
    
    /**
     * Method requestAll
     *
     * @return array
     */
    public function requestAll(): array|bool
    {
        $connection = Database::createConnect();

        $result = $connection->query($this->chainQuery)->fetchAll(\PDO::FETCH_ASSOC);
        Database::closeConnect($connection);

        if ($result) {
            $array = [];

            foreach ($result as $row) {
                $array[] = $this->morph($row);
            }

            return $array;
        }

        return false;
    }


    /**
     * Method getChainQuery
     *
     * @return string
     */
    public function getChainQuery(): string
    {
        return $this->chainQuery;
    }

    /**
     * Method clearChain
     *
     * @return object
     */
    public function clearChain(): object
    {
        $this->chainQuery = '';

        return $this;
    }

    /**
     * Method update
     *
     * @param array $rows [explicite description]
     *
     * @return object
     */
    public function update(array $rows): object
    {
        $index = 0;
        $this->chainQuery .= "UPDATE {$this->className} SET ";

        foreach ($rows as $key => $value) {
            if ($index === count($rows) - 1) {
                $this->chainQuery .= "{$key} = '{$value}'";
            } else {
                $this->chainQuery .= "{$key} = '{$value}', ";
            }

            $index = $index + 1;
        }

        return $this;
    }

    /**
     * Method create
     *
     * @param array $params [explicite description]
     *
     * @return object|bool
     */
    public function create(array $params = []): object|bool
    {
        if (empty($params)) {
            return false;
        }

        $query = $this->insert($params)->getChainQuery();
        $connection = Database::createConnect();
        $result = $connection->exec($query);

        if ($result) {
            $AI = '_' . $this->classPropertyAI;
            $params[$AI] = $connection->lastInsertId();
            Database::closeConnect($connection);

            return $this->morph($params);
        }

        Database::closeConnect($connection);

        return false;
    }

    /**
     * Method updateOne
     *
     * @param array $rows [explicite description]
     * @param array $conditions [explicite description]
     *
     * @return object|bool
     */
    public function updateOne(array $rows, array $conditions): object|bool
    {
        if (empty($rows)) {
            return false;
        }

        $result = $this->update($rows)->where($conditions)->request();

        if ($result) {
            return $result;
        }

        return false;
    }

    /**
     * Method updateMany
     *
     * @param array $rows [explicite description]
     *
     * @return object|bool
     */
    public function updateMany(array $rows): object|bool
    {
        if (empty($rows)) {
            return false;
        }

        $result = $this->update($rows)->request();

        if ($result) {
            return $result;
        }

        return false;
    }

    /**
     * Method deleteOne
     *
     * @param array $conditions [explicite description]
     *
     * @return object|bool
     */
    public function deleteOne(array $conditions = []): object|bool
    {
        if (empty($conditions)) {
            return false;
        }

        $result = $this->delete()->where($conditions)->request();

        if ($result) {
            return $result;
        }

        return false;
    }
    
    /**
     * Method find
     *
     * @param array $conditions [explicite description]
     *
     * @return array
     */
    public function find(array $conditions): array|object|bool
    {

        $result = $this->select()->where($conditions)->requestAll();

        if ($result) {
            return $result;
        }

        return false;
    }

    /**
     * Method findOne
     *
     * @param array $conditions [explicite description]
     *
     * @return object|bool
     */
    public function findOne(array $conditions): object|bool
    {
        if (empty($conditions)) {
            return false;
        }

        $result = $this->select()->where($conditions)->limit()->request();

        if ($result) {
            return $result;
        }

        return false;
    }

    /**
     * Method findById
     *
     * @param int $id [explicite description]
     *
     * @return object|bool
     */
    public function findById(int $id): object|bool
    {
        if (!$id) {
            return false;
        }

        $result = $this->select()->where([$this->classPropertyAI => $id])->request();

        if ($result) {
            return $result;
        }

        return false;
    }

    /**
     * Method morph
     *
     * @param $data $data [explicite description]
     *
     * @return object
     */
    private function morph($data): object
    {
        $className = $this->className . 'Model';
        $class = new $className;

        foreach ($data as $key => $value) {
            if ($key === $this->classPropertyAI) {
                $AI = '_' . $this->classPropertyAI;
                $class->$AI = $value;
            } else {
                $class->$key = $value;
            }
        }

        return $class;
    }
}
