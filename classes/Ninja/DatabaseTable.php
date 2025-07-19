<?php

namespace Ninja;

class DatabaseTable
{

    public $pdo;
    public $table;
    public $primaryKey;
    private $className;
    private $constructorArgs;

    public function __construct(
        \PDO $pdo,
        string $table,
        string $primaryKey,
        string $className = '\stdClass',
        array $constructorArgs = []
    ) {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
        $this->className = $className;
        $this->constructorArgs = $constructorArgs;
    }

    /** 
     * 
     * Private Function 
     * 
     * */

    private function query(string $sql, array $parameters = [])
    {
        $query = $this->pdo->prepare($sql);
        $query->execute($parameters);

        return $query;
    }

    private function insert(array $fields)
    {
        $query = 'INSERT INTO `' . $this->table . '` (';

        foreach ($fields as $key => $value) {
            $query .= '`' . $key . '`,';
        }

        $query = rtrim($query, ',');

        $query .= ') VALUES (';

        foreach ($fields as $key => $value) {
            $query .= ':' . $key . ',';
        }

        $query = rtrim($query, ',');

        $query .= ')';

        $fields = $this->processDates($fields);

        $this->query($query, $fields);

        return $this->pdo->lastInsertId();
    }

    private function update(array $fields)
    {
        $query = 'UPDATE `' . $this->table . '` SET';

        foreach ($fields as $key => $value) {
            $query .= '`' . $key . '` = :' . $key . ',';
        }
        $query = rtrim($query, ',');

        $query .= ' WHERE `' . $this->primaryKey . '` = :primaryKey';

        $fields = $this->processDates($fields);

        //Imposta la variabile :primaryKey
        $fields['primaryKey'] = $fields['id'];

        $this->query($query, $fields);
    }

    private function processDates(array $fields)
    {
        foreach ($fields as $key => $value) {
            if ($value instanceof \DateTime) {
                $fields[$key] = $value->format('Y-m-d');
            }
        }

        return $fields;
    }

    /** 
     * 
     * Public Function 
     * 
     * */

    public function findAll()
    {
        $result = $this->query('SELECT * FROM `' . $this->table . '`');

        return $result->fetchAll(\PDO::FETCH_CLASS, $this->className, $this->constructorArgs);
    }

    // Recupera i campi di una tabella mysql
    public function describe()
    {
        $result = $this->query('DESCRIBE`' . $this->table . '`');

        $describe = $result->fetchAll();

        foreach ($describe as $value) {
            $fields[] = $value['Field'];
        }

        return $fields;
    }
    public function delete(string $id)
    {
        $parameters = [':id' => $id];

        $query = 'DELETE FROM `' . $this->table . '` WHERE `' . $this->primaryKey . '` = :id';

        $this->query($query, $parameters);
    }

    public function findById(string $value)
    {

        $query = 'SELECT * FROM `' . $this->table . '` WHERE `' . $this->primaryKey . '` = :value';

        $parameters = [':value' => $value];

        $query = $this->query($query, $parameters);

        return $query->fetchObject($this->className, $this->constructorArgs);
    }

    public function find($column, $value)
    {
        $query = 'SELECT * FROM `' . $this->table . '` WHERE `' . $column . '` = :value';

        $parameters = ['value' => $value];

        $result = $this->query($query, $parameters);

        return $result->fetchAll(\PDO::FETCH_CLASS, $this->className, $this->constructorArgs);
    }

    public function total()
    {
        $query = 'SELECT COUNT(*) FROM `' . $this->table . '`';

        $query = $this->query($query);

        $row = $query->fetch();

        return $row[0];
    }

    public function save(array $record)
    {
        $entity = new $this->className(...$this->constructorArgs);
        try {
            if ($record[$this->primaryKey] == '') {
                $record[$this->primaryKey] = null;
            }
            $insertId = $this->insert($record);
            $entity->{$this->primaryKey} = $insertId;
        } catch (\PDOException $e) {
            $this->update($record);
        }

        foreach ($record as $key => $value) {
            if (!empty($value)) {
                $entity->$key = $value;
            }
        }

        return $entity;
    }
}
